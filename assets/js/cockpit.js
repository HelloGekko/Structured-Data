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
