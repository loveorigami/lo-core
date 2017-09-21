/*
 *
 * Copyright (c) 2012 Aderemi Dadepo
 * @license: Dual licensed under the MIT and GPL licenses.
 * @version: 0.0.1
 * http://geekabyte.blogspot.com
 */
(function ($) {

    $.fn.charlength = function (options) {

        // Create some defaults, extending them with any options that were provided
        var settings = $.extend({
            'limit': 50,
            'classSuccess': 'label label-success',
            'classWarning': 'label label-warning',
            'classDanger': 'label label-danger'
        }, options);

        function getCssClass(len) {
            var cssClass = settings.classSuccess;

            if ((settings.limit - 10) <= len && len <= settings.limit) {
                cssClass = settings.classWarning;
            }

            if (len > settings.limit) {
                cssClass = settings.classDanger;
            }

            return cssClass;
        }

        //get content of textarea;
        return this.each(function () {
                var len = $(this).val().length;
                var cssClass = getCssClass(len);

                if (this.tagName !== 'TEXTAREA' && $(this).attr('type') !== 'text') {
                    //because the plugin is meant for TEXTAREA and INPUT only
                    return true;
                }

                $(this).after("<span class='" + cssClass + " bs-maxlength'>" + len + " / " + settings.limit + "</span>");

                //on key-up check if the content is not over the limit
                $(this).keyup(function () {
                    var len = $(this).val().length;
                    var cssClass = getCssClass(len);
                    $(this).next().replaceWith("<span class='" + cssClass + " bs-maxlength'>" + len + " / " + settings.limit + "</span>");
                });
            }
        );
    };
})(jQuery);