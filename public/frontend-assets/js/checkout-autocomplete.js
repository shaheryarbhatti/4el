/* ============================================================================
   CHECKOUT — Google Places Address Autocomplete
   Attaches Google Places autocomplete to the "Street Address" field. Picking a
   suggestion auto-fills city, state/province, postal code and country.

   Loaded on the checkout page together with the Google Maps JS API:
     <script src="...checkout-autocomplete.js"></script>
     <script async src="https://maps.googleapis.com/maps/api/js?key=KEY
              &libraries=places&callback=initCheckoutAutocomplete&loading=async"></script>
   The API calls initCheckoutAutocomplete() once it has loaded.
   ========================================================================== */
(function () {
    'use strict';

    function byName(name) { return document.querySelector('[name="' + name + '"]'); }

    function setVal(name, val) {
        var el = byName(name);
        if (el && val) el.value = val;
    }

    // Match a Google country name to the <select name="country"> options.
    function setCountry(country) {
        var sel = byName('country');
        if (!sel || !country) return;
        var lc = country.toLowerCase();
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value.toLowerCase() === lc) { sel.selectedIndex = i; return; }
        }
        // Fallback to "Other" if the country isn't in the list.
        for (var j = 0; j < sel.options.length; j++) {
            if (sel.options[j].value === 'Other') { sel.selectedIndex = j; return; }
        }
    }

    window.initCheckoutAutocomplete = function () {
        var input = byName('address_line');
        if (!input || !(window.google && google.maps && google.maps.places)) return;

        // Reduce the browser's own autofill dropdown competing with Google's.
        input.setAttribute('autocomplete', 'off');

        var ac = new google.maps.places.Autocomplete(input, {
            fields: ['address_components', 'formatted_address'],
            types: ['address']
        });

        ac.addListener('place_changed', function () {
            var place = ac.getPlace();
            if (!place || !place.address_components) return;

            // Helper to pull a component by type.
            var get = function (type, useShort) {
                for (var i = 0; i < place.address_components.length; i++) {
                    var c = place.address_components[i];
                    if (c.types.indexOf(type) !== -1) return useShort ? c.short_name : c.long_name;
                }
                return '';
            };

            var streetNumber = get('street_number');
            var route        = get('route');
            var sublocality  = get('sublocality') || get('sublocality_level_1') || get('neighborhood');
            var city         = get('locality') || get('postal_town') || get('administrative_area_level_2');
            var state        = get('administrative_area_level_1');
            var postal       = get('postal_code');
            var country      = get('country');

            // Build a clean street line.
            var street = [streetNumber, route].filter(Boolean).join(' ');
            if (sublocality) street = street ? street + ', ' + sublocality : sublocality;
            if (!street) street = place.formatted_address || input.value;

            input.value = street;
            setVal('city', city);
            setVal('state', state);
            setVal('postal_code', postal);
            setCountry(country);
        });

        // Don't submit the form when pressing Enter to pick a suggestion.
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && document.querySelector('.pac-container')) {
                var visible = Array.prototype.some.call(
                    document.querySelectorAll('.pac-container'),
                    function (el) { return el.offsetParent !== null; }
                );
                if (visible) e.preventDefault();
            }
        });
    };
})();
