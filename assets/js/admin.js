/* global HGSD, jQuery */
( function ( $ ) {
	'use strict';

	if ( typeof HGSD === 'undefined' ) {
		return;
	}

	var $wrap = $( '#hgsd-wizard' );
	if ( ! $wrap.length ) {
		return;
	}

	var counter = 0;
	var acfCache = { all: null, repeaters: null, subfields: {} };
	var currentProps = {};

	/* -------------------------------------------------------------- helpers */

	function uid() {
		return 'r' + ( counter++ );
	}

	function opt( value, label, selected ) {
		var o = document.createElement( 'option' );
		o.value = value;
		o.textContent = label;
		if ( selected ) {
			o.selected = true;
		}
		return o;
	}

	function fillSelect( $select, items, selected, placeholder ) {
		$select.empty();
		if ( placeholder ) {
			$select.append( opt( '', placeholder, false ) );
		}
		$.each( items, function ( value, label ) {
			$select.append( opt( value, label, String( value ) === String( selected ) ) );
		} );
	}

	function acfRequest( mode, repeater ) {
		return $.getJSON( HGSD.ajaxUrl, {
			action: 'hgsd_get_acf_fields',
			nonce: HGSD.nonce,
			mode: mode,
			repeater: repeater || ''
		} );
	}

	/* ----------------------------------------------------------- step navigation */

	var step = 1;
	function showStep( n ) {
		step = n;
		$wrap.find( '.hgsd-step' ).attr( 'hidden', true );
		$wrap.find( '.hgsd-step[data-step="' + n + '"]' ).removeAttr( 'hidden' );
		$wrap.find( '.hgsd-steps li' ).removeClass( 'is-active' );
		$wrap.find( '.hgsd-steps li[data-step="' + n + '"]' ).addClass( 'is-active' );
		$wrap.find( '.hgsd-prev' ).attr( 'hidden', n === 1 );
		$wrap.find( '.hgsd-next' ).attr( 'hidden', n === 3 );
		$wrap.find( '.hgsd-final-hint' ).attr( 'hidden', n !== 3 );
	}

	$wrap.on( 'click', '.hgsd-next', function () {
		showStep( Math.min( 3, step + 1 ) );
	} );
	$wrap.on( 'click', '.hgsd-prev', function () {
		showStep( Math.max( 1, step - 1 ) );
	} );
	$wrap.on( 'click', '.hgsd-steps li', function () {
		showStep( parseInt( $( this ).data( 'step' ), 10 ) );
	} );

	/* --------------------------------------------------------------- type selection */

	function selectedType() {
		return $wrap.find( 'input[name="hgsd[type]"]:checked' ).val() || '';
	}

	function applyType( rebuildRows ) {
		var key = selectedType();
		var type = HGSD.types[ key ] || { properties: {}, isFaq: false };
		currentProps = type.properties || {};

		// Toggle FAQ vs generic property UI.
		$wrap.find( '.hgsd-faq-wrap' ).attr( 'hidden', ! type.isFaq );
		$wrap.find( '.hgsd-properties-wrap' ).attr( 'hidden', !! type.isFaq );

		if ( rebuildRows && ! type.isFaq ) {
			$wrap.find( '.hgsd-properties' ).empty();
		}
		if ( type.isFaq ) {
			initFaq();
		}
	}

	$wrap.on( 'change', 'input[name="hgsd[type]"]', function () {
		applyType( true );
	} );

	/* --------------------------------------------------------- property mapping rows */

	function propertyOptions() {
		var items = { '': HGSD.i18n.selectValue };
		$.each( currentProps, function ( key, def ) {
			items[ key ] = def.label + ( def.recommended ? ' ★' : '' );
		} );
		return items;
	}

	function buildPropertyRow( data ) {
		data = data || {};
		var id = uid();
		var base = 'hgsd[properties][' + id + ']';

		var $row = $( '<div class="hgsd-row hgsd-property-row" />' );

		var $prop = $( '<select />' ).attr( 'name', base + '[property]' );
		fillSelect( $prop, propertyOptions(), data.property, null );

		var $source = $( '<select class="hgsd-source" />' ).attr( 'name', base + '[source]' );
		fillSelect( $source, {
			wp: HGSD.i18n.wordpress,
			acf: HGSD.i18n.acf,
			custom: HGSD.i18n.customText
		}, data.source || 'wp', null );

		var $value = $( '<span class="hgsd-value" />' );

		var $remove = $( '<button type="button" class="button-link hgsd-remove" />' ).text( HGSD.i18n.remove );

		$row.append(
			$( '<span class="hgsd-col" />' ).append( $prop ),
			$( '<span class="hgsd-col" />' ).append( $source ),
			$( '<span class="hgsd-col hgsd-col-value" />' ).append( $value ),
			$remove
		);

		renderValueControl( $value, base, data.source || 'wp', data.value || '' );

		$source.on( 'change', function () {
			renderValueControl( $value, base, $( this ).val(), '' );
		} );

		return $row;
	}

	function renderValueControl( $value, base, source, current ) {
		$value.empty();
		var name = base + '[value]';

		if ( 'custom' === source ) {
			$value.append(
				$( '<input type="text" class="widefat" />' )
					.attr( 'name', name )
					.attr( 'placeholder', '{{title}} …' )
					.val( current )
			);
			return;
		}

		if ( 'acf' === source ) {
			if ( ! HGSD.hasAcf ) {
				$value.append( $( '<em class="hgsd-note" />' ).text( HGSD.i18n.noAcf ) );
				$value.append( $( '<input type="hidden" />' ).attr( 'name', name ).val( '' ) );
				return;
			}
			var $sel = $( '<select />' ).attr( 'name', name );
			$sel.append( opt( '', HGSD.i18n.selectField, false ) );
			$value.append( $sel );
			loadAcfAll().done( function ( fields ) {
				$.each( fields, function ( i, f ) {
					$sel.append( opt( f.name, f.label + ' (' + f.name + ')', f.name === current ) );
				} );
			} );
			return;
		}

		// WordPress fields.
		var $wp = $( '<select />' ).attr( 'name', name );
		fillSelect( $wp, HGSD.wpFields, current, HGSD.i18n.selectField );
		$value.append( $wp );
	}

	function loadAcfAll() {
		if ( acfCache.all ) {
			return $.Deferred().resolve( acfCache.all ).promise();
		}
		return acfRequest( 'all' ).then( function ( res ) {
			acfCache.all = ( res && res.success ) ? res.data : [];
			return acfCache.all;
		} );
	}

	$wrap.on( 'click', '.hgsd-add-property', function () {
		$wrap.find( '.hgsd-properties' ).append( buildPropertyRow() );
	} );

	/* ------------------------------------------------------------- condition rows */

	function conditionValueControl( $cell, base, type, data ) {
		$cell.empty();
		var nameValue = base + '[value]';
		var nameValue2 = base + '[value2]';

		switch ( type ) {
			case 'global':
			case 'homepage':
				return;

			case 'post_type':
				$cell.append( select( nameValue, HGSD.postTypes, data.value ) );
				return;

			case 'post_format':
				$cell.append( select( nameValue, HGSD.postFormats, data.value ) );
				return;

			case 'page_template':
				$cell.append( select( nameValue, HGSD.pageTemplates, data.value ) );
				return;

			case 'post':
				$cell.append( searchSelect( nameValue, 'post', 'post', data.value ) );
				return;

			case 'page':
				$cell.append( searchSelect( nameValue, 'post', 'page', data.value ) );
				return;

			case 'post_category':
				$cell.append( searchSelect( nameValue, 'term', 'category', data.value ) );
				return;

			case 'taxonomy':
				var $tax = select( nameValue2, HGSD.taxonomies, data.value2 || 'post_tag' );
				var $term = searchSelect( nameValue, 'term', data.value2 || 'post_tag', data.value );
				$tax.on( 'change', function () {
					$term.replaceWith( ( $term = searchSelect( nameValue, 'term', $( this ).val(), '' ) ) );
				} );
				$cell.append( $tax, $term );
				return;

			case 'author':
				$cell.append(
					$( '<input type="number" class="small-text" />' )
						.attr( 'name', nameValue )
						.attr( 'placeholder', 'User ID' )
						.val( data.value || '' )
				);
				return;

			case 'author_name':
				$cell.append(
					$( '<input type="text" />' ).attr( 'name', nameValue ).attr( 'placeholder', 'name' ).val( data.value || '' )
				);
				return;

			case 'url_parameter':
				$cell.append(
					$( '<input type="text" class="hgsd-half" />' ).attr( 'name', nameValue ).attr( 'placeholder', 'parameter' ).val( data.value || '' ),
					$( '<input type="text" class="hgsd-half" />' ).attr( 'name', nameValue2 ).attr( 'placeholder', 'value (optional)' ).val( data.value2 || '' )
				);
				return;

			case 'date':
				$cell.append(
					$( '<input type="date" />' ).attr( 'name', nameValue ).val( data.value || '' )
				);
				return;
		}
	}

	function select( name, items, selected ) {
		var $s = $( '<select />' ).attr( 'name', name );
		fillSelect( $s, items, selected, null );
		return $s;
	}

	function searchSelect( name, object, arg, savedValue ) {
		var $box = $( '<span class="hgsd-search" />' );
		var $input = $( '<input type="text" class="hgsd-search-input" />' ).attr( 'placeholder', HGSD.i18n.searchPosts );
		var $sel = $( '<select class="hgsd-search-select" />' ).attr( 'name', name );

		if ( savedValue ) {
			$sel.append( opt( savedValue, '#' + savedValue, true ) );
		} else {
			$sel.append( opt( '', HGSD.i18n.selectValue, false ) );
		}

		var timer = null;
		$input.on( 'keyup', function () {
			var term = $( this ).val();
			clearTimeout( timer );
			timer = setTimeout( function () {
				$.getJSON( HGSD.ajaxUrl, {
					action: 'hgsd_search_content',
					nonce: HGSD.nonce,
					object: object,
					arg: arg,
					search: term
				} ).done( function ( res ) {
					var keep = $sel.val();
					$sel.empty().append( opt( '', HGSD.i18n.selectValue, false ) );
					if ( res && res.success ) {
						$.each( res.data, function ( i, item ) {
							$sel.append( opt( item.id, item.text, String( item.id ) === String( keep ) ) );
						} );
					}
				} );
			}, 300 );
		} );

		$box.append( $input, $sel );
		return $box;
	}

	function buildConditionRow( group, data ) {
		data = data || {};
		var id = uid();
		var base = 'hgsd[conditions][' + group + '][' + id + ']';

		var $row = $( '<div class="hgsd-row hgsd-condition-row" />' );

		var $type = select( base + '[type]', HGSD.conditionTypes, data.type );
		$type.prepend( opt( '', HGSD.i18n.selectValue, ! data.type ) );

		var $op = select( base + '[operator]', { is: 'is equal to', is_not: 'is not equal to' }, data.operator || 'is' );

		var $cell = $( '<span class="hgsd-col hgsd-col-value" />' );

		var $remove = $( '<button type="button" class="button-link hgsd-remove" />' ).text( HGSD.i18n.remove );

		$row.append(
			$( '<span class="hgsd-col" />' ).append( $type ),
			$( '<span class="hgsd-col" />' ).append( $op ),
			$cell,
			$remove
		);

		conditionValueControl( $cell, base, data.type || '', data );

		$type.on( 'change', function () {
			conditionValueControl( $cell, base, $( this ).val(), {} );
		} );

		return $row;
	}

	$wrap.on( 'click', '.hgsd-add-rule', function () {
		var group = $( this ).data( 'group' );
		$wrap.find( '.hgsd-rules[data-group="' + group + '"]' ).append( buildConditionRow( group ) );
	} );

	/* ---------------------------------------------------------------------- FAQ */

	var faqInited = false;
	function initFaq() {
		if ( faqInited ) {
			return;
		}
		faqInited = true;
		toggleFaqMethod();
	}

	function toggleFaqMethod() {
		var method = $wrap.find( '.hgsd-faq-method' ).val();
		var auto = 'automatic' === method;
		$wrap.find( '.hgsd-faq-automatic' ).attr( 'hidden', ! auto );
		$wrap.find( '.hgsd-faq-manual' ).attr( 'hidden', auto );

		if ( auto ) {
			if ( ! HGSD.hasAcfPro ) {
				$wrap.find( '.hgsd-acf-missing' ).removeAttr( 'hidden' );
			}
			loadFaqRepeaters();
		}
	}

	$wrap.on( 'change', '.hgsd-faq-method', toggleFaqMethod );

	function loadFaqRepeaters() {
		var $rep = $wrap.find( '.hgsd-faq-repeater' );
		if ( $rep.data( 'loaded' ) ) {
			return;
		}
		$rep.data( 'loaded', true );
		acfRequest( 'repeaters' ).done( function ( res ) {
			$rep.empty().append( opt( '', HGSD.i18n.selectField, false ) );
			if ( res && res.success ) {
				$.each( res.data, function ( i, f ) {
					$rep.append( opt( f.name, f.label + ' (' + f.name + ')', f.name === $rep.data( 'selected' ) ) );
				} );
			}
			if ( $rep.val() ) {
				loadFaqSubfields( $rep.val() );
			}
		} );
	}

	function loadFaqSubfields( repeater ) {
		var $q = $wrap.find( '.hgsd-faq-question' );
		var $a = $wrap.find( '.hgsd-faq-answer' );
		acfRequest( 'subfields', repeater ).done( function ( res ) {
			var fields = ( res && res.success ) ? res.data : [];
			[ $q, $a ].forEach( function ( $s ) {
				var sel = $s.data( 'selected' );
				$s.empty().append( opt( '', HGSD.i18n.selectField, false ) );
				$.each( fields, function ( i, f ) {
					$s.append( opt( f.name, f.label + ' (' + f.name + ')', f.name === sel ) );
				} );
			} );
		} );
	}

	$wrap.on( 'change', '.hgsd-faq-repeater', function () {
		$wrap.find( '.hgsd-faq-question, .hgsd-faq-answer' ).removeData( 'selected' );
		loadFaqSubfields( $( this ).val() );
	} );

	function buildFaqItem( data ) {
		data = data || {};
		var id = uid();
		var base = 'hgsd[faq][items][' + id + ']';
		var $row = $( '<div class="hgsd-row hgsd-faq-item" />' );
		$row.append(
			$( '<input type="text" class="widefat" />' ).attr( 'name', base + '[question]' ).attr( 'placeholder', HGSD.i18n.question ).val( data.question || '' ),
			$( '<textarea class="widefat" rows="2" />' ).attr( 'name', base + '[answer]' ).attr( 'placeholder', HGSD.i18n.answer ).val( data.answer || '' ),
			$( '<button type="button" class="button-link hgsd-remove" />' ).text( HGSD.i18n.remove )
		);
		return $row;
	}

	$wrap.on( 'click', '.hgsd-add-faq', function () {
		$wrap.find( '.hgsd-faq-items' ).append( buildFaqItem() );
	} );

	/* ----------------------------------------------------------- generic remove */

	$wrap.on( 'click', '.hgsd-remove', function () {
		$( this ).closest( '.hgsd-row' ).remove();
	} );

	/* ----------------------------------------------------------------- rehydrate */

	function rehydrate() {
		var saved = {};
		try {
			saved = JSON.parse( document.getElementById( 'hgsd-saved' ).textContent || '{}' );
		} catch ( e ) {
			saved = {};
		}

		applyType( false );

		// Properties.
		var props = saved.properties || [];
		if ( props.length ) {
			$.each( props, function ( i, p ) {
				$wrap.find( '.hgsd-properties' ).append( buildPropertyRow( p ) );
			} );
		}

		// Conditions.
		var cond = saved.conditions || {};
		[ 'include', 'exclude' ].forEach( function ( group ) {
			var rules = ( cond[ group ] || [] );
			$.each( rules, function ( i, r ) {
				$wrap.find( '.hgsd-rules[data-group="' + group + '"]' ).append( buildConditionRow( group, r ) );
			} );
		} );

		// FAQ manual items.
		var faq = saved.faq || {};
		$.each( faq.items || [], function ( i, item ) {
			$wrap.find( '.hgsd-faq-items' ).append( buildFaqItem( item ) );
		} );

		showStep( 1 );
	}

	rehydrate();

} )( jQuery );
