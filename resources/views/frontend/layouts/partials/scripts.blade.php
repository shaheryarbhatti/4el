{{-- Storefront JS bundle (Porto demo36). External Cloudflare beacons removed. --}}
{{-- Expose cart-add URL for global add-to-cart handler --}}
<script>
window.EB_CART_BASE = '{{ url('/cart/add') }}';
window.EB_CSRF     = '{{ csrf_token() }}';
window.EB_CART_URL = '{{ route('cart.index') }}';
</script>
<script src="{{ asset('frontend-assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('frontend-assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend-assets/js/optional/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend-assets/js/plugins.min.js') }}"></script>
<script src="{{ asset('frontend-assets/js/jquery.appear.min.js') }}"></script>
<script src="{{ asset('frontend-assets/js/jquery.plugin.min.js') }}"></script>
<script src="{{ asset('frontend-assets/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('frontend-assets/js/main.min.js') }}"></script>
@stack('scripts')
<script>
// ---- Shop-by-category megamenu with hover grace period ----
(function () {
    var btn  = document.querySelector('.eb-shopby');
    var mega = document.querySelector('.eb-shopby__mega');
    if (!btn || !mega) return;
    var timer;
    function openMega()  { clearTimeout(timer); mega.classList.add('is-open'); }
    function closeMega() { timer = setTimeout(function () { mega.classList.remove('is-open'); }, 220); }
    btn .addEventListener('mouseenter', openMega);
    btn .addEventListener('mouseleave', closeMega);
    mega.addEventListener('mouseenter', openMega);
    mega.addEventListener('mouseleave', closeMega);
})();

/* ══════════════════════════════════════════════════════════════════
   Porto miniPopup — fix "View Cart" / "Checkout" URLs to our routes,
   then expose ebShowMiniPopup() for AJAX calls (quick-view etc.)
══════════════════════════════════════════════════════════════════ */
$(function () {
    // Patch URLs the moment Porto inserts a minipopup-box into the DOM
    var area = document.querySelector('.minipopup-area');
    if (area) {
        new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                m.addedNodes.forEach(function (node) {
                    if (node.nodeType !== 1) return;
                    var vc = node.querySelector('.viewcart, .btn.viewcart');
                    var co = node.querySelector('.checkout, .btn.checkout');
                    if (vc) vc.href = window.EB_CART_URL;
                    if (co) co.href = window.EB_CART_URL;
                });
            });
        }).observe(area, { childList: true });
    }
});

// Manually trigger Porto's minipopup from outside the IIFE
// Replicates Porto's template exactly so it looks identical
window.ebShowMiniPopup = function (name, nameLink, imgSrc, imgLink) {
    var area = document.querySelector('.minipopup-area');
    if (!area) return;

    var action = '<a href="' + window.EB_CART_URL + '" class="btn viewcart">View Cart</a>'
               + '<a href="' + window.EB_CART_URL + '" class="btn btn-dark checkout">Checkout</a>';

    var box = document.createElement('div');
    box.className = 'minipopup-box';
    box.innerHTML =
        '<div class="product">' +
            '<figure class="product-media">' +
                '<a href="' + (imgLink || '#') + '">' +
                    '<img src="' + (imgSrc || '') + '" alt="product" width="60" height="60">' +
                '</a>' +
            '</figure>' +
            '<div class="product-detail">' +
                '<a href="' + (nameLink || '#') + '" class="product-name">' + (name || 'Item') + '</a>' +
                '<p>has been added to your cart.</p>' +
            '</div>' +
        '</div>' +
        '<div class="product-action">' + action + '</div>' +
        '<button class="mfp-close"></button>';

    area.appendChild(box);

    // Activate using Porto's existing animation class
    var img = box.querySelector('img');
    function activate() {
        box.classList.add('active');
        // Close button
        box.querySelector('.mfp-close').addEventListener('click', function () {
            box.classList.remove('active');
            setTimeout(function () { box.remove(); }, 300);
        });
        // Auto-dismiss after 4 s
        setTimeout(function () {
            box.classList.remove('active');
            setTimeout(function () { box.remove(); }, 300);
        }, 4000);
    }
    if (img.src) { img.onload = activate; img.onerror = activate; activate(); }
    else { activate(); }
};

