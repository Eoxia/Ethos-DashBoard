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

	});
};


window.eoxiaJS.ethosDashboard.RIG.copyToClipboard = function(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";

    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
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
}