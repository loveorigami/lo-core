/**
 * Created by админ on 05.12.2016.
 */

$(function () {
    "use strict";
    /*
     * Add collapse and remove events to boxes And save cookies
     */
    $("[data-widget='collapse']").each(function () {
        var box = $(this).parents(".box").first();
        //console.log('found '+box.attr('id') + 'cookstate = ' + $.cookie(box.attr('id')+'_state') );
        if (localStorage.getItem(box.attr('id') + '_state') == "hide") {
            if (!box.hasClass("collapsed-box")) {
                box.addClass("collapsed-box");
                box.find('i.collapsed').removeClass('fa-minus').addClass("fa-plus");
                box.slideDown();
            }
        }
    });

    $("[data-widget='collapse']").on('click', function () {
        var box = $(this).parents(".box").first();
        //console.log('clicked '+box.attr('id') + 'cookstate = ' + $.cookie(box.attr('id')+'_state') );
        if (!box.hasClass("collapsed-box")) {
            localStorage.setItem(box.attr('id') + '_state', "hide");
        } else {
            localStorage.setItem(box.attr('id') + '_state', "show");
        }
    });
});