/*
 standard-widget-extensions.js
 Copyright 2013, 2014 Hirokazu Matsui (blogger323)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 */
/*
  hm-swe-accordion-head: a class for headings
  hm-swe-collapsed: a class for headings with collapsed area
  hm-swe-expanded: a class for headings with expanded area
 */
(function ($, window, document, undefined) {

    $(document).ready(function () {
        var cook = null;
        var contentid = "#" + swe.maincol_id;
        var widget = '.' + swe.widget_class;
        var slide_duration = parseInt(swe.slide_duration, 10);

        var CONDITION = {
            content_height: 0, /* including margins, borders and paddings */
            content_top: 0,
            content_margin_top: 0,
            window_height: 0,
            mode: parseInt(swe.scroll_mode, 10), /* 2: switch back */
            header_space: 0, /* will be set in resizefunc() */
            direction: 0,
            prevscrolltop: -1
        };

        function init_sidebar(sidebar, param_id, percent_width, disable_iflt, float_attr_check_mode) {
            sidebar.o =  null; // jQuery object
            sidebar.height = 0; /* include borders and paddings */
            sidebar.fixed = 0;
            sidebar.default_offset = { top: 0, left: 0 };
            sidebar.margin_top = 0;
            sidebar.margin_left = 0;
            sidebar.width = 0;
            sidebar.absolute_adjustment_top = 0;
            sidebar.absolute_adjustment_left = 0;
            sidebar.percent_width = 0;
            sidebar.disable_iflt = 0;
            sidebar.float_attr_check_mode = parseInt(float_attr_check_mode, 10);

            sidebar.mode = 0; // 0:disabled, 1:long, 2:short

            sidebar.previoustop = 0;

            if (param_id) {
                sidebar.id = '#' + param_id;
                if (sidebar.id && $(sidebar.id).length > 0) {
                    sidebar.o = $(sidebar.id);
                    sidebar.parent = sidebar.o.parent();
                    sidebar.margin_top = parseInt(sidebar.o.css('margin-top'), 10);
                    sidebar.percent_width = parseFloat(percent_width);
                    sidebar.disable_iflt = parseInt(disable_iflt, 10);

                }
            }
        }

        var SIDEBAR1 = {};
        var SIDEBAR2 = {};
        var DISABLED_SIDEBAR = 0;
        var LONG_SIDEBAR     = 1;
        var SHORT_SIDEBAR    = 2;

        swe.sidebar1 = SIDEBAR1;
        swe.sidebar2 = SIDEBAR2;
        swe.condition = CONDITION;

        init_sidebar(SIDEBAR1, swe.sidebar_id, swe.proportional_sidebar, swe.disable_iflt, swe.float_attr_check_mode);
        init_sidebar(SIDEBAR2, swe.sidebar_id2, swe.proportional_sidebar2, swe.disable_iflt2, swe.float_attr_check_mode2);

        if (swe.accordion_widget) {
            function init_accordion() {


                if (typeof JSON !== "undefined") {
                    $.cookie.json = true;
                    cook = $.cookie('hm_swe');
                }

                var i;
                for (i = 0; i < swe.custom_selectors.length; i++) {

                    $('body').off('mouseenter', swe.custom_selectors[i] + ' ' + swe.heading_string)
                        .off('mouseleave', swe.custom_selectors[i] + ' ' + swe.heading_string);  // first remove them

                    // cursor setting
                    $('body').on('mouseenter', swe.custom_selectors[i] + ' ' + swe.heading_string,
                        function () {
                            $(this).css("cursor", "pointer");
                        })
                        .on('mouseleave', swe.custom_selectors[i] + ' ' + swe.heading_string,
                        function () {
                            $(this).css("cursor", "default");
                        }
                    );

                    var headings = $(swe.custom_selectors[i] + ' ' + swe.heading_string).addClass("hm-swe-accordion-head");

                    // restore status, set heading markers
                    $(swe.custom_selectors[i]).each(function () {
                        var heading = $(this).children(swe.heading_string);

                        /*
                          Priority:
                          1. cookie
                          2. initially collapsed setting
                          3. CSS display = none
                          4. class attribute hm-swe-expanded/hm-swe-collapsed (in theme files)
                         */
                        if ((cook && cook[$(this).attr('id')] == "t") ||
                            (!cook && !swe.initially_collapsed && heading.next().css('display') !== 'none')) {
                            if ((! heading.hasClass('hm-swe-expanded')) && (! heading.hasClass('hm-swe-collapsed'))) {
                                heading.addClass('hm-swe-expanded');
                            }
                        }
                        else {
                            heading.addClass('hm-swe-collapsed');
                        }

                    });

                    headings.filter('.hm-swe-expanded').next().show();
                    headings.filter('.hm-swe-collapsed').next().hide();

                    $('body').off('click', swe.custom_selectors[i] + ' ' + swe.heading_string); // first remove current

                    // click event handler
                    $('body').on('click', swe.custom_selectors[i] + ' ' + swe.heading_string, function () {
                        var c = $(this).next();
                        if (c) {
                            if (c.is(":hidden")) {
                                if (swe.single_expansion) {
                                    $(".hm-swe-accordion-head").not(this).removeClass('hm-swe-expanded').addClass('hm-swe-collapsed');
                                    $(".hm-swe-accordion-head").not(this).next().slideUp(slide_duration);
                                }

                                $(this).removeClass('hm-swe-collapsed').addClass('hm-swe-expanded');
                                c.slideDown(slide_duration, set_widget_status);
                            }
                            else {
                                $(this).removeClass('hm-swe-expanded').addClass('hm-swe-collapsed');
                                c.slideUp(slide_duration, set_widget_status);
                            }
                        }
                    });
                } // end of for
            }  // end of function

            // save current states in cookies
            function set_widget_status() {
                if (typeof JSON !== "undefined") {
                    var c2 = {};
                    var i;
                    for (i = 0; i < swe.custom_selectors.length; i++) {

                        $(swe.custom_selectors[i] + ' ' + swe.heading_string).each(function () {
                            if ($(this).next().is(':visible')) {
                                c2[$(this).parent().attr('id')] = "t";
                            }
                        });
                        $.cookie('hm_swe', c2, { path: '/' });
                    } /* for */
                }
                if (typeof resizefunc === 'function') {
                    resizefunc();
                    /* because the height of the sidebar has changed. */
                }
            }

            init_accordion();

        } // if accordion_widget

        if (swe.scroll_stop && $(contentid).length > 0) {

            function scrollfunc() {
                var curscrolltop = $(window).scrollTop();

                if (SIDEBAR1.o) {
                    manage_sidebar(SIDEBAR1, curscrolltop);
                }
                if (SIDEBAR2.o) {
                    manage_sidebar(SIDEBAR2, curscrolltop);
                }

                CONDITION.direction = curscrolltop - CONDITION.prevscrolltop;
                CONDITION.prevscrolltop = curscrolltop;
            }

            function manage_sidebar(sidebar, curscrolltop) {

                if (sidebar.mode === DISABLED_SIDEBAR) {
                    // For z-index based Themes, do not use css("position", "static")
                    sidebar.o.css("position", "relative");
                    sidebar.o.css("top", "0");
                    sidebar.o.css("left", "0");
                    sidebar.o.css('width', '');
                    sidebar.o.css('margin-left', '');
                    return;
                }

                var sidebar_cur_top = sidebar.o.offset().top;
                sidebar_cur_top -= sidebar.margin_top;

                if ( !swe.ignore_footer &&
                    (   (sidebar.mode == LONG_SIDEBAR &&
                         curscrolltop >= CONDITION.content_top + CONDITION.content_height - CONDITION.window_height) ||
                        (sidebar.mode == SHORT_SIDEBAR &&
                         curscrolltop >= CONDITION.content_top + CONDITION.content_height - sidebar.height - CONDITION.header_space)
                    )) {
                    // scroll again with footer
                    sidebar.o.css("position", "absolute");
                    sidebar.o.css("top", CONDITION.content_top + CONDITION.content_height
                        - sidebar.height - sidebar.absolute_adjustment_top);
                    sidebar.o.css("left", sidebar.default_offset.left - sidebar.absolute_adjustment_left);
                    sidebar.o.css("width", sidebar.width);
                    sidebar.o.css('margin-left', sidebar.margin_left);
                    sidebar.fixed = 0;
                }
                else if ((CONDITION.mode == 2 || sidebar.mode == SHORT_SIDEBAR) && curscrolltop < sidebar.default_offset.top - CONDITION.header_space) {
                    // For z-index based Themes, do not use css("position", "static")
                    sidebar.o.css("position", "relative");
                    sidebar.o.css("top", "0");
                    sidebar.o.css("left", "0");
                    sidebar.o.css("width", '');
                    sidebar.o.css('margin-left', '');
                    sidebar.fixed = 0;
                }
                else if (CONDITION.mode == 2 && sidebar.mode == LONG_SIDEBAR &&  curscrolltop < CONDITION.prevscrolltop &&
                        curscrolltop < sidebar_cur_top - CONDITION.header_space) {
                    // FOR MODE2 BLOCK
                    // at the top of sidebar

                    sidebar.o.css("position", "fixed");
                    sidebar.o.css("top", CONDITION.header_space); // no need of margin-top
                    sidebar.o.css("left", sidebar.default_offset.left - $(window).scrollLeft());
                    sidebar.o.css("width", sidebar.width);
                    sidebar.o.css('margin-left', sidebar.margin_left);
                    sidebar.fixed = 1;
                }
                else if ((CONDITION.mode == 2 && sidebar.mode == LONG_SIDEBAR && curscrolltop >= sidebar_cur_top + sidebar.height - CONDITION.window_height &&
                   curscrolltop > CONDITION.prevscrolltop) ||
                          (CONDITION.mode != 2 && sidebar.mode == LONG_SIDEBAR && curscrolltop >= sidebar.default_offset.top + sidebar.height - CONDITION.window_height)) {
                    // at the bottom of sidebar
                    sidebar.o.css("position", "fixed");
                    sidebar.o.css("top", CONDITION.window_height - sidebar.height);
                    sidebar.o.css("left", sidebar.default_offset.left - $(window).scrollLeft());
                    sidebar.o.css("width", sidebar.width);
                    sidebar.o.css('margin-left', sidebar.margin_left);
                    sidebar.fixed = 1;
                }
                else if (CONDITION.mode == 2 && sidebar.mode == LONG_SIDEBAR && (curscrolltop - CONDITION.prevscrolltop) * CONDITION.direction < 0 && sidebar.fixed) {
                    // FOR MODE2 BLOCK
                    // the direction has changed
                    // mode2 absolute position

                    sidebar.o.css("position", "absolute");
                    sidebar.o.css("top", sidebar_cur_top - sidebar.absolute_adjustment_top);
                    sidebar.o.css("left", sidebar.default_offset.left - sidebar.absolute_adjustment_left);
                    sidebar.o.css("width", sidebar.width);
                    sidebar.o.css('margin-left', sidebar.margin_left);
                    sidebar.fixed = 0;
                }
                else if (sidebar.mode == SHORT_SIDEBAR) {
                    // shorter sidebar as fixed
                    sidebar.o.css("position", "fixed");
                    sidebar.o.css("top", CONDITION.header_space); // no need of margin-top
                    sidebar.o.css("left", sidebar.default_offset.left - $(window).scrollLeft());
                    sidebar.o.css("width", sidebar.width);
                    sidebar.o.css('margin-left', sidebar.margin_left);
                    sidebar.fixed = 1;
                }
                else if (CONDITION.mode != 2) {
                    // For z-index based Themes, do not use css("position", "static")
                    sidebar.o.css("position", "relative");
                    sidebar.o.css("top", "0");
                    sidebar.o.css("left", "0");
                    sidebar.o.css("width", '');
                    sidebar.o.css('margin-left', '');
                    sidebar.fixed = 0;
                }
                else {
                    // continue absolute
                }
                sidebar.previoustop = sidebar_cur_top;

            }

            function resizefunc(fromTimer) {
                var c = $(contentid);
                CONDITION.content_top = c.offset().top;
                CONDITION.content_margin_top = parseInt(c.css('margin-top'), 10);
                CONDITION.content_top -= CONDITION.content_margin_top;

                CONDITION.content_height = c.outerHeight(true);

                CONDITION.window_height = $(window).height();
                CONDITION.prevscrolltop = -1;
                CONDITION.direction = 0;

                CONDITION.header_space = parseInt(swe.header_space, 10);
                var adminbar = $('#wpadminbar');
                if (adminbar.length > 0) {
                    CONDITION.header_space += adminbar.height();
                }

                if (SIDEBAR1.o) {
                    resize_sidebar(SIDEBAR1);
                }
                if (SIDEBAR2.o) {
                    resize_sidebar(SIDEBAR2);
                }

                if (SIDEBAR1.o && SIDEBAR1.mode != DISABLED_SIDEBAR && SIDEBAR2.o && SIDEBAR2.mode != DISABLED_SIDEBAR) {
                    CONDITION.content_height = Math.max(CONDITION.content_height,
                        SIDEBAR1.height + SIDEBAR1.default_offset.top - CONDITION.content_top,
                        SIDEBAR2.height + SIDEBAR2.default_offset.top - CONDITION.content_top);
                }

                // After the content height fix, we finalize the sidebar mode.
                if (SIDEBAR1.o) { finalize_sidebarmode(SIDEBAR1); }
                if (SIDEBAR2.o) { finalize_sidebarmode(SIDEBAR2); }

                scrollfunc();

                if (fromTimer === true && swe.recalc_after > 0 && swe.recalc_count > 0) {
                    if (swe.recalc_count < 10000) {
                        swe.recalc_count--;
                    }
                    setTimeout( resizefunc, swe.recalc_after * 1000, true);
                }

            }

            function is_enabled(sidebar) {
                var f = sidebar.o.css('float');
                return ( $(window).width() >= sidebar.disable_iflt &&
                    ( (! sidebar.float_attr_check_mode) || f == 'left' || f == 'right') );

            }

            function resize_sidebar(sidebar) {
                sidebar.height = sidebar.o.outerHeight(true);

                sidebar.fixed = 0;
                sidebar.previoustop = 0;

                sidebar.o.css("position", "relative");
                sidebar.o.css("top", "0");
                sidebar.o.css("left", "0");

                sidebar.o.css('width', '');
                sidebar.width = parseFloat(sidebar.o.css('width')); // using css('width') (not width())
                // Use a fixed width because the parent will change.

                sidebar.o.css('margin-left', '');
                sidebar.margin_left = parseFloat(sidebar.o.css('margin-left'), 10);  // might be float in responsive themes

                sidebar.default_offset = sidebar.o.offset();
                if (!sidebar.default_offset) {
                    return; // something wrong.
                }

                sidebar.default_offset.top  -= sidebar.margin_top;
                sidebar.default_offset.left -= sidebar.margin_left;

                // determine the adjustment value for the absolute position
                // find a parent which has a position other than static
                var o = sidebar.o.offsetParent();
                sidebar.absolute_adjustment_top  = o.offset().top;  // TODO: margin adjustment needed?
                sidebar.absolute_adjustment_left = o.offset().left;

                if ( ! is_enabled(sidebar) ) {
                    sidebar.mode = DISABLED_SIDEBAR;
                }
                else {
                    sidebar.mode = LONG_SIDEBAR; // Temporarily. We will finalize it after.
                }

            } // function resize_sidebar

            function finalize_sidebarmode(sidebar) {

                if (sidebar.default_offset.top + sidebar.height >= CONDITION.content_top + CONDITION.content_height ||
                    ! is_enabled(sidebar)) {
                    sidebar.mode = DISABLED_SIDEBAR;
                }
                else if (sidebar.height + CONDITION.header_space <= CONDITION.window_height) {
                    sidebar.mode = SHORT_SIDEBAR;
                }
                else {
                    sidebar.mode = LONG_SIDEBAR
                }

            } // finalize_sidebarmode

            swe.resizeHandler = resizefunc;

            $(window).scroll(scrollfunc);
            $(window).resize(resizefunc);



            swe.recalc_after = parseInt(swe.recalc_after, 10);
            swe.recalc_count = parseInt(swe.recalc_count, 10);
            resizefunc(true);

        } // if scroll_stop

        function reloadfunc() {
            if (swe.accordion_widget) {
                init_accordion();
            }

            if (swe.scroll_stop && $(contentid).length) {
                init_sidebar(SIDEBAR1, swe.sidebar_id, swe.proportional_sidebar, swe.disable_iflt, swe.float_attr_check_mode);
                init_sidebar(SIDEBAR2, swe.sidebar_id2, swe.proportional_sidebar2, swe.disable_iflt2, swe.float_attr_check_mode2);
                swe.resizeHandler();
            }
        }

        swe.reloadHandler = reloadfunc;

        // Tabs (test)
        //$( "#tabs-sidebar-3" ).tabs();

    }); // ready function
})(jQuery, window, document);
