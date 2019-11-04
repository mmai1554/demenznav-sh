(function ($) {
    'use strict';
    function new_map(element) {
        // var
        let divmarkers = element.find('attr=[data-lat]');
        let map = new google.maps.Map(element, {
            zoom: 10,
            center: new google.maps.LatLng(54.1792679,9.3400332),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        // add a markers reference
        // map.markers = [];
        // // add markers
        // divmarkers.each(function () {
        //     add_marker($(this), map);
        // });
        // // center map
        // center_map(map);
        // return
        return map;
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
            var infowindow = new google.maps.InfoWindow({
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
        var bounds = new google.maps.LatLngBounds();

        // loop through all markers and create bounds
        $.each(map.markers, function (i, marker) {

            var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());

            bounds.extend(latlng);

        });

        // only 1 marker?
        if (map.markers.length === 1) {
            // set center of map
            map.setCenter(bounds.getCenter());
            map.setZoom(16);
        }
        else {
            // fit to bounds
            map.fitBounds(bounds);
        }

    }

    let map = null;

    $(document).ready(function () {
        // alert('I go ab!');

        // $('#gmapresults').each(function () {
        //     // create map
        //     map = new_map($(this));
        // });
    });


})(jQuery);