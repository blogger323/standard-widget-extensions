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
(function ($, window, document, undefined) {

	$(document).ready(function () {
		var cook = null;
		var contentid = "#" + swe.maincol_id;
		var widget = '.' + swe.widget_class;
		var slide_duration = parseInt(swe.slide_duration, 10);

		var CONDITION = {
			content_height: 0, /* including margins */
			window_height: 0,
			mode: parseInt(swe.scroll_mode, 10), /* 2: switch back */
			header_space: parseInt(swe.header_space, 10),
			direction: 0,
			prevscrolltop: -1
		};

		function init_sidebar(sidebar, param_id, percent_width, disable_iflt) {
			sidebar.o =  null; // jQuery object
			sidebar.top = 0;
			sidebar.height = 0;
			sidebar.fixedtop = -1;
			sidebar.fixed = 0;
			sidebar.offset = 0;
			sidebar.margintop = 0;
			sidebar.marginbottom = 0;
			sidebar.marginleft = 0;
			sidebar.width = 0;
			sidebar.absolute_adjustment_top = 0;
			sidebar.absolute_adjustment_left = 0;
			sidebar.main_side_adjustment = 0;
			sidebar.percent_width = 0;
			sidebar.disable_iflt = 0;

            sidebar.previoustop = 0;

			if (param_id) {
				sidebar.id = '#' + param_id;
				if (sidebar.id && $(sidebar.id).length > 0) {
					sidebar.o = $(sidebar.id);
					sidebar.parent = sidebar.o.parent();
					sidebar.margintop = parseInt(sidebar.o.css('margin-top'), 10);
					sidebar.marginbottom = parseInt(sidebar.o.css('margin-bottom'), 10);
					sidebar.padding_top    = parseInt(sidebar.o.css('padding-top'), 10);
					sidebar.padding_bottom = parseInt(sidebar.o.css('padding-bottom'), 10);
					sidebar.marginleft = parseFloat(sidebar.o.css('margin-left'), 10);  // might be float in responsive themes
					sidebar.percent_width = parseFloat(percent_width, 10);
					sidebar.disable_iflt = parseInt(disable_iflt, 10);

					/* fix for negative percent margins in decimal (for TwentyFourteen) */
					if (sidebar.marginleft < 0) {
						sidebar.marginleft = Math.floor(sidebar.marginleft);
					}

					/* fix for margins in percent */
					sidebar.o.css('margin-left', sidebar.marginleft);

				}
			}
		}

        var SIDEBAR1 = {};
        var SIDEBAR2 = {};
        var DISABLED_SIDEBAR = 9999;

		init_sidebar(SIDEBAR1, swe.sidebar_id, swe.proportional_sidebar, swe.disable_iflt);
		init_sidebar(SIDEBAR2, swe.sidebar_id2, swe.proportional_sidebar2, swe.disable_iflt2);

		if (swe.accordion_widget) {
			if (typeof JSON !== "undefined") {
				$.cookie.json = true;
				cook = $.cookie('hm_swe');
			}

			var i;
			for (i = 0; i < swe.custom_selectors.length; i++) {

				$(swe.custom_selectors[i] + ' ' + swe.heading_string).hover(
						function () {
							$(this).css("cursor", "pointer");
						},
						function () {
							$(this).css("cursor", "default");
						}
				);

				$(swe.custom_selectors[i] + ' ' + swe.heading_string).addClass("hm-swe-accordion-head");

				// restore status, set heading markers
				$(swe.custom_selectors[i]).each(function () {
					if (cook && cook[$(this).attr('id')] == "t" /* TODO: || ! cook && visible */) {
						$(this).children(swe.heading_string).next().show();
						if (swe.heading_marker) {
							$(this).children(swe.heading_string).css('background', swe.buttonminusurl + " no-repeat left center");
						}
					}
					else {
						$(this).children(swe.heading_string).next().hide();
						if (swe.heading_marker) {
							$(this).children(swe.heading_string).css('background', swe.buttonplusurl + " no-repeat left center");
						}
					}
				});

				// click event handler
				$(swe.custom_selectors[i] + ' ' + swe.heading_string).click(function () {
					var c = $(this).next();
					if (c) {
						if (c.is(":hidden")) {
							if (swe.single_expansion) {
								$(".hm-swe-accordion-head").not(this).next().slideUp(slide_duration);
								if (swe.heading_marker) {
									$(".hm-swe-accordion-head").not(this).css('background', swe.buttonplusurl + " no-repeat left center");
								}
							}

							c.slideDown(slide_duration, set_widget_status);

							if (swe.heading_marker) {
								$(this).css('background', swe.buttonminusurl + " no-repeat left center");
							}

						}
						else {
							c.slideUp(slide_duration, set_widget_status);
							if (swe.heading_marker) {
								$(this).css('background', swe.buttonplusurl + " no-repeat left center");
							}
						}
					}
				});
			} // end of for

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
				var s = curscrolltop - sidebar.offset.top;

				if (sidebar.top === DISABLED_SIDEBAR) {
					// For z-index based Themes, do not use css("position", "static")
					sidebar.o.css("position", "relative");
					sidebar.o.css("top", "0");
					sidebar.o.css("left", "0");
					return;
				}

				if ( !swe.ignore_footer && ((s >= CONDITION.content_height - CONDITION.window_height - sidebar.main_side_adjustment  && sidebar.top < CONDITION.header_space) ||
						(sidebar.top === CONDITION.header_space /* shorter sidebar */ && s >= CONDITION.content_height - sidebar.height - sidebar.margintop - sidebar.marginbottom - sidebar.main_side_adjustment - sidebar.padding_top - sidebar.padding_bottom))) {
					// scroll again with footer
					sidebar.o.css("position", "absolute");
					sidebar.o.css("top", sidebar.offset.top + CONDITION.content_height - sidebar.height - sidebar.margintop - sidebar.marginbottom - sidebar.absolute_adjustment_top - sidebar.main_side_adjustment - sidebar.padding_top - sidebar.padding_bottom + CONDITION.header_space);
					sidebar.o.css("left", sidebar.offset.left - sidebar.absolute_adjustment_left - sidebar.marginleft);
					sidebar.o.css("width", sidebar.width);
					sidebar.fixedtop = sidebar.o.offset().top;
					sidebar.fixed = 0;
				}
				else if (CONDITION.mode == 2 && (curscrolltop - CONDITION.prevscrolltop) * CONDITION.direction < 0 && sidebar.fixed
                    && sidebar.top != CONDITION.header_space /* not shorter sidebar */) {
					// FOR MODE2 BLOCK
					// the direction has changed
					// mode2 absolute position
					var o = sidebar.o.offset().top - sidebar.margintop;
					sidebar.o.css("position", "absolute");
					sidebar.o.css("top", o - sidebar.absolute_adjustment_top);
					sidebar.o.css("left", sidebar.offset.left - sidebar.absolute_adjustment_left - sidebar.marginleft);
					sidebar.o.css("width", sidebar.width);
					sidebar.fixed = 0;
				}
				else if (CONDITION.mode == 2 && curscrolltop < CONDITION.prevscrolltop &&
						curscrolltop < sidebar.fixedtop  - sidebar.margintop - CONDITION.header_space  && curscrolltop > sidebar.offset.top) {
					// FOR MODE2 BLOCK
					// at the top of sidebar

					sidebar.o.css("position", "fixed");
					sidebar.o.css("top", CONDITION.header_space);
					sidebar.o.css("left", sidebar.offset.left - $(window).scrollLeft() - sidebar.marginleft);
					sidebar.o.css("width", sidebar.width);
					sidebar.fixed = 1;
					sidebar.fixedtop = sidebar.o.offset().top + sidebar.marginbottom;
				}
				else if ((CONDITION.mode == 2 && curscrolltop > CONDITION.prevscrolltop && sidebar.fixedtop > 0 && curscrolltop > sidebar.fixedtop + sidebar.height + sidebar.padding_top + sidebar.padding_bottom - CONDITION.window_height  ) ||
						((CONDITION.mode != 2 || (CONDITION.mode == 2 && sidebar.fixedtop < 0)) && s >= -sidebar.top && sidebar.top <= CONDITION.header_space)) {
					// at the bottom of sidebar
					sidebar.o.css("position", "fixed");
					sidebar.o.css("top", sidebar.top /*- CONDITION.header_space */); /* No adjustment for shorter sidebars but needs adjustment for longer ones Jan. 2014 */
					sidebar.o.css("left", sidebar.offset.left - $(window).scrollLeft() - sidebar.marginleft);
					sidebar.o.css("width", sidebar.width);

					sidebar.fixed = 1;
					sidebar.fixedtop = sidebar.o.offset().top;
				}
				else if (CONDITION.mode != 2 || curscrolltop < sidebar.offset.top - CONDITION.header_space) {
					// For z-index based Themes, do not use css("position", "static")
					sidebar.o.css("position", "relative");
					sidebar.o.css("top", "0");
					sidebar.o.css("left", "0");
					sidebar.o.css("width", sidebar.width);
					sidebar.fixedtop = -1;
					sidebar.fixed = 0;
				}
				else {
					// continue absolute
				}
                sidebar.previoustop = sidebar.o.offset().top;

			}

			function resizefunc() {
				CONDITION.content_height = $(contentid).height() + parseInt($(contentid).css('margin-top'), 10) + parseInt($(contentid).css('margin-bottom'), 10);
				CONDITION.window_height = $(window).height();
				CONDITION.prevscrolltop = -1;
				CONDITION.direction = 0;

				if (SIDEBAR1.o) {
					resize_sidebar(SIDEBAR1);
				}
				if (SIDEBAR2.o) {
					resize_sidebar(SIDEBAR2);
				}

				scrollfunc();
			}

			function resize_sidebar(sidebar) {
				sidebar.height = sidebar.o.height();
				sidebar.fixedtop = -1;
				sidebar.fixed = 0;

				sidebar.o.css("position", "relative");
				sidebar.o.css("top", "0");
				sidebar.o.css("left", "0");

				if (sidebar.percent_width === 0) {
					sidebar.width = sidebar.o.css('width'); // using css('width') (not width())
					// Use a fixed width because the parent will change.
				}
				else {
					sidebar.width = sidebar.parent.css('width') * sidebar.percent_width / 100;
					sidebar.o.css('width', sidebar.width);
				}

				sidebar.offset = sidebar.o.offset();
				if (!sidebar.offset) {
					return; // something wrong.
				}
				sidebar.offset.top -= sidebar.margintop;

				// determine the adjustment value for the absolute position
				// find a parent which has a position other than static
				var o = sidebar.o.offsetParent();
				sidebar.absolute_adjustment_top  = o.offset().top;
				sidebar.absolute_adjustment_left = o.offset().left;

				// determine the adjustment value for the position diff between the content and the sidebar
				sidebar.main_side_adjustment = sidebar.o.offset().top - $(contentid).offset().top;

				// The top position of the sidebar when fixed. Usually has a negative value.
				sidebar.top = CONDITION.window_height - sidebar.height - sidebar.margintop - sidebar.marginbottom
				  - sidebar.padding_top - sidebar.padding_bottom + CONDITION.header_space;
				if (CONDITION.content_height <= sidebar.height + sidebar.padding_top + sidebar.padding_bottom || $(window).width() < sidebar.disable_iflt) {
					/* longer sidebar than the content || narrow window width */
					sidebar.top = DISABLED_SIDEBAR;
					/* special value for no-scroll */
				}
				else if (sidebar.top > CONDITION.header_space) { /* shorter sidebar than the window */
					sidebar.top = CONDITION.header_space;
				}
			} // function resize_sidebar

			swe.resizeHandler = resizefunc;

			$(window).scroll(scrollfunc);
			$(window).resize(resizefunc);

			if (swe.enable_reload_me) {
				// add elements to display warning
				$('body').append('<div class="hm-swe-modal-background"><div class="hm-swe-resize-message">' +
						swe.msg_reload_me +
						'<br /><input type="button" id="hm-swe-reload-button" value="' + swe.msg_reload + '" /></div></div>');

				// set handlers
				$(window).resize(function() {
					$('.hm-swe-modal-background').css('display', 'block');
				});

				$('.hm-swe-modal-background').click(function() {
					$('.hm-swe-modal-background').css('display', 'none');
				});

				$('#hm-swe-reload-button').click(function() {
					location.reload();
				});
			}

			resizefunc();

			var recalc_after = parseInt(swe.recalc_after, 10);
			if (recalc_after > 0) {
				setTimeout( resizefunc, recalc_after * 1000);
			}
		} // if scroll_stop
	}); // ready function
})(jQuery, window, document);
