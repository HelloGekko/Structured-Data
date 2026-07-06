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
	var mode = $wrap.attr( 'data-mode' ) || 'new';

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

		// Reviews panel only for types Google supports as itemReviewed; for
		// blocked types (Service, Organization) show the explanation instead.
		$wrap.find( '.hgsd-reviews-wrap' ).attr( 'hidden', ! type.supportsReviews );
		var $blocked = $wrap.find( '.hgsd-reviews-blocked' );
		$blocked.attr( 'hidden', ! type.reviewsBlocked );
		$blocked.find( '.hgsd-reviews-blocked-text' ).text( type.reviewsBlocked || '' );

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

	function fillPropertySelect( $sel, selected, showAll ) {
		$sel.empty();
		$sel.append( opt( '', HGSD.i18n.selectValue, ! selected ) );

		var $rec = $( '<optgroup />' ).attr( 'label', '★ ' + HGSD.i18n.recommended );
		var $all = $( '<optgroup />' ).attr( 'label', HGSD.i18n.allProperties );
		var recCount = 0;
		var allCount = 0;

		$.each( currentProps, function ( key, def ) {
			var o = opt( key, def.label, key === selected );
			if ( def.comment ) {
				o.title = def.comment;
			}
			if ( def.recommended ) {
				$rec.append( o );
				recCount++;
			} else if ( showAll ) {
				$all.append( o );
				allCount++;
			}
		} );

		if ( recCount ) {
			$sel.append( $rec );
		}
		if ( showAll ) {
			if ( allCount ) {
				$sel.append( $all );
			}
		} else {
			$sel.append( opt( '__all__', HGSD.i18n.showAll, false ) );
		}
	}

	// Sub-property definitions per expandable schema.org class (AJAX-cached).
	var classCache = {};

	function loadClassProps( cls ) {
		if ( classCache[ cls ] ) {
			return $.Deferred().resolve( classCache[ cls ] ).promise();
		}
		return $.getJSON( HGSD.ajaxUrl, {
			action: 'hgsd_class_props',
			nonce: HGSD.nonce,
			class: cls
		} ).then( function ( res ) {
			classCache[ cls ] = ( res && res.success ) ? res.data : {};
			return classCache[ cls ];
		} );
	}

	// Definition for a full (possibly two-tier) property key.
	function resolveDef( fullKey ) {
		if ( currentProps[ fullKey ] ) {
			return currentProps[ fullKey ];
		}
		if ( fullKey && fullKey.indexOf( '.' ) !== -1 ) {
			var head = fullKey.split( '.' )[ 0 ];
			var leaf = fullKey.substring( head.length + 1 );
			var hd = currentProps[ head ];
			if ( hd && hd.object && classCache[ hd.object ] && classCache[ hd.object ][ leaf ] ) {
				return classCache[ hd.object ][ leaf ];
			}
		}
		return {};
	}

	function fillSubSelect( $sub, props, selected ) {
		$sub.empty().append( opt( '', HGSD.i18n.subProperty, ! selected ) );
		$.each( props, function ( key, def ) {
			var o = opt( key, def.label, key === selected );
			if ( def.comment ) {
				o.title = def.comment;
			}
			$sub.append( o );
		} );
	}

	function buildPropertyRow( data ) {
		data = data || {};
		var id = uid();
		var base = 'hgsd[properties][' + id + ']';

		var $row = $( '<div class="hgsd-row hgsd-property-row" />' );

		// The saved key may be a plain property or "objectProp.subProp".
		var savedKey = data.property || '';
		var savedHead = savedKey;
		var savedLeaf = '';
		if ( savedKey && ! currentProps[ savedKey ] && savedKey.indexOf( '.' ) !== -1 ) {
			var head = savedKey.split( '.' )[ 0 ];
			if ( currentProps[ head ] && currentProps[ head ].object ) {
				savedHead = head;
				savedLeaf = savedKey.substring( head.length + 1 );
			}
		}

		// Show the full list up front when editing a non-recommended property.
		var showAll = !! ( savedHead && currentProps[ savedHead ] && ! currentProps[ savedHead ].recommended );

		var $prop = $( '<select class="hgsd-prop-select" />' );
		fillPropertySelect( $prop, savedHead, showAll );

		var $sub = $( '<select class="hgsd-subprop" />' ).hide();
		var $propHidden = $( '<input type="hidden" />' ).attr( 'name', base + '[property]' ).val( savedKey );

		function syncHidden() {
			var key = $prop.val() || '';
			var def = currentProps[ key ] || {};
			if ( '__all__' === key ) {
				key = '';
			} else if ( def.object ) {
				key = $sub.val() ? key + '.' + $sub.val() : '';
			}
			$propHidden.val( key ).trigger( 'change' );
		}

		var $desc = $( '<p class="hgsd-prop-desc" />' );
		function updateDesc() {
			var d = resolveDef( $propHidden.val() );
			if ( ! d.comment ) {
				d = currentProps[ $prop.val() ] || {};
			}
			$desc.text( d.comment || '' );
		}

		function renderValue( current ) {
			renderValueControl( $value, base, $source.val(), current || '', $propHidden.val() );
		}

		function applyProp( leafToSelect, currentValue ) {
			var def = currentProps[ $prop.val() ] || {};
			if ( def.object ) {
				$sub.show();
				loadClassProps( def.object ).done( function ( props ) {
					fillSubSelect( $sub, props, leafToSelect || '' );
					syncHidden();
					updateDesc();
					renderValue( currentValue || '' );
				} );
				return;
			}
			$sub.hide().empty();
			syncHidden();
			updateDesc();
			renderValue( currentValue || '' );
		}

		$prop.on( 'change', function () {
			if ( '__all__' === $( this ).val() ) {
				fillPropertySelect( $prop, '', true );
				$prop.trigger( 'focus' );
				return;
			}
			applyProp( '', '' );
		} );

		$sub.on( 'change', function () {
			syncHidden();
			updateDesc();
			renderValue( '' );
		} );

		var $source = $( '<select class="hgsd-source" />' ).attr( 'name', base + '[source]' );
		fillSelect( $source, {
			wp: HGSD.i18n.wordpress,
			acf: HGSD.i18n.acf,
			media: HGSD.i18n.media,
			custom: HGSD.i18n.customText
		}, data.source || 'wp', null );

		var $value = $( '<span class="hgsd-value" />' );

		var $remove = $( '<button type="button" class="button-link hgsd-remove" />' ).text( HGSD.i18n.remove );

		$row.append(
			$( '<span class="hgsd-col" />' ).append( $prop, $sub, $propHidden ),
			$( '<span class="hgsd-col" />' ).append( $source ),
			$( '<span class="hgsd-col hgsd-col-value" />' ).append( $value ),
			$remove,
			$desc
		);

		if ( savedLeaf ) {
			applyProp( savedLeaf, data.value || '' );
		} else {
			renderValueControl( $value, base, data.source || 'wp', data.value || '', savedKey );
			updateDesc();
		}

		$source.on( 'change', function () {
			renderValue( '' );
		} );

		return $row;
	}

	function renderValueControl( $value, base, source, current, propKey ) {
		$value.empty();
		var name = base + '[value]';
		var def = resolveDef( propKey );

		if ( 'custom' === source ) {
			// Fixed list of allowed values for enumeration properties.
			if ( def.enum && def.enum.length ) {
				var $e = $( '<select />' ).attr( 'name', name );
				$e.append( opt( '', HGSD.i18n.selectValue, ! current ) );
				$.each( def.enum, function ( i, o ) {
					$e.append( opt( o.value, o.label, o.value === current ) );
				} );
				$value.append( $e );
				return;
			}
			// True/false for boolean properties.
			if ( 'boolean' === def.type ) {
				var $b = $( '<select />' ).attr( 'name', name );
				$b.append( opt( '', HGSD.i18n.selectValue, ! current ) );
				$b.append( opt( 'true', 'true', 'true' === current ) );
				$b.append( opt( 'false', 'false', 'false' === current ) );
				$value.append( $b );
				return;
			}
			$value.append(
				$( '<input type="text" class="widefat" />' )
					.attr( 'name', name )
					.attr( 'placeholder', '{{title}} …' )
					.val( current )
			);
			return;
		}

		if ( 'media' === source ) {
			renderMediaControl( $value, name, current );
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

	function renderMediaControl( $value, name, current ) {
		var $box = $( '<span class="hgsd-media" />' );
		var $hidden = $( '<input type="hidden" />' ).attr( 'name', name ).val( current || '' );
		var $img = $( '<img class="hgsd-media-preview" alt="" />' );
		var $pick = $( '<button type="button" class="button hgsd-media-pick" />' );
		var $remove = $( '<button type="button" class="button-link hgsd-media-remove" />' ).text( HGSD.i18n.remove );

		function reflect( url ) {
			if ( url ) {
				$img.attr( 'src', url ).show();
				$pick.text( HGSD.i18n.changeImage );
				$remove.show();
			} else {
				$img.attr( 'src', '' ).hide();
				$pick.text( HGSD.i18n.selectImage );
				$remove.hide();
			}
		}

		$pick.on( 'click', function ( e ) {
			e.preventDefault();
			if ( typeof wp === 'undefined' || ! wp.media ) {
				// Media library unavailable — fall back to a URL field.
				$box.replaceWith( $( '<input type="text" class="widefat" />' ).attr( 'name', name ).val( $hidden.val() ) );
				return;
			}
			var frame = wp.media( {
				title: HGSD.i18n.selectImage,
				button: { text: HGSD.i18n.useImage },
				library: { type: 'image' },
				multiple: false
			} );
			frame.on( 'select', function () {
				var att = frame.state().get( 'selection' ).first().toJSON();
				var url = ( att.sizes && att.sizes.full ) ? att.sizes.full.url : att.url;
				$hidden.val( url );
				reflect( url );
				$hidden.trigger( 'change' );
			} );
			frame.open();
		} );

		$remove.on( 'click', function () {
			$hidden.val( '' );
			reflect( '' );
			$hidden.trigger( 'change' );
		} );

		$box.append( $hidden, $img, $pick, $remove );
		$value.append( $box );
		reflect( current || '' );
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

			case 'acf_field':
				if ( ! HGSD.hasAcf ) {
					$cell.append( $( '<em class="hgsd-note" />' ).text( HGSD.i18n.noAcf ) );
					return;
				}
				buildAcfCondition( $cell, nameValue, nameValue2, data );
				return;
		}
	}

	/* ACF field condition: pick a field, then a value control that fits it. */
	function buildAcfCondition( $cell, nameValue, nameValue2, data ) {
		var $field = $( '<select class="hgsd-half hgsd-acf-cond-field" />' ).attr( 'name', nameValue );
		var $valueWrap = $( '<span class="hgsd-acf-cond-value" />' );
		$cell.append( $field, $valueWrap );

		loadAcfAll().done( function ( fields ) {
			$field.append( opt( '', HGSD.i18n.selectField, ! data.value ) );
			$.each( fields, function ( i, f ) {
				$field.append( opt( f.name, f.label || f.name, f.name === data.value ) );
			} );

			function draw( savedVal ) {
				renderAcfValueControl( $valueWrap, nameValue2, findAcfField( fields, $field.val() ), savedVal );
			}

			draw( data.value2 || '' );
			$field.on( 'change', function () {
				draw( '' );
			} );
		} );
	}

	function findAcfField( fields, name ) {
		var found = null;
		$.each( fields, function ( i, f ) {
			if ( f.name === name ) {
				found = f;
				return false;
			}
		} );
		return found;
	}

	function renderAcfValueControl( $wrap, name, field, savedVal ) {
		$wrap.empty();
		savedVal = savedVal || '';

		if ( ! field ) {
			$wrap.append( acfTextInput( name, savedVal ) );
			return;
		}

		var type = field.type || 'text';

		// Boolean toggle → true / false select.
		if ( type === 'true_false' ) {
			$wrap.append( select( name, { '1': HGSD.i18n.acfTrue, '0': HGSD.i18n.acfFalse }, '' === savedVal ? '1' : savedVal ) );
			return;
		}

		// Any field with defined choices (select, radio, checkbox, button group).
		if ( field.choices && field.choices.length ) {
			var $s = $( '<select />' ).attr( 'name', name );
			$s.append( opt( '', HGSD.i18n.acfAnyValue, '' === savedVal ) );
			$.each( field.choices, function ( i, c ) {
				$s.append( opt( c.value, c.label || c.value, c.value === savedVal ) );
			} );
			$wrap.append( $s );
			return;
		}

		// Numeric fields.
		if ( type === 'number' || type === 'range' ) {
			$wrap.append(
				$( '<input type="number" />' ).attr( 'name', name ).attr( 'placeholder', HGSD.i18n.acfAnyValue ).val( savedVal )
			);
			return;
		}

		// Everything else compares as text.
		$wrap.append( acfTextInput( name, savedVal ) );
	}

	function acfTextInput( name, savedVal ) {
		return $( '<input type="text" />' ).attr( 'name', name ).attr( 'placeholder', HGSD.i18n.acfAnyValue ).val( savedVal );
	}

	function select( name, items, selected ) {
		var $s = $( '<select />' ).attr( 'name', name );
		fillSelect( $s, items, selected, null );
		return $s;
	}

	function searchSelect( name, object, arg, savedValue, onSelect ) {
		var $box = $( '<span class="hgsd-search" />' );
		var $input = $( '<input type="text" class="hgsd-search-input" autocomplete="off" />' ).attr( 'placeholder', HGSD.i18n.searchPosts );
		var $hidden = $( '<input type="hidden" />' ).attr( 'name', name ).val( savedValue || '' );
		var $list = $( '<ul class="hgsd-search-results" />' ).hide();

		$box.append( $input, $hidden, $list );

		function choose( id, text ) {
			$hidden.val( id );
			$input.val( text );
			$list.hide().empty();
			$hidden.trigger( 'change' );
			if ( typeof onSelect === 'function' ) {
				onSelect( id, text );
			}
		}

		function render( items ) {
			$list.empty();
			if ( ! items || ! items.length ) {
				$list.hide();
				return;
			}
			$.each( items, function ( i, item ) {
				$( '<li />' ).text( item.text ).attr( 'data-id', item.id ).appendTo( $list );
			} );
			$list.show();
		}

		var timer = null;
		function search() {
			var term = $input.val();
			clearTimeout( timer );
			timer = setTimeout( function () {
				$.getJSON( HGSD.ajaxUrl, {
					action: 'hgsd_search_content',
					nonce: HGSD.nonce,
					object: object,
					arg: arg,
					search: term
				} ).done( function ( res ) {
					render( ( res && res.success ) ? res.data : [] );
				} );
			}, 250 );
		}

		$input.on( 'input focus', search );
		$list.on( 'click', 'li', function () {
			choose( $( this ).attr( 'data-id' ), $( this ).text() );
		} );
		$( document ).on( 'click', function ( e ) {
			if ( $box[0] && ! $.contains( $box[0], e.target ) && e.target !== $box[0] ) {
				$list.hide();
			}
		} );

		// Resolve the label for a pre-saved id so the field isn't blank on load.
		if ( savedValue ) {
			$.getJSON( HGSD.ajaxUrl, {
				action: 'hgsd_search_content',
				nonce: HGSD.nonce,
				object: object,
				arg: arg,
				id: savedValue
			} ).done( function ( res ) {
				$input.val( ( res && res.success && res.data.length ) ? res.data[0].text : '#' + savedValue );
			} );
		}

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
		var $opCol = $( '<span class="hgsd-col" />' ).append( $op );

		var $cell = $( '<span class="hgsd-col hgsd-col-value" />' );

		var $remove = $( '<button type="button" class="button-link hgsd-remove" />' ).text( HGSD.i18n.remove );

		$row.append(
			$( '<span class="hgsd-col" />' ).append( $type ),
			$opCol,
			$cell,
			$remove
		);

		function applyConditionType( type, values ) {
			// "Show globally" and "Homepage" are simple flags — no operator/value.
			$opCol.toggle( hasOperator( type ) );
			conditionValueControl( $cell, base, type, values );
		}

		applyConditionType( data.type || '', data );

		$type.on( 'change', function () {
			applyConditionType( $( this ).val(), {} );
		} );

		return $row;
	}

	function hasOperator( type ) {
		return [ '', 'global', 'homepage' ].indexOf( type ) === -1;
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

	/* ------------------------------------------------------------- live preview */

	var previewTimer = null;
	var previewPostId = '';

	function schedulePreview() {
		clearTimeout( previewTimer );
		previewTimer = setTimeout( refreshPreview, 600 );
	}

	function refreshPreview() {
		var $out = $wrap.find( '.hgsd-preview-output' );
		var $note = $wrap.find( '.hgsd-preview-note' );
		var payload = $wrap.find( 'select, input, textarea' ).serialize();
		var extra = previewPostId ? '&preview_post=' + encodeURIComponent( previewPostId ) : '';

		$note.text( HGSD.i18n.previewLoading );

		$.post(
			HGSD.ajaxUrl,
			payload + '&action=hgsd_preview&nonce=' + encodeURIComponent( HGSD.nonce ) + extra,
			function ( res ) {
				if ( res && res.success ) {
					if ( res.data.empty ) {
						$out.text( '' );
						$note.text( res.data.note || HGSD.i18n.previewEmpty );
					} else {
						$out.text( res.data.json );
						$note.text( res.data.note || '' );
					}
				} else {
					$out.text( '' );
					$note.text( '' );
				}
			}
		).fail( function () {
			$note.text( '' );
		} );
	}

	$wrap.on( 'change keyup', 'select, input, textarea', schedulePreview );
	$wrap.on( 'click', '.hgsd-preview-refresh', function () {
		refreshPreview();
	} );

	// Field-source: toggle the source-post picker and build its search control.
	function initSourceControl() {
		var $mode = $wrap.find( '.hgsd-source-mode' );
		var $postRow = $wrap.find( '.hgsd-source-post' );
		var $ctrl = $wrap.find( '.hgsd-source-post-control' );
		if ( ! $mode.length ) {
			return;
		}
		if ( ! $ctrl.data( 'built' ) ) {
			$ctrl.data( 'built', true );
			$ctrl.append( searchSelect( 'hgsd[source][post_id]', 'post', 'any', $ctrl.attr( 'data-selected' ) || '' ) );
		}
		function toggle() {
			$postRow.attr( 'hidden', 'post' !== $mode.val() );
		}
		$mode.off( 'change.hgsdsrc' ).on( 'change.hgsdsrc', toggle );
		toggle();
	}

	// Live-preview post picker (not part of the saved form).
	function initPreviewPicker() {
		var $c = $wrap.find( '.hgsd-preview-post-control' );
		if ( ! $c.length || $c.data( 'built' ) ) {
			return;
		}
		$c.data( 'built', true );
		$c.append( searchSelect( '', 'post', 'any', '', function ( id ) {
			previewPostId = id;
			refreshPreview();
		} ) );
	}

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

		initSourceControl();
		initPreviewPicker();

		if ( 'edit' === mode ) {
			// Existing schema: drop the step chrome and show everything at once.
			$wrap.find( '.hgsd-steps, .hgsd-nav' ).hide();
			$wrap.find( '.hgsd-step' ).removeAttr( 'hidden' );
		} else {
			showStep( 1 );
		}

		refreshPreview();
	}

	rehydrate();

} )( jQuery );
