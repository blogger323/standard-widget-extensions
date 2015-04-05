/*
 standard-widget-extensions.js
 Copyright 2013, 2014, 2015 Hirokazu Matsui (blogger323)

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

        var last_tab_float = '';
        var last_accordion_float = '';

        //---- Accordion Widgets
        var accordion_widgets = {
            name : "accordion_widgets"
        };

        accordion_widgets.init_cookie = function() {
            // should be called just after page loaded.

            // initialize cookie
            if (typeof JSON !== "undefined" && !cook) { // if JSON is available
                $.cookie.json = true;
                cook = $.cookie('hm_swe'); // might be 'undefined'

                /*
                for (var name in cook) {
                    if ($('#' + name).length) {
                        if (cook[name] === 't') {

                        }
                        else {

                        }
                    }
                }
                */

                for (var i = 0; i < swe.custom_selectors.length; i++) {
                    // restore status, set classes for heading markers
                    $(swe.custom_selectors[i]).each(function () {
                        var heading = $(this).children(swe.heading_string);

                        /*
                         Priority:
                         1. cookie
                         2. initially collapsed setting
                         3. CSS display = none
                         4. class attribute hm-swe-expanded/hm-swe-collapsed (in theme files)
                         */
                        if (cook && cook[$(this).attr('id')]) {
                            if (cook[$(this).attr('id')] === "t") {
                                heading.removeClass('hm-swe-collapsed').addClass('hm-swe-expanded');
                            }
                            else { // cook[$(this).attr('id')] === "f"
                                heading.removeClass('hm-swe-expanded').addClass('hm-swe-collapsed');
                            }
                        }
                        else if (swe.initially_collapsed || heading.next().css('display') === 'none') {
                            heading.removeClass('hm-swe-expanded').addClass('hm-swe-collapsed');
                        }
                        else {
                            if ((!heading.hasClass('hm-swe-expanded')) && (!heading.hasClass('hm-swe-collapsed'))) {
                                heading.addClass('hm-swe-expanded');
                            }
                        }

                    });

                }
            }
        };

        accordion_widgets.setup = function() {

            var i;
            last_accordion_float = [];
            for (i = 0; i < swe.custom_selectors.length; i++) {
                var floated = is_floated(swe.custom_selectors_area_id[i]);
                last_accordion_float.push(floated);
                if (swe.accordion_widget_condition === 'always' ||
                    (swe.accordion_widget_condition === 'floated' && floated) ||
                    (swe.accordion_widget_condition === 'not_floated' && !floated)) {
                    // cursor setting
                    $('body').off('mouseenter', swe.custom_selectors[i] + ' ' + swe.heading_string)
                        .off('mouseleave', swe.custom_selectors[i] + ' ' + swe.heading_string);  // first remove them
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

/*
                    // restore status, set classes for heading markers
                    $(swe.custom_selectors[i]).each(function () {
                        var heading = $(this).children(swe.heading_string);

                        if ((cook && cook[$(this).attr('id')] == "t") ||
                            (!cook && !swe.initially_collapsed && heading.next().css('display') !== 'none')) {
                            if ((!heading.hasClass('hm-swe-expanded')) && (!heading.hasClass('hm-swe-collapsed'))) {
                                heading.addClass('hm-swe-expanded');
                            }
                        }
                        else {
                            if ((!heading.hasClass('hm-swe-expanded')) && (!heading.hasClass('hm-swe-collapsed'))) {
                                heading.addClass('hm-swe-collapsed');
                            }
                        }

                    });
                    */

                    headings.filter('.hm-swe-expanded').next().show();
                    headings.filter('.hm-swe-collapsed').next().hide();

                    // set classes if no class is attached
                    $(swe.custom_selectors[i]).each(function () {
                        var heading = $(this).children(swe.heading_string);
                        if ((!heading.hasClass('hm-swe-expanded')) && (!heading.hasClass('hm-swe-collapsed'))) {

                            if (heading.next().css('display') === 'none') {
                                heading.addClass('hm-swe-collapsed');
                            }
                            else {
                                heading.addClass('hm-swe-expanded');
                            }
                        }
                    });

                    // click event handler
                    $('body').off('click', swe.custom_selectors[i] + ' ' + swe.heading_string); // first remove current
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
                } // end of if
                else { // disabled for NOW
                    // fixme: init cookies for later use
                    $(swe.custom_selectors[i]).each(function () {
                        $(this).children(swe.heading_string).removeClass('hm-swe-expanded hm-swe-collapsed');
                    });
                    $('body').off('mouseenter', swe.custom_selectors[i] + ' ' + swe.heading_string)
                        .off('mouseleave', swe.custom_selectors[i] + ' ' + swe.heading_string);
                    $('body').off('click', swe.custom_selectors[i] + ' ' + swe.heading_string);

                    // show all
                    // todo: needs to hide some widgets?
                    $(swe.custom_selectors[i] + ' ' + swe.heading_string).each(function() {
                        $(this).next().show();
                    });

                } // end of else
            } // end of for

        }; // setup function


        //---- Smart Sidebar
        var smart_sidebar = {
            name : "smart_sidebar",
            display_button_selector: '#hm-swe-smart-sidebar-display-button',
            cancel_button_selector: '#hm-swe-smart-sidebar-cancel-button'
        };

        smart_sidebar.enable = function() {
            var sidebar = $('#' + swe.smart_sidebar_id);
            sidebar.css({
//                width: "90vw",
//                height: "95vh",
//                position: "fixed",
//                left: "5vw",
//                top: "2.5vh",
                "background-color": 'white',
                "z-index": 100000, // greater than adminbar z-index (= 99999)
                "overflow-y": "scroll",
                display: "none"
            });

            // use a function to avoid conflict with sticky_sidebar
            swe.set_sidebar_attributes(swe.smart_sidebar_id,
                {
                    'position': 'fixed',
                    'top' : '2.5vh',
                    'left' : '5vw',
                    'width' : '90vw',
                    'height' : '95vh'
                },
                smart_sidebar);

            if (! $(smart_sidebar.display_button_selector).length) {
                sidebar.prepend('<div><div id="' + smart_sidebar.display_button_selector.slice(1) +
                    '" class="dashicons dashicons-no-alt" style="height: 64px; width: 64px; font-size: 64px; float: right;"></div></div>');

                $(smart_sidebar.display_button_selector).click(function(){
                    $('#' + swe.smart_sidebar_id).hide(400);
                })
                    .hover(
                    function() {
                        $(this).css("cursor", "pointer");
                    },
                    function() {
                        $(this).css("cursor", "pointer");
                    }
                );
            }

            if (! $(smart_sidebar.cancel_button_selector).length) {

                $('body').append('<div><span id="' + smart_sidebar.cancel_button_selector.slice(1) +
                    '" class="dashicons dashicons-admin-generic" style="height: 64px; width: 64px; font-size: 64px"></span></div>');

                $(smart_sidebar.cancel_button_selector).click(function() {
                    $('#' + swe.smart_sidebar_id).show(400);
                })
                    .css({
                        top: top,
                        left: left,
                        "z-index": 10000,
                        position: "fixed"

                    }
                )
                    .hover(
                    function() {
                        $(this).css("cursor", "pointer");
                    },
                    function() {
                        $(this).css("cursor", "pointer");
                    }
                );
            }

            var top  = parseInt(swe.smart_sidebar_top) + adjustment_for_adminbar();
            var left = parseInt(swe.smart_sidebar_left);

            if (left < 0) {
                left = $(window).width() + left;
            }
            $(smart_sidebar.cancel_button_selector).css({
                top: top,
                left: left
            });
        }; // enable function

        smart_sidebar.disable = function() {
            var display_button = $(smart_sidebar.display_button_selector);
            var cancel_button = $(smart_sidebar.cancel_button_selector);

            display_button.remove();
            cancel_button.remove();

            smart_sidebar.reset_css();
        };

        smart_sidebar.reset_css = function() {
            var sidebar = $('#' + swe.smart_sidebar_id);
            sidebar.css({
                width: "",
                height: "",
                position: "",
                left: "",
                top: "",
                "background-color": '',
                "z-index": '',
                "overflow-y": "",
                display: ""
            });
        }

        smart_sidebar.setup = function() {
            smart_sidebar.reset_css(); // restore the original position
            if (swe.smart_sidebar_condition === 'not_floated' && swe.smart_sidebar_id && !is_floated('#' + swe.smart_sidebar_id)) {
                smart_sidebar.enable();
            }
            else {
                smart_sidebar.disable();
            }
        };

        //---- Tabbed Widgets
        var tabbed_widgets = {
            name : "tabbed_widgets"
        };

        tabbed_widgets.setup = function() {
            last_tab_float = [];
            if (swe.tab_widget_condition !== 'never') {

                for (var i = 0; i < swe.tab_sidebar_id.length; i++) {

                    if (swe.tab_sidebar_id[i]) {
                        var floated = is_floated('#' + swe.tab_sidebar_id[i]);
                        last_tab_float.push(floated);
                        if (swe.tab_widget_condition === 'always' ||
                            (swe.tab_widget_condition === 'floated' && floated) ||
                            (swe.tab_widget_condition === 'not_floated' && !floated)) {
                            if (! $('#' + swe.tab_sidebar_id[i]).hasClass('ui-tabs')) {
                                $('#' + swe.tab_sidebar_id[i]).tabs();
                            }
                            $('#hm-swe-tab-ul-' + swe.tab_sidebar_id[i]).show(); // show the <ul> element for tab titles, added in PHP
                        }
                        else {
                            if ($('#' + swe.tab_sidebar_id[i]).hasClass('ui-tabs')) {
                                $('#' + swe.tab_sidebar_id[i]).tabs('destroy');
                            }
                            $('#hm-swe-tab-ul-' + swe.tab_sidebar_id[i]).hide(); // hide the <ul> element for tab titles
                        }
                    }
                }
            }
        };

        //---- Sticky Sidebar
        var sticky_sidebar = {
            name : "sticky_sidebar"
        };

        sticky_sidebar.enabled = function() {
            return swe.sidebar1_condition !== 'never' || swe.sidebar2_condition !== 'never';
        };

        sticky_sidebar.setup = function(fromTimer) {

            var c = $(contentid);
            if (sticky_sidebar.enabled() && c.length > 0) {
                CONDITION.content_top = c.offset().top;
                CONDITION.content_margin_top = parseInt(c.css('margin-top'), 10);
                CONDITION.content_top -= CONDITION.content_margin_top;

                CONDITION.content_height = c.outerHeight(true);

                CONDITION.window_height = $(window).height();
                CONDITION.prevscrolltop = -1;
                CONDITION.direction = 0;

                CONDITION.header_space = parseInt(swe.header_space, 10);
                CONDITION.header_space += adjustment_for_adminbar();


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
                if (SIDEBAR1.o) {
                    finalize_sidebarmode(SIDEBAR1);
                }
                if (SIDEBAR2.o) {
                    finalize_sidebarmode(SIDEBAR2);
                }

                scrollfunc();

                if (fromTimer === true && swe.recalc_after > 0 && swe.recalc_count > 0) {
                    if (swe.recalc_count < 10000) {
                        swe.recalc_count--;
                    }
                    setTimeout(sticky_sidebar.setup, swe.recalc_after * 1000, true);
                }
            }
        }

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

        function init_sidebar(sidebar, param_id, percent_width, disable_iflt, sidebar_condition) {
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
            sidebar.float_attr_check_mode = (sidebar_condition  === 'floated');

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

        // store sidebar 'float' status
        swe.set_init_floats = function() {
            swe.init_floats = {};

            swe.init_floats[swe.sidebar_id] = $("#" + swe.sidebar_id).css("float", "").css("float");
            swe.init_floats[swe.sidebar_id2] = $("#" + swe.sidebar_id2).css("float", "").css("float");
            swe.init_floats[swe.smart_sidebar_id] = $("#" + swe.smart_sidebar_id).css("float", "").css("float");

            // {"" : undefined} appears
        };

        /*
        swe.set_position = function(id, position, top, left, obj) {
            if (swe.init_floats[id]) {
                if (swe.init_floats[id] === "none" && obj.name === "smart_sidebar") {
                    $("#" + id).css({
                        "position" : position,
                        "top" : top,
                        "left" : left
                    });
                }
                else if (swe.init_floats[id] !== "none" && obj.name === "sticky_sidebar") {
                    $("#" + id).css({
                        "position" : position,
                        "top" : top,
                        "left" : left
                    });
                }
            }
        };
        */

        swe.set_sidebar_attributes = function(id, attributes, obj) {
            if (swe.init_floats[id]) {
                if (swe.init_floats[id] === "none" && obj.name === "smart_sidebar") {
                    $("#" + id).css(attributes);
                }
                else if (swe.init_floats[id] !== "none" && obj.name === "sticky_sidebar") {
                    $("#" + id).css(attributes);
                }
            }
        }

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
                        else {
                            c2[$(this).parent().attr('id')] = "f";
                        }
                    });
                    $.cookie('hm_swe', c2, { path: '/' });
                } /* for */
            }

            sticky_sidebar.setup();
        }

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
                swe.set_sidebar_attributes(sidebar.id.substring(1),
                    {
                        'position': 'relative',
                        'top': '0',
                        'left': '0',
                        'width' : '',
                        'margin-left' : ''                            
                    },
                    sticky_sidebar);
