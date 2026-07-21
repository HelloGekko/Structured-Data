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
			if ( 'mention' === s.reason ) {
				$li.append( ' ' ).append( $( '<span class="hgsd-badge hgsd-badge-yellow" />' ).text( HGSDCockpit.i18n.mentioned ) );
			}
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
		openPanelById( parseInt( $row.attr( 'data-post' ), 10 ), $row );
	}

	function openPanelById( postId, $row ) {
		currentPost = postId;
		$currentRow = $row || null;

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
			renderGsc( d.gsc, d.gscReady );
			renderIndex( d.indexReady, d.indexStatus );
			renderReadability( d.readability, HGSDCockpit.i18n.aiClean );
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

	/* ------------------------------------------------------------ search console */

	function renderGsc( gsc, ready ) {
		var $facts = $panel.find( '.hgsd-panel-gsc-facts' );
		var $btn = $panel.find( '.hgsd-gsc-inspect' );
		$facts.empty();
		$panel.find( '.hgsd-gsc-status' ).text( '' );

		if ( ! ready ) {
			$facts.append( $( '<li class="hgsd-muted" />' ).text( HGSDCockpit.i18n.gscOff ) );
			$btn.hide();
			return;
		}
		$btn.show();

		if ( ! gsc || ! gsc.coverage ) {
			$facts.append( $( '<li class="hgsd-muted" />' ).text( HGSDCockpit.i18n.gscNone ) );
			return;
		}
		$facts.append( $( '<li />' ).text( gsc.coverage ) );
		if ( gsc.google_canonical ) {
			$facts.append( $( '<li />' ).text( 'Google canonical: ' + gsc.google_canonical ) );
		}
		if ( gsc.last_crawl ) {
			$facts.append( $( '<li />' ).text( 'Last crawl: ' + gsc.last_crawl.substring( 0, 10 ) ) );
		}
	}

	$panel.on( 'click', '.hgsd-gsc-inspect', function () {
		var $status = $panel.find( '.hgsd-gsc-status' );
		$status.text( '…' );
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_gsc',
			nonce: HGSDCockpit.nonce,
			post_id: currentPost
		} ).done( function ( res ) {
			if ( res && res.success ) {
				renderGsc( res.data, true );
			} else {
				$status.text( ( res && res.data && res.data.message ) ? res.data.message : HGSDCockpit.i18n.error );
			}
		} );
	} );

	/* --------------------------------------------------- AI readability */

	function renderReadability( messages, cleanText ) {
		var $facts = $panel.find( '.hgsd-panel-ai-facts' );
		$panel.find( '.hgsd-ai-status' ).text( '' );
		$facts.empty();

		if ( ! messages || ! messages.length ) {
			$facts.append( $( '<li class="hgsd-ai-ok" />' ).text( cleanText ) );
			return;
		}
		$.each( messages, function ( i, m ) {
			$facts.append( $( '<li />' ).text( m ) );
		} );
	}

	$panel.on( 'click', '.hgsd-ai-recheck', function () {
		var $btn = $( this );
		var $status = $panel.find( '.hgsd-ai-status' );
		$btn.prop( 'disabled', true );
		$status.text( '…' );
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_airecheck',
			nonce: HGSDCockpit.nonce,
			post_id: currentPost
		} ).done( function ( res ) {
			$btn.prop( 'disabled', false );
			if ( res && res.success ) {
				$status.text( '' );
				renderReadability( res.data.messages, HGSDCockpit.i18n.aiCleanFull );
			} else {
				$status.text( ( res && res.data && res.data.message ) ? res.data.message : HGSDCockpit.i18n.error );
			}
		} );
	} );

	/* --------------------------------------------------- instant indexing */

	function renderIndex( ready, status ) {
		var $facts = $panel.find( '.hgsd-panel-index-facts' );
		var $btn = $panel.find( '.hgsd-index-submit' );
		$facts.empty();
		$panel.find( '.hgsd-index-status' ).text( '' );

		if ( ! ready ) {
			$facts.append( $( '<li class="hgsd-muted" />' ).text( HGSDCockpit.i18n.indexOff ) );
			$btn.hide();
			return;
		}
		$btn.text( HGSDCockpit.i18n.indexBtn ).prop( 'disabled', false ).show();

		if ( status && status.time ) {
			var when = new Date( status.time * 1000 ).toISOString().substring( 0, 10 );
			var ok = 'error' !== status.status;
			$facts.append(
				$( '<li />' ).text( HGSDCockpit.i18n.indexOn + ': ' + when + ( ok ? ' ✓' : ' ✕' ) )
			);
		} else {
			$facts.append( $( '<li class="hgsd-muted" />' ).text( HGSDCockpit.i18n.indexNever ) );
		}
	}

	$panel.on( 'click', '.hgsd-index-submit', function () {
		var $btn = $( this );
		var $status = $panel.find( '.hgsd-index-status' );
		$btn.prop( 'disabled', true ).text( HGSDCockpit.i18n.indexBusy );
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_index',
			nonce: HGSDCockpit.nonce,
			post_id: currentPost
		} ).done( function ( res ) {
			$btn.prop( 'disabled', false ).text( HGSDCockpit.i18n.indexBtn );
			if ( res && res.success ) {
				$status.text( res.data.message || '✓' );
				renderIndex( true, { time: res.data.time, status: 'ok' } );
			} else {
				$status.text( ( res && res.data && res.data.message ) ? res.data.message : HGSDCockpit.i18n.error );
			}
		} );
	} );

	/* ------------------------------------------------------------ cluster graph */

	var SVG_NS = 'http://www.w3.org/2000/svg';

	function svgEl( tag, attrs ) {
		var el = document.createElementNS( SVG_NS, tag );
		$.each( attrs || {}, function ( key, value ) {
			el.setAttribute( key, value );
		} );
		return el;
	}

	function renderGraph( data ) {
		var $wrap = $( '.hgsd-graph-wrap' );
		var svg = $( '.hgsd-graph-svg' )[0];
		if ( ! svg ) {
			return;
		}
		while ( svg.firstChild ) {
			svg.removeChild( svg.firstChild );
		}

		var width = 900, height = 560;
		var cx = width / 2, cy = height / 2;
		var nodes = data.nodes || [];
		var edges = data.edges || [];

		// Arrowhead marker.
		var defs = svgEl( 'defs' );
		var marker = svgEl( 'marker', {
			id: 'hgsd-arrow', viewBox: '0 0 10 10', refX: 22, refY: 5,
			markerWidth: 7, markerHeight: 7, orient: 'auto-start-reverse'
		} );
		marker.appendChild( svgEl( 'path', { d: 'M 0 0 L 10 5 L 0 10 z', 'class': 'hgsd-arrowhead' } ) );
		defs.appendChild( marker );
		svg.appendChild( defs );

		// Radial layout: center in the middle, the rest on a circle.
		var positions = {};
		var ring = [];
		$.each( nodes, function ( i, n ) {
			if ( n.center ) {
				positions[ n.id ] = { x: cx, y: cy };
			} else {
				ring.push( n );
			}
		} );
		var radius = Math.min( cx, cy ) - 70;
		$.each( ring, function ( i, n ) {
			var angle = ( i / ring.length ) * 2 * Math.PI - Math.PI / 2;
			positions[ n.id ] = {
				x: cx + radius * Math.cos( angle ),
				y: cy + radius * Math.sin( angle )
			};
		} );

		// Edges first (under the nodes).
		$.each( edges, function ( i, e ) {
			var a = positions[ e.source ], b = positions[ e.target ];
			if ( ! a || ! b ) {
				return;
			}
			svg.appendChild( svgEl( 'line', {
				x1: a.x, y1: a.y, x2: b.x, y2: b.y,
				'class': 'hgsd-edge hgsd-edge-' + e.type,
				'marker-end': 'url(#hgsd-arrow)'
			} ) );
		} );

		// Nodes.
		$.each( nodes, function ( i, n ) {
			var p = positions[ n.id ];
			var g = svgEl( 'g', { 'class': 'hgsd-node' + ( n.center ? ' is-center' : '' ) + ( n.cornerstone ? ' is-cornerstone' : '' ) + ( n.orphan ? ' is-orphan' : '' ), 'data-post': n.id } );
			g.appendChild( svgEl( 'circle', { cx: p.x, cy: p.y, r: n.center ? 22 : 13 } ) );
			var label = svgEl( 'text', { x: p.x, y: p.y + ( n.center ? 40 : 28 ) } );
			label.textContent = n.title.length > 22 ? n.title.substring( 0, 21 ) + '…' : n.title;
			g.appendChild( label );
			svg.appendChild( g );
		} );

		$wrap.removeAttr( 'hidden' );
	}

	$( document ).on( 'change', '.hgsd-graph-select', function () {
		var id = parseInt( $( this ).val(), 10 );
		if ( ! id ) {
			$( '.hgsd-graph-wrap' ).attr( 'hidden', true );
			return;
		}
		$.getJSON( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_graph',
			nonce: HGSDCockpit.nonce,
			post_id: id
		} ).done( function ( res ) {
			if ( res && res.success ) {
				renderGraph( res.data );
			}
		} );
	} );

	// Clicking a node opens the same side panel as a table row.
	$( document ).on( 'click', '.hgsd-node', function () {
		openPanelById( parseInt( $( this ).attr( 'data-post' ), 10 ), null );
	} );

	/* ------------------------------------------------------------------- tips */

	function tipRequest( $tip, op, done ) {
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_tip',
			nonce: HGSDCockpit.nonce,
			key: $tip.attr( 'data-key' ),
			op: op
		} ).done( done );
	}

	$( document ).on( 'click', '.hgsd-tip-open', function () {
		var post = parseInt( $( this ).attr( 'data-post' ), 10 );
		if ( post ) { // Aggregated tips render as plain links instead.
			openPanelById( post, null );
		}
	} );

	$( document ).on( 'click', '.hgsd-tips-rescan', function () {
		var $btn = $( this );
		$btn.prop( 'disabled', true );
		$( '.hgsd-tips-rescan-status' ).text( HGSDCockpit.i18n.rescanning );
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_rescan',
			nonce: HGSDCockpit.nonce
		} ).done( function () {
			window.location.reload();
		} ).fail( function () {
			$btn.prop( 'disabled', false );
			$( '.hgsd-tips-rescan-status' ).text( HGSDCockpit.i18n.error );
		} );
	} );

	$( document ).on( 'click', '.hgsd-tips-settings-save', function () {
		var types = [];
		$( '.hgsd-tips-skip:checked' ).each( function () {
			types.push( $( this ).val() );
		} );
		$.post( HGSDCockpit.ajaxUrl, {
			action: 'hgsd_cockpit_tip_settings',
			nonce: HGSDCockpit.nonce,
			types: types
		} ).always( function () {
			window.location.reload();
		} );
	} );

	$( document ).on( 'click', '.hgsd-tip-dismiss', function () {
		var $tip = $( this ).closest( '.hgsd-tip' );
		tipRequest( $tip, 'dismiss', function () {
			$tip.slideUp( 150, function () {
				$tip.remove();
				var $count = $( '.hgsd-tips-count' );
				var n = Math.max( 0, parseInt( $count.text(), 10 ) - 1 );
				$count.text( n ).toggleClass( 'is-zero', 0 === n );
			} );
		} );
	} );

	$( document ).on( 'click', '.hgsd-tip-restore', function () {
		var $tip = $( this ).closest( '.hgsd-tip' );
		tipRequest( $tip, 'restore', function () {
			window.location.reload();
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
