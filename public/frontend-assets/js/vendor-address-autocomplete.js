/* ============================================================================
   VENDOR APPLY — Google Places address autocomplete + geo-coordinates.
   Picking an address fills the address field, the City field, and the hidden
   latitude/longitude inputs (saved with the vendor's store).
   Loaded with the Google Maps JS API (callback=initVendorAutocomplete).
   ========================================================================== */
(function () {
    'use strict';

    window.initVendorAutocomplete = function () {
        var input = document.getElementById('va-address');
        if (!input || !(window.google && google.maps && google.maps.places)) return;

        var latEl  = document.getElementById('va-lat');
        var lngEl  = document.getElementById('va-lng');
        var cityEl = document.querySelector('[name="city"]');
        var hint   = document.getElementById('va-geo-hint');

        var ac = new google.maps.places.Autocomplete(input, {
            fields: ['address_components', 'formatted_address', 'geometry'],
            types: ['geocode']
        });

        ac.addListener('place_changed', function () {
            var place = ac.getPlace();
            if (!place) return;

            // Fill the address with the human-readable address.
            if (place.formatted_address) input.value = place.formatted_address;

            // Fill City from the locality component.
            if (cityEl && place.address_components) {
                for (var i = 0; i < place.address_components.length; i++) {
                    var c = place.address_components[i];
                    if (c.types.indexOf('locality') !== -1 || c.types.indexOf('postal_town') !== -1) {
                        cityEl.value = c.long_name;
                        break;
                    }
                }
            }

            // Capture latitude / longitude.
            if (place.geometry && place.geometry.location) {
                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                if (latEl) latEl.value = lat.toFixed(7);
                if (lngEl) lngEl.value = lng.toFixed(7);
                if (hint) hint.innerHTML = '<i class="fas fa-check-circle" style="color:#2e7d32;"></i> Location pinned ('
                    + lat.toFixed(5) + ', ' + lng.toFixed(5) + ')';
            }
        });

        // Don't submit the form on Enter while a suggestion is open.
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && document.querySelector('.pac-container')) {
                var open = Array.prototype.some.call(document.querySelectorAll('.pac-container'), function (el) { return el.offsetParent !== null; });
                if (open) e.preventDefault();
            }
        });
    };
})();
