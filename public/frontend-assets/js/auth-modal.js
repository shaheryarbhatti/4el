/* ============================================================================
   AUTH MODAL — submit loader.
   When the Sign In / Create Account form is submitted, the button (.lm-submit)
   is disabled and shows a spinner until the page reloads (prevents double
   submits + gives feedback). Loaded from the scripts partial.

   Note: the browser only fires "submit" AFTER HTML5 validation passes, so the
   spinner never shows on an invalid form. On a validation error the page
   re-renders and the button resets to its original state automatically.
   ========================================================================== */
(function () {
    'use strict';

    document.addEventListener('submit', function (e) {
        var form = e.target;
        var btn  = form.querySelector('.lm-submit');
        if (!btn || btn.disabled) return;

        // Remember original label, then swap to a spinner and disable.
        btn.dataset.originalHtml = btn.innerHTML;
        btn.classList.add('is-loading');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Please wait…';

        // Disable AFTER the current submit is already in flight.
        setTimeout(function () { btn.disabled = true; }, 0);
    });
})();