/* ── Global Add-to-Cart: intercept .btn-add-cart clicks ─────────────────
   Each product card must have data-product-id on the button OR a closest
   ancestor with data-product-id. We also accept data-product-slug.
   ─────────────────────────────────────────────────────────────────────── */
$(document).on('click', '.btn-add-cart', function(e) {
    var $btn  = $(this);
    var pid   = $btn.data('product-id') || $btn.data('product-slug');

    if (!pid) return;

    // Porto's productsCartAction shows its own miniPopup for .product-type-simple
    // cards immediately from the DOM (demo behaviour). We still need to send our
    // own AJAX to actually add to the real backend cart — but we skip ebShowMiniPopup
    // to avoid a second/duplicate toast.
    var isPortoCard = $btn.hasClass('product-type-simple') && $btn.closest('.product-default').length;

    if (!isPortoCard) e.preventDefault();

    var url = window.EB_CART_BASE + '/' + pid;

    // Find the product card scoped to this specific button only
    var $card = $btn.closest(
        '.product-default, .eb-deal-hero, [class*="eb-deal"], [class*="eb-gp"], ' +
        '[class*="eb-feat"], article, .owl-item > div, .col-md-4, .col-lg-3, .col-lg-4'
    );
    if (!$card.length) $card = $btn.parent();

    var pName = isPortoCard ? '' : ($card.find('h2 a, h3 a, .product-title a, .product-title').first().text().trim() || '');
    var pImg  = isPortoCard ? '' : ($card.find('figure img, .product-image img, img').first().attr('src') || '');

    $btn.addClass('loading').prop('disabled', true);

    $.ajax({
        url   : url,
        method: 'POST',
        data  : { _token: window.EB_CSRF, quantity: 1 },
        success: function(res) {
            if (res.success) {
                $('.eb-cart-badge, .eb-top__badge').text(res.count);
                $btn.addClass('added');
                setTimeout(function() { $btn.removeClass('loading added').prop('disabled', false); }, 1200);
                // Porto already showed its own popup for .product-default cards
                if (!isPortoCard) {
                    ebShowMiniPopup(pName, '', pImg, '');
                }
            }
        },
        error: function() {
            $btn.removeClass('loading').prop('disabled', false);
            if (!isPortoCard) window.location = window.EB_CART_URL;
        }
    });
});

$(function () {
    // Custom tab switcher for the login modal
    function lmShowTab(name) {
        $('.lm-tab').removeClass('active');
        $('.lm-tab[data-lm-tab="' + name + '"]').addClass('active');
        $('.lm-pane').removeClass('active');
        $('#lm-pane-' + name).addClass('active');
    }

    // Header tab buttons
    $(document).on('click', '.lm-tab[data-lm-tab]', function () {
        lmShowTab($(this).data('lm-tab'));
    });

    // "Create one free" / "Sign in" inline links
    $(document).on('click', '.lm-tab-switch', function (e) {
        e.preventDefault();
        lmShowTab($(this).data('lm-target'));
    });

    // "Register" link in the header — open modal on the register tab
    $('[data-tab="register"]').on('click', function (e) {
        e.preventDefault();
        $('#loginModal').modal('show');
        setTimeout(function () { lmShowTab('register'); }, 180);
    });

    // Re-open modal on the correct tab after server validation error
    @guest
        @if ($errors->any())
            @if (old('_form') === 'register')
                $('#loginModal').modal('show');
                lmShowTab('register');
            @elseif (old('_form') === 'login')
                $('#loginModal').modal('show');
            @endif
        @endif
    @endguest
});
</script>

<script src="{{ asset('frontend-assets/js/wishlist.js') }}"></script>
<script src="{{ asset('frontend-assets/js/auth-modal.js') }}"></script>
<script src="{{ asset('frontend-assets/js/confirm-modal.js') }}"></script>
