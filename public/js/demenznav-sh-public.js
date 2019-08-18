;(function (a) {
    a.fn.rwdImageMaps = function () {
        var c = this;
        var b = function () {
            c.each(function () {
                if (typeof (a(this).attr("usemap")) == "undefined") {
                    return
                }
                var e = this, d = a(e);
                a("<img />").on('load', function () {
                    var g = "width", m = "height", n = d.attr(g), j = d.attr(m);
                    if (!n || !j) {
                        var o = new Image();
                        o.src = d.attr("src");
                        if (!n) {
                            n = o.width
                        }
                        if (!j) {
                            j = o.height
                        }
                    }
                    var f = d.width() / 100, k = d.height() / 100, i = d.attr("usemap").replace("#", ""), l = "coords";
                    a('map[name="' + i + '"]').find("area").each(function () {
                        var r = a(this);
                        if (!r.data(l)) {
                            r.data(l, r.attr(l))
                        }
                        var q = r.data(l).split(","), p = new Array(q.length);
                        for (var h = 0; h < p.length; ++h) {
                            if (h % 2 === 0) {
                                p[h] = parseInt(((q[h] / n) * 100) * f)
                            } else {
                                p[h] = parseInt(((q[h] / j) * 100) * k)
                            }
                        }
                        r.attr(l, p.toString())
                    })
                }).attr("src", d.attr("src"))
            })
        };
        a(window).resize(b).trigger("resize");
        return this
    }
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
    $(function () {
        $('.mi-s2').select2();
    });

})(jQuery);

function mapHighlight(key) {
    document.getElementById('shmap').classList.add(key);
    document.getElementById('legende_' + key).classList.add('highlight');
}

function mapReset(key) {
    document.getElementById('shmap').classList.remove(key);
    document.getElementById('legende_' + key).classList.remove('highlight');
}