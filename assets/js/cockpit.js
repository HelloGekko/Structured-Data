/* global HGSDCockpit, jQuery */
( function ( $ ) {
	'use strict';

	if ( typeof HGSDCockpit === 'undefined' ) {
		return;
	}

	var $panel = $( '.hgsd-panel' );
	var currentPost = 0;
	var $currentRow = null;

	function rowState( $row ) {
		return {
			cornerstone: $row.find( '.hgsd-cornerstone-toggle' ).prop( 'checked' ) ? 1 : 0,
			canonical: $row.attr( 'data-canonical' ) || '',
			noindex: $row.attr( 'data-noindex' ) === '1' ? 1 : 0,
			nofollow: $row.attr( 'data-nofollow' ) === '1' ? 1 : 0
		};
	}

	function save( postId, state, done ) {
		$.post( HGSDCockpit.ajaxUrl, $.extend( {
			action: 'hgsd_cockpit_save',
			nonce: HGSDCockpit.nonce,
			post_id: postId
		}, state ) ).done( function ( res ) {
			done( res && res.success ? res.data : null );
		} ).fail( function () {
			done( null );
		} );
	}

	function refreshRow( $row, data ) {
		$row.find( '.hgsd-cornerstone-toggle' ).prop( 'checked', !! data.cornerstone );
		$row.attr( 'data-canonical', data.canonical || '' );
		$row.attr( 'data-noindex', data.robots.noindex ? '1' : '0' );
		$row.attr( 'data-nofollow', data.robots.nofollow ? '1' : '0' );

		var flags = [];
		if ( data.robots.noindex ) {
			flags.push( 'noindex' );
		}
		if ( data.robots.nofollow ) {
			flags.push( 'nofollow' );
		}
		$row.find( '.hgsd-robots-cell' ).html( flags.length ? flags.join( ', ' ) : '<span class="hgsd-muted">default</span>' );
		$row.find( '.hgsd-canonical-cell' ).html(
			data.canonical ? '<span class="hgsd-badge hgsd-badge-blue">override</span>' : '<span class="hgsd-muted">default</span>'
		);
	}

	function linkList( $list, items ) {
		$list.empty();
		if ( ! items || ! items.length ) {
			$list.append( $( '<li class="hgsd-muted" />' ).text( HGSDCockpit.i18n.none ) );
			return;
		}
		$.each( items, function ( i, item ) {
			var text = item.title + ( item.anchor ? ' — “' + item.anchor + '”' : '' ) + ( item.context === 'menu' ? ' (menu)' : '' );
			$list.append( $( '<li />' ).text( text ) );
		} );
	}

	function renderRelations( relations ) {
		var $list = $panel.find( '.hgsd-panel-relations' );
		$list.empty();
		if ( ! relations || ! relations.length ) {
			$list.append( $( '<li class="hgsd-muted" />' ).text( HGSDCockpit.i18n.none ) );
			return;
		}
		$.each( relations, function ( i, r ) {
			var $li = $( '<li />' );
			$li.append( $( '<em />' ).text( r.label + ': ' ) );
			$li.append( $( '<a target="_blank" />' ).attr( 'href', r.url ).text( r.title ) );
			if ( ! r.linked ) {
				$li.append( ' ' ).append( $( '<span class="hgsd-badge hgsd-badge-yellow" />' ).text( HGSDCockpit.i18n.noLink ) );
			}
			$li.append( ' ' ).append(
				$( '<button type="button" class="button-link hgsd-rel-delete" />' ).attr( 'data-id', r.id ).text( '✕' )
			);
			$list.append( $li );
		} );
	}

	function renderIncoming( incoming ) {
		var $list = $panel.find( '.hgsd-panel-incoming' );
		$list.empty();
		if ( ! incoming || ! incoming.length ) {
			$list.append( $( '<li class="hgsd-muted" />' ).text( HGSDCockpit.i18n.none ) );
			return;
		}
		$.each( incoming, function ( i, r ) {
			$list.append( $( '<li />' ).text( r.title + ' → ' + r.relation ) );
		} );
	}

	function renderSuggestions( suggestions ) {
		var $list = $panel.find( '.hgsd-panel-suggestions' );
		$list.empty();
		if ( ! suggestions || ! suggestions.length ) {
			$list.append( $( '<li class="hgsd-muted" />' ).text( HGSDCockpit.i18n.none ) );
			return;
		}
		$.each( suggestions, function ( i, s ) {
			var $li = $( '<li />' );
			$li.append( document.createTextNode( HGSDCockpit.i18n.linkTo + ' ' ) );
			$li.append( $( '<a target="_blank" />' ).attr( 'href', s.url ).text( s.title ) );
			$list.append( $li );
		} );
	}

	function fillRelationTypes( types ) {
		var $sel = $panel.find( '.hgsd-rel-type' );
		if ( $sel.children().length ) {
			return;
		}
		$.each( types, function ( key, label ) {
			$sel.append( $( '<option />' ).attr( 'value', key ).text( label ) );
		} );
	}

	function openPanel( $row ) {
		currentPost = parseInt( $row.attr( 'data-post' ), 10 );
		$currentRow = $row;

		$.getJSON( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_detail',
			nonce: HGSDCockpit.nonce,
			post_id: currentPost
		} ).done( function ( res ) {
			if ( ! res || ! res.success ) {
				return;
			}
			var d = res.data;
			$panel.find( '.hgsd-panel-title' ).text( d.title );
			$panel.find( '.hgsd-panel-view' ).attr( 'href', d.url );
			$panel.find( '.hgsd-panel-edit' ).attr( 'href', d.edit );
			$panel.find( '.hgsd-panel-cornerstone' ).prop( 'checked', !! d.cornerstone );
			$panel.find( '.hgsd-panel-canonical' ).val( d.canonical || '' );
			$panel.find( '.hgsd-panel-noindex' ).prop( 'checked', !! d.robots.noindex );
			$panel.find( '.hgsd-panel-nofollow' ).prop( 'checked', !! d.robots.nofollow );
			$panel.find( '.hgsd-panel-status' ).text( '' );
			linkList( $panel.find( '.hgsd-panel-inlinks' ), d.inlinks );
			linkList( $panel.find( '.hgsd-panel-outlinks' ), d.outlinks );
			fillRelationTypes( d.relationTypes || {} );
			renderRelations( d.relations );
			renderIncoming( d.incoming );
			renderSuggestions( d.suggestions );
			$panel.find( '.hgsd-rel-search-input' ).val( '' );
			$panel.find( '.hgsd-rel-target' ).val( '' );
			$panel.removeAttr( 'hidden' );
		} );
	}

	// Open the panel from a row (but not when toggling the checkbox).
	$( document ).on( 'click', '.hgsd-row-item td', function ( e ) {
		if ( $( e.target ).is( 'input, a, button' ) ) {
			return;
		}
		openPanel( $( this ).closest( 'tr' ) );
	} );

	// Inline cornerstone toggle saves immediately with the row's other state.
	$( document ).on( 'change', '.hgsd-cornerstone-toggle', function () {
		var $row = $( this ).closest( 'tr' );
		save( parseInt( $row.attr( 'data-post' ), 10 ), rowState( $row ), function ( data ) {
			if ( data ) {
				refreshRow( $row, data );
			}
		} );
	} );

	// Panel save.
	$panel.on( 'click', '.hgsd-panel-save', function () {
		var state = {
			cornerstone: $panel.find( '.hgsd-panel-cornerstone' ).prop( 'checked' ) ? 1 : 0,
			canonical: $panel.find( '.hgsd-panel-canonical' ).val(),
			noindex: $panel.find( '.hgsd-panel-noindex' ).prop( 'checked' ) ? 1 : 0,
			nofollow: $panel.find( '.hgsd-panel-nofollow' ).prop( 'checked' ) ? 1 : 0
		};
		save( currentPost, state, function ( data ) {
			$panel.find( '.hgsd-panel-status' ).text( data ? HGSDCockpit.i18n.saved : HGSDCockpit.i18n.error );
			if ( data && $currentRow ) {
				refreshRow( $currentRow, data );
			}
		} );
	} );

	$panel.on( 'click', '.hgsd-panel-close', function () {
		$panel.attr( 'hidden', true );
	} );

	// Relation target search (mini combobox against hgsd_search_content).
	var relTimer = null;
	$panel.on( 'input focus', '.hgsd-rel-search-input', function () {
		var term = $( this ).val();
		clearTimeout( relTimer );
		relTimer = setTimeout( function () {
			$.getJSON( HGSDCockpit.ajaxUrl, {
				action: 'hgsd_search_content',
				nonce: HGSDCockpit.nonce,
				object: 'post',
				arg: 'any',
				search: term
			} ).done( function ( res ) {
				var $list = $panel.find( '.hgsd-rel-results' );
				$list.empty();
				if ( res && res.success && res.data.length ) {
					$.each( res.data, function ( i, item ) {
						$list.append( $( '<li />' ).attr( 'data-id', item.id ).text( item.text ) );
					} );
					$list.removeAttr( 'hidden' );
				} else {
					$list.attr( 'hidden', true );
				}
			} );
		}, 250 );
	} );

	$panel.on( 'click', '.hgsd-rel-results li', function () {
		$panel.find( '.hgsd-rel-target' ).val( $( this ).attr( 'data-id' ) );
		$panel.find( '.hgsd-rel-search-input' ).val( $( this ).text() );
		$panel.find( '.hgsd-rel-results' ).attr( 'hidden', true ).empty();
	} );

	$panel.on( 'click', '.hgsd-rel-add', function () {
		var target = parseInt( $panel.find( '.hgsd-rel-target' ).val(), 10 );
		if ( ! target ) {
			return;
		}
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_relation_add',
			nonce: HGSDCockpit.nonce,
			source: currentPost,
			target: target,
			relation: $panel.find( '.hgsd-rel-type' ).val()
		} ).done( function ( res ) {
			if ( res && res.success ) {
				renderRelations( res.data.relations );
				$panel.find( '.hgsd-rel-search-input' ).val( '' );
				$panel.find( '.hgsd-rel-target' ).val( '' );
			}
		} );
	} );

	$panel.on( 'click', '.hgsd-rel-delete', function () {
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_relation_delete',
			nonce: HGSDCockpit.nonce,
			id: $( this ).attr( 'data-id' ),
			source: currentPost
		} ).done( function ( res ) {
			if ( res && res.success ) {
				renderRelations( res.data.relations );
			}
		} );
	} );

	// Reindex.
	$( document ).on( 'click', '.hgsd-reindex', function () {
		var $btn = $( this );
		$btn.prop( 'disabled', true );
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_reindex',
			nonce: HGSDCockpit.nonce
		} ).always( function () {
			window.location.reload();
		} );
	} );

} )( jQuery );