//                sidebar.o.css("position", "relative");
//                sidebar.o.css("top", "0");
//                sidebar.o.css("left", "0");
//                sidebar.o.css('width', '');
//                sidebar.o.css('margin-left', '');
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
                swe.set_sidebar_attributes(sidebar.id.substring(1),
                    {
                        'position': 'absolute',
                        'top': CONDITION.content_top + CONDITION.content_height - sidebar.height - sidebar.absolute_adjustment_top,
                        'left': sidebar.default_offset.left - sidebar.absolute_adjustment_left,
                        'width': sidebar.width,
                        'margin-left': sidebar.margin_left
                    },
                    sticky_sidebar);
//                sidebar.o.css("position", "absolute");
//                sidebar.o.css("top", CONDITION.content_top + CONDITION.content_height
//                - sidebar.height - sidebar.absolute_adjustment_top);
//                sidebar.o.css("left", sidebar.default_offset.left - sidebar.absolute_adjustment_left);
//                sidebar.o.css("width", sidebar.width);
//                sidebar.o.css('margin-left', sidebar.margin_left);
                sidebar.fixed = 0;
            }
            else if ((CONDITION.mode == 2 || sidebar.mode == SHORT_SIDEBAR) && curscrolltop < sidebar.default_offset.top - CONDITION.header_space) {
                // For z-index based Themes, do not use css("position", "static")
                swe.set_sidebar_attributes(sidebar.id.substring(1),
                    {
                        'position': 'relative',
                        'top': '0',
                        'left': '0',
                        'width' : '',
                        'margin-left' : ''
                    },
                sticky_sidebar);
//                sidebar.o.css("position", "relative");
//                sidebar.o.css("top", "0");
//                sidebar.o.css("left", "0");
//                sidebar.o.css("width", '');
//                sidebar.o.css('margin-left', '');
                sidebar.fixed = 0;
            }
            else if (CONDITION.mode == 2 && sidebar.mode == LONG_SIDEBAR &&  curscrolltop < CONDITION.prevscrolltop &&
                curscrolltop < sidebar_cur_top - CONDITION.header_space) {
                // FOR MODE2 BLOCK
                // at the top of sidebar

                swe.set_sidebar_attributes(sidebar.id.substring(1),
                    {
                        'position': 'fixed',
                        'top': CONDITION.header_space,
                        'left': sidebar.default_offset.left - $(window).scrollLeft(),
                        'width': sidebar.width,
                        'margin-left': sidebar.margin_left
                    },
                sticky_sidebar);
//                sidebar.o.css("position", "fixed");
//                sidebar.o.css("top", CONDITION.header_space); // no need of margin-top
//                sidebar.o.css("left", sidebar.default_offset.left - $(window).scrollLeft());
//                sidebar.o.css("width", sidebar.width);
//                sidebar.o.css('margin-left', sidebar.margin_left);
                sidebar.fixed = 1;
            }
            else if ((CONDITION.mode == 2 && sidebar.mode == LONG_SIDEBAR && curscrolltop >= sidebar_cur_top + sidebar.height - CONDITION.window_height &&
                curscrolltop > CONDITION.prevscrolltop) ||
                (CONDITION.mode != 2 && sidebar.mode == LONG_SIDEBAR && curscrolltop >= sidebar.default_offset.top + sidebar.height - CONDITION.window_height)) {
                // at the bottom of sidebar
                swe.set_sidebar_attributes(sidebar.id.substring(1),
                    {
                        'position': 'fixed',
                        'top': CONDITION.window_height - sidebar.height,
                        'left': sidebar.default_offset.left - $(window).scrollLeft(),
                        'width': sidebar.width,
                        'margin-left' : sidebar.margin_left
                    },
                sticky_sidebar);
//                sidebar.o.css("position", "fixed");
//                sidebar.o.css("top", CONDITION.window_height - sidebar.height);
//                sidebar.o.css("left", sidebar.default_offset.left - $(window).scrollLeft());
//                sidebar.o.css("width", sidebar.width);
//                sidebar.o.css('margin-left', sidebar.margin_left);
                sidebar.fixed = 1;
            }
            else if (CONDITION.mode == 2 && sidebar.mode == LONG_SIDEBAR && (curscrolltop - CONDITION.prevscrolltop) * CONDITION.direction < 0 && sidebar.fixed) {
                // FOR MODE2 BLOCK
                // the direction has changed
                // mode2 absolute position

                swe.set_sidebar_attributes(sidebar.id.substring(1),
                    {
                        'position': 'absolute',
                        'top': sidebar_cur_top - sidebar.absolute_adjustment_top,
                        'left': sidebar.default_offset.left - sidebar.absolute_adjustment_left,
                        'width' : sidebar.width,
                        'margin-left' : sidebar.margin_left
                    },
                    sticky_sidebar);
//                sidebar.o.css("position", "absolute");
//                sidebar.o.css("top", sidebar_cur_top - sidebar.absolute_adjustment_top);
//                sidebar.o.css("left", sidebar.default_offset.left - sidebar.absolute_adjustment_left);
//                sidebar.o.css("width", sidebar.width);
//                sidebar.o.css('margin-left', sidebar.margin_left);
                sidebar.fixed = 0;
            }
            else if (sidebar.mode == SHORT_SIDEBAR) {
                // shorter sidebar as fixed
                swe.set_sidebar_attributes(sidebar.id.substring(1),
                    {
                        'position': 'fixed',
                        'top': CONDITION.header_space,
                        'left': sidebar.default_offset.left - $(window).scrollLeft(),
                        'width' : sidebar.width,
                        'margin-left' : sidebar.margin_left
                    },
                sticky_sidebar);
//                sidebar.o.css("position", "fixed");
//                sidebar.o.css("top", CONDITION.header_space); // no need of margin-top
//                sidebar.o.css("left", sidebar.default_offset.left - $(window).scrollLeft());
//                sidebar.o.css("width", sidebar.width);
//                sidebar.o.css('margin-left', sidebar.margin_left);
                sidebar.fixed = 1;
            }
            else if (CONDITION.mode != 2) {
                // For z-index based Themes, do not use css("position", "static")
                swe.set_sidebar_attributes(sidebar.id.substring(1),
                    {
                        'position': 'relative',
                        'top': '0',
                        'left': '0',
                        'width': '',
                        'margin-left': ''
                    },
                sticky_sidebar);
//                sidebar.o.css("position", "relative");
//                sidebar.o.css("top", "0");
//                sidebar.o.css("left", "0");
//                sidebar.o.css("width", '');
//                sidebar.o.css('margin-left', '');
                sidebar.fixed = 0;
            }
            else {
                // continue absolute
            }
            sidebar.previoustop = sidebar_cur_top;

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

            swe.set_sidebar_attributes(sidebar.id.substring(1),
                {
                    'position': 'relative',
                    'top': '0',
                    'left': '0',
                    'width': '',
                    'margin-left' : ''
                },
                sticky_sidebar);
