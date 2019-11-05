(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(function () {
        $('.mi-s2').select2();
        //
        $( "#FormUmkreissuche" ).submit(function( event ) {
            event.preventDefault();
            $.ajax({
                url: umkreissuche.ajaxurl,
                type: 'post',
                data: {
                    action: 'ajax_umkreissuche'
                },
                success: function( result ) {
                    alert( result );
                }
            })
        });
        //
        //
        new_map($('#gmapresults'));
    });

    function new_map(element) {
        // var
        let divmarkers = $('.mnc-results').find('[data-lat]');
        let map = new google.maps.Map(element[0], {
            zoom: 9,
            center: new google.maps.LatLng(54.1792679,9.3400332),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        // add a markers reference
        map.markers = [];
        // // add markers
        divmarkers.each(function () {
            add_marker($(this), map);
        });
        add_my_location(map);
        // center map
        center_map(map);
        // return
        return map;
    }

    /**
     * use customized marker to display own location on the map
     * @param map
     */
    function add_my_location(map) {
        let mypos = $('#MyPosition');
        let iconURL = '/wp-content/themes/demenznav-theme/assets/my_location.png';
        let image = {
            url: '/wp-content/themes/demenznav-theme/assets/my_location.png',
            scaledSize: new google.maps.Size(32, 32), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0)
        };
        let latlng = new google.maps.LatLng(mypos.attr('data-my-lat'), mypos.attr('data-my-lng'));
        let marker = new google.maps.Marker({
            position: latlng,
            map: map,
            icon:image
            // label: mypos.attr('data-label'),
        });
        // add to array
        map.markers.push(marker);
        if (mypos.html()) {
            // create info window
            let infowindow = new google.maps.InfoWindow({
                content: mypos.html()
            });

            // show info window when marker is clicked
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });
        }
    }

    function add_marker(divmarker, map) {

        // var
        let latlng = new google.maps.LatLng(divmarker.attr('data-lat'), divmarker.attr('data-lng'));
        // create marker
        let marker = new google.maps.Marker({
            position: latlng,
            map: map,
            label: divmarker.attr('data-label'),
        });
        // add to array
        map.markers.push(marker);

        // if marker contains HTML, add it to an infoWindow
        if (divmarker.html()) {
            // create info window
            let infowindow = new google.maps.InfoWindow({
                content: divmarker.html()
            });

            // show info window when marker is clicked
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });
        }

    }

    function center_map(map) {

        // vars
        let bounds = new google.maps.LatLngBounds();

        // loop through all markers and create bounds
        $.each(map.markers, function (i, marker) {
            let latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
            bounds.extend(latlng);
        });
        // only 1 marker?
        if (map.markers.length === 1) {
            // set center of map
            map.setCenter(bounds.getCenter());
            map.setZoom(16);
        }
        else {
            map.fitBounds(bounds);
        }

    }

})(jQuery);