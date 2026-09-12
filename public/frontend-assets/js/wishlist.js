/* ============================================================================
   WISHLIST JS  — AJAX add/remove + live header badge update.
   Loaded from resources/views/frontend/layouts/partials/scripts.blade.php.

   Wire any element like:
     <button class="js-wish-toggle" data-id="123" data-url="/wishlist/toggle/123">
   and it will toggle the wishlist without a page reload.
   ========================================================================== */
(function () {
    'use strict';

    function csrf() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : '';
    }

    // Update the little red count badge next to the header heart.
    function setBadge(count) {
        var badge = document.getElementById('eb-wish-count');
        if (!badge) return;
        badge.textContent = count;
        badge.classList.toggle('is-zero', !count);
    }

    // ---- Toggle hearts on product cards ----
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.js-wish-toggle');
        if (!btn) return;
        e.preventDefault();

        var url = btn.dataset.url;
        if (!url) return;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) return;
            btn.classList.toggle('is-active', data.added);
            setBadge(data.count);
        })
        .catch(function () {});
    });

    // ---- Remove from the wishlist page (AJAX) ----
    document.addEventListener('submit', function (e) {
        var form = e.target.closest('.js-wish-remove');
        if (!form) return;
        e.preventDefault();

        fetch(form.action, {
            method: 'POST', // Laravel reads _method=DELETE
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) return;
            var card = document.getElementById('wl-card-' + form.dataset.id);
            if (card) card.remove();
            setBadge(data.count);
            if (data.count === 0) window.location.reload(); // show empty state
        })
        .catch(function () {});
    });
})();
