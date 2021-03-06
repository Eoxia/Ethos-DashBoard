/**
 * Initialise l'objet "accident" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 0.1.0
 */
window.eoxiaJS.ethosDashboard.RIG = {};

window.eoxiaJS.ethosDashboard.RIG.init = function() {
	jQuery( document ).on( 'click', '.fa-copy', function() {
		jQuery( this ).closest( '.acf-input' ).find( 'input[type="text"]' ).removeAttr( 'disabled' );
		window.eoxiaJS.ethosDashboard.RIG.copyToClipboard( jQuery( this ).closest( '.acf-input' ).find( 'input[type="text"]' )[0] );
		jQuery( this ).closest( '.acf-input' ).find( 'input[type="text"]' ).attr( 'disabled', 'disabled' );
	} );

	if ( jQuery( "body.post-type-rig .wp-heading-inline" ).text() === 'Rigs' ) {
		jQuery( "body.post-type-rig .wrap .page-title-action").after('<a class="page-title-action wpeo-modal-event" data-action="load_modal_regenerate" data-class="modal-force-display modal-regenerate-txt" data-title="Regenerate TXT in process">Regenerate TXT</a>');
	}

	jQuery( document ).on( 'modal-opened', '.modal-regenerate-txt', window.eoxiaJS.ethosDashboard.RIG.modalOpened );
};


window.eoxiaJS.ethosDashboard.RIG.copyToClipboard = function(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";

    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);

    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }

    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
};

window.eoxiaJS.ethosDashboard.RIG.modalOpened = function( triggeredElement ) {
	var data = {
		action: 'regenerate_txt',
		_wpnonce: jQuery( '.modal-regenerate-txt li.active:first' ).data( 'nonce' ),
		ids: jQuery( '.modal-regenerate-txt li.active:first' ).data( 'ids' ),
		number_error: jQuery( '.modal-regenerate-txt .number-error' ).val(),
		number_success: jQuery( '.modal-regenerate-txt .number-success' ).val(),
		total: jQuery( '.modal-regenerate-txt .total' ).val()
	};

	window.eoxiaJS.request.send( triggeredElement, data, function( element, response ) {
		jQuery( '.modal-regenerate-txt li.active:first' ).find( 'img' ).remove();
		jQuery( '.modal-regenerate-txt li.active:first' ).append( '<span class="dashicons dashicons-yes"></span>' );
		jQuery( '.modal-regenerate-txt li.active:first' ).removeClass( 'active' );

		jQuery( '.modal-regenerate-txt .number-error' ).val( response.data.number_error );
		jQuery( '.modal-regenerate-txt .number-success' ).val( response.data.number_success );

		for ( var key in response.data.error_message ) {
			jQuery( '.modal-regenerate-txt li span[data-id="' + key + '"]' ).css( 'color', 'red' );
			jQuery( '.modal-regenerate-txt .log' ).append( '<li>' + response.data.error_message[key] + '</li>' );
		}

		if ( jQuery( '.modal-regenerate-txt li.active' ).length > 0 ) {
			window.eoxiaJS.ethosDashboard.RIG.modalOpened( triggeredElement );
		} else {
			jQuery( '.modal-regenerate-txt' ).removeClass( 'modal-force-display' );

			jQuery( '.modal-regenerate-txt .log' ).append( '<li>' + response.data.final_message + '</li>' );
		}
	} );
};
