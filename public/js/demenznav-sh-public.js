(function ($) {

    /**
     * Copyright 2012, Digital Fusion
     * Licensed under the MIT license.
     * http://teamdf.com/jquery-plugins/license/
     *
     * @author Sam Sehnert
     * @desc A small plugin that checks whether elements are within
     *       the user visible viewport of a web browser.
     *       can accounts for vertical position, horizontal, or both
     */
    var $w = $(window);
    $.fn.in_the_viewport = function (partial, hidden, direction, container) {

        if (this.length < 1)
            return;

        // Set direction default to 'both'.
        direction = direction || 'both';

        var $t = this.length > 1 ? this.eq(0) : this,
            isContained = typeof container !== 'undefined' && container !== null,
            $c = isContained ? $(container) : $w,
            wPosition = isContained ? $c.position() : 0,
            t = $t.get(0),
            vpWidth = $c.outerWidth(),
            vpHeight = $c.outerHeight(),
            clientSize = hidden === true ? t.offsetWidth * t.offsetHeight : true;

        if (typeof t.getBoundingClientRect === 'function') {

            // Use this native browser method, if available.
            var rec = t.getBoundingClientRect(),
                tViz = isContained ?
                    rec.top - wPosition.top >= 0 && rec.top < vpHeight + wPosition.top :
                    rec.top >= 0 && rec.top < vpHeight,
                bViz = isContained ?
                    rec.bottom - wPosition.top > 0 && rec.bottom <= vpHeight + wPosition.top :
                    rec.bottom > 0 && rec.bottom <= vpHeight,
                lViz = isContained ?
                    rec.left - wPosition.left >= 0 && rec.left < vpWidth + wPosition.left :
                    rec.left >= 0 && rec.left < vpWidth,
                rViz = isContained ?
                    rec.right - wPosition.left > 0 && rec.right < vpWidth + wPosition.left :
                    rec.right > 0 && rec.right <= vpWidth,
                vVisible = partial ? tViz || bViz : tViz && bViz,
                hVisible = partial ? lViz || rViz : lViz && rViz,
                vVisible = (rec.top < 0 && rec.bottom > vpHeight) ? true : vVisible,
                hVisible = (rec.left < 0 && rec.right > vpWidth) ? true : hVisible;

            if (direction === 'both')
                return clientSize && vVisible && hVisible;
            else if (direction === 'vertical')
                return clientSize && vVisible;
            else if (direction === 'horizontal')
                return clientSize && hVisible;
        } else {

            var viewTop = isContained ? 0 : wPosition,
                viewBottom = viewTop + vpHeight,
                viewLeft = $c.scrollLeft(),
                viewRight = viewLeft + vpWidth,
                position = $t.position(),
                _top = position.top,
                _bottom = _top + $t.height(),
                _left = position.left,
                _right = _left + $t.width(),
                compareTop = partial === true ? _bottom : _top,
                compareBottom = partial === true ? _top : _bottom,
                compareLeft = partial === true ? _right : _left,
                compareRight = partial === true ? _left : _right;

            if (direction === 'both')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop)) && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
            else if (direction === 'vertical')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
            else if (direction === 'horizontal')
                return !!clientSize && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
        }
    };

})(jQuery);
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
    jQuery.fn.extend({
        in_viewport: function (opt) {
            let el = this;
            let top_of_element = el.offset().top;
            let bottom_of_element = el.offset().top + el.outerHeight();
            let bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
            let top_of_screen = $(window).scrollTop();
            return (bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element);
        }
    });
    $(function () {

        $('.mi-s2').select2();
        //
        $("#FormUmkreissuche").submit(function (event) {
            event.preventDefault();
            $.ajax({
                url: umkreissuche.ajaxurl,
                type: 'post',
                data: {
                    action: 'ajax_umkreissuche'
                },
                success: function (result) {
                    alert(result);
                }
            })
        });
        //
        let mapcontainer = $('#gmapresults');
        if (mapcontainer.length) {
            new_map(mapcontainer);
        }
        $('#MncMapContainer').height(function (index, height) {
            return window.innerHeight - $(this).offset().top;
        });
        // let handler = function () {
        //     let map = $('#MncMapContainer');
        //     let header = $('#MncHeader');
        //     if (!(map.length && header.length)) {
        //         return;
        //     }
        //     if ($('#PageNavigator').in_the_viewport()) {
        //         // let position = header.position();
        //         map.removeClass('mnc-map-sticky');
        //         map.addClass('mnc-map-scroll');
        //     } else {
        //         map.removeClass('mnc-map-scroll');
        //         // map.addClass('mnc-map-sticky');
        //     }
        // };
        // window.addEventListener('scroll', handler, {passive: true});


    });

    function new_map(element) {
        // var
        let divmarkers = $('.mnc-results').find('[data-lat]');
        let map = new google.maps.Map(element[0], {
            zoom: 9,
            center: new google.maps.LatLng(54.1792679, 9.3400332),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        // add a markers reference
        map.markers = [];
        // // add markers
        let sem = false;
        divmarkers.each(function () {
            sem = true;
            add_marker($(this), map);
        });
        if(sem) {
            add_my_location(map);
            center_map(map);
        }
        // center map
        // return
        return map;
    }

    /**
     * use customized marker to display own location on the map
     * @param map
     */
    function add_my_location(map) {
        let mypos = $('#MyPosition');
        if(!mypos.length) {
            return;
        }
        let iconURL = '/wp-content/themes/demenznav-theme/assets/my_location.png';
        let image = {
            url: '/wp-content/themes/demenznav-theme/assets/my_location.png',
            scaledSize: new google.maps.Size(32, 32), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(0, 0)
        };
        let latlng = new google.maps.LatLng(mypos.attr('data-my-lat'), mypos.attr('data-my-lng'));
        let marker = new google.maps.Marker({
            position: latlng,
            map: map,
            icon: image
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
        } else {
            map.fitBounds(bounds);
        }

    }

})(jQuery);