//            sidebar.o.css("position", "relative");
//            sidebar.o.css("top", "0");
//            sidebar.o.css("left", "0");

//            sidebar.o.css('width', '');
            sidebar.width = parseFloat(sidebar.o.css('width')); // using css('width') (not width())
            // preserve this value to use for a fixed position because the parent will change.

//            sidebar.o.css('margin-left', '');
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


        function adjustment_for_adminbar() {
            var adminbar = $('#wpadminbar');
            return adminbar.length > 0 ?  adminbar.height(): 0;
        }

        function resizefunc() {

            swe.set_init_floats();
            accordion_widgets.setup();
            tabbed_widgets.setup();
            smart_sidebar.setup();
            sticky_sidebar.setup();
        }


        function reloadfunc() {
            swe.set_init_floats();
            accordion_widgets.setup();
            tabbed_widgets.setup();
            smart_sidebar.setup();

            if (sticky_sidebar.enabled() && $(contentid).length) {
                init_sidebar(SIDEBAR1, swe.sidebar_id, swe.proportional_sidebar, swe.disable_iflt, swe.sidebar1_condition);
                init_sidebar(SIDEBAR2, swe.sidebar_id2, swe.proportional_sidebar2, swe.disable_iflt2, swe.sidebar2_condition);
                sticky_sidebar.setup();
            }
        }

        init_sidebar(SIDEBAR1, swe.sidebar_id, swe.proportional_sidebar, swe.disable_iflt, swe.sidebar1_condition);
        init_sidebar(SIDEBAR2, swe.sidebar_id2, swe.proportional_sidebar2, swe.disable_iflt2, swe.sidebar2_condition);

        swe.reloadHandler = reloadfunc;
        swe.resizeHandler = sticky_sidebar.setup;
        $(window).resize(resizefunc);

        swe.set_init_floats();
        accordion_widgets.init_cookie();
        accordion_widgets.setup();
        tabbed_widgets.setup();
        smart_sidebar.setup();

        if (sticky_sidebar.enabled() && $(contentid).length > 0) {

            $(window).scroll(scrollfunc);

            swe.recalc_after = parseInt(swe.recalc_after, 10);
            swe.recalc_count = parseInt(swe.recalc_count, 10);
            sticky_sidebar.setup(true);

        } // if

        function is_floated(selector) {
            var q = $(selector);
            return q.length && (q.css('float') === 'left' || q.css('float') === 'right');
        }


        // check if there are any changes related to tabs and accordions.
        // initial states are set in each initial function.
        function has_changed() {
            if (swe.tab_widget_condition !== 'never') {
                if (!last_tab_float) {
                    return true;
                }
                for (var i = 0; i < swe.tab_sidebar_id.length; i++) {
                    if (swe.tab_sidebar_id[i]) {
                        var floated = is_floated('#' + swe.tab_sidebar_id[i]);
                        if (floated !== last_tab_float[i]) {
                            return true;
                        }
                    }
                }
            }

            if (swe.accordion_widget) {
                if (!last_accordion_float) {
                    return true;
                }
                for (i = 0; i < swe.custom_selectors_area_id.length; i++) {
                    var floated = is_floated(swe.custom_selectors_area_id[i]);
                    if (floated !== last_accordion_float[i]) {
                        return true;
                    }
                }
            }

            return false;
        }

    }); // ready function
})(jQuery, window, document);
