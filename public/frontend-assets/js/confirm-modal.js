/* ============================================================================
   CONFIRM MODAL — replaces the browser's confirm() with a themed dialog.

   Usage: put data-confirm attributes on a <form> (or a link/button):
     <form ... class="js-confirm"
           data-confirm-title="Clear your cart?"
           data-confirm-message="This removes all items. Can't be undone."
           data-confirm-text="Yes, clear"
           data-confirm-icon="fa-trash-alt"
           data-confirm-variant="danger">   (danger | primary)

   For a form: submission is intercepted, the modal shows, and on confirm the
   form is submitted. For a link with data-confirm: on confirm we navigate.
   ========================================================================== */
(function () {
    'use strict';

    var pending = null; // the element (form/link) waiting for confirmation
    var overlay, modal, iconEl, titleEl, msgEl, confirmBtn, cancelBtn, closeBtn;

    function build() {
        overlay = document.createElement('div');
        overlay.className = 'cfm-overlay';
        overlay.innerHTML =
            '<div class="cfm-modal" role="dialog" aria-modal="true">' +
                '<button type="button" class="cfm-close" aria-label="Close"><i class="fas fa-times"></i></button>' +
                '<div class="cfm-icon"><i class="fas fa-trash-alt"></i></div>' +
                '<h3 class="cfm-title">Are you sure?</h3>' +
                '<p class="cfm-msg"></p>' +
                '<div class="cfm-actions">' +
                    '<button type="button" class="cfm-btn cfm-btn--cancel">Cancel</button>' +
                    '<button type="button" class="cfm-btn cfm-btn--confirm">Confirm</button>' +
                '</div>' +
            '</div>';
        document.body.appendChild(overlay);

        modal      = overlay.querySelector('.cfm-modal');
        iconEl     = overlay.querySelector('.cfm-icon i');
        titleEl    = overlay.querySelector('.cfm-title');
        msgEl      = overlay.querySelector('.cfm-msg');
        confirmBtn = overlay.querySelector('.cfm-btn--confirm');
        cancelBtn  = overlay.querySelector('.cfm-btn--cancel');
        closeBtn   = overlay.querySelector('.cfm-close');

        cancelBtn.addEventListener('click', close);
        closeBtn.addEventListener('click', close);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

        confirmBtn.addEventListener('click', function () {
            var el = pending;
            close();
            if (!el) return;
            el.dataset.confirmed = '1';
            if (el.tagName === 'FORM') {
                if (el.requestSubmit) el.requestSubmit(); else el.submit();
            } else if (el.href) {
                window.location.href = el.href;
            }
        });
    }

    function open(el) {
        pending = el;
        var d = el.dataset;
        titleEl.textContent   = d.confirmTitle   || 'Are you sure?';
        msgEl.textContent     = d.confirmMessage || 'Please confirm this action.';
        confirmBtn.textContent = d.confirmText   || 'Confirm';
        iconEl.className = 'fas ' + (d.confirmIcon || 'fa-exclamation-triangle');
        modal.classList.toggle('is-primary', d.confirmVariant === 'primary');
        overlay.classList.add('is-open');
    }

    function close() {
        overlay.classList.remove('is-open');
        pending = null;
    }

    document.addEventListener('DOMContentLoaded', function () {
        build();

        // Intercept form submits that carry data-confirm.
        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (form.matches && form.matches('.js-confirm') && !form.dataset.confirmed) {
                e.preventDefault();
                open(form);
            }
        });

        // Intercept links/buttons with data-confirm.
        document.addEventListener('click', function (e) {
            var el = e.target.closest('a.js-confirm, button.js-confirm');
            if (el && el.tagName !== 'BUTTON' && !el.dataset.confirmed) {
                e.preventDefault();
                open(el);
            }
        });
    });
})();
