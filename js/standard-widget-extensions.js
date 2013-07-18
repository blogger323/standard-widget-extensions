/*
 standard-widget-extensions.js
 Copyright 2013 Hirokazu Matsui (blogger323)

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
		var sidebarid = "#" + swe.sidebar_id;
		var widget = '.' + swe.widget_class;
		var prevscrolltop = -1;
		var fixedsidebartop = -1;
		var mode = parseInt(swe.scroll_mode, 10);
		var fixed = 0;
		var direction = 0;

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
					if (cook && cook[$(this).attr('id')] == "t") {
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
								$(".hm-swe-accordion-head").not(this).next().slideUp();
								if (swe.heading_marker) {
									$(".hm-swe-accordion-head").not(this).css('background', swe.buttonplusurl + " no-repeat left center");
								}
							}

							c.slideDown(set_widget_status);

							if (swe.heading_marker) {
								$(this).css('background', swe.buttonminusurl + " no-repeat left center");
							}

						}
						else {
							c.slideUp(set_widget_status);
							if (swe.heading_marker) {
								$(this).css('background', swe.buttonplusurl + " no-repeat left center");
							}
						}
					}
				});
			}
			/* end of for */

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
					}
					/* for */
				}
				if (typeof resizefunc === 'function') {
					resizefunc();
					/* because the height of the sidebar has changed. */
				}
			}

		}
		/* if accordion_widget */

		if (swe.scroll_stop && $(sidebarid) && $(contentid)) {
			var h, ph, wh, sidebaroffset, sidebarwidth, sidebartop;
			var sidebarmargintop = parseInt($(sidebarid).css('margin-top'), 10);
			var sidebarmarginbottom = parseInt($(sidebarid).css('margin-bottom'), 10);
			var absolute_adjustment_top = 0;
			var absolute_adjustment_left = 0;
			var main_side_adjustment = 0;

			function scrollfunc() {
				if (sidebartop === 1) {
					$(sidebarid).css("position", "static");
					return;
				}
				var curscrolltop = $(window).scrollTop();
				var s = curscrolltop - sidebaroffset.top;

				if ( !swe.ignore_footer && ((s >= ph - wh - main_side_adjustment && sidebartop < 0) ||
						(sidebartop === 0 /* shorter sidebar */ && s >= ph - h - sidebarmargintop - sidebarmarginbottom - main_side_adjustment))) {
					// scroll again with footer
					$(sidebarid).css("position", "absolute");
					$(sidebarid).css("top", sidebaroffset.top + ph - h - sidebarmargintop - sidebarmarginbottom - absolute_adjustment_top - main_side_adjustment);
					$(sidebarid).css("left", sidebaroffset.left - absolute_adjustment_left);
					$(sidebarid).css("width", sidebarwidth);
					fixedsidebartop = $(sidebarid).offset().top;
					fixed = 0;
				}
				else if (mode == 2 && (curscrolltop - prevscrolltop) * direction < 0 && fixed) {
					// mode2 absolute position
					var o = $(sidebarid).offset().top - sidebarmargintop;
					$(sidebarid).css("position", "absolute");
					$(sidebarid).css("top", o - absolute_adjustment_top);
					$(sidebarid).css("left", sidebaroffset.left - absolute_adjustment_left);
					$(sidebarid).css("width", sidebarwidth);
					fixed = 0;
				}
				else if (mode == 2 && curscrolltop < prevscrolltop &&
						curscrolltop < fixedsidebartop  - sidebarmargintop && curscrolltop > sidebaroffset.top) {
					// at the top of sidebar

					$(sidebarid).css("position", "fixed");
					$(sidebarid).css("top", 0);
					$(sidebarid).css("left", sidebaroffset.left - $(window).scrollLeft());
					$(sidebarid).css("width", sidebarwidth);
					fixed = 1;
					fixedsidebartop = $(sidebarid).offset().top + sidebarmarginbottom;
				}
				else if ((mode == 2 && curscrolltop > prevscrolltop && fixedsidebartop > 0 && curscrolltop > fixedsidebartop + h - wh  ) ||
						((mode != 2 || (mode == 2 && fixedsidebartop < 0)) && s >= -sidebartop && sidebartop <= 0)) {
					// at the bottom of sidebar
					$(sidebarid).css("position", "fixed");
					$(sidebarid).css("top", sidebartop);
					$(sidebarid).css("left", sidebaroffset.left - $(window).scrollLeft());
					$(sidebarid).css("width", sidebarwidth);

					fixed = 1;
					fixedsidebartop = $(sidebarid).offset().top;
				}
				else if (mode != 2 || curscrolltop < sidebaroffset.top) {
					$(sidebarid).css("position", "static");
					fixedsidebartop = -1;
					fixed = 0;
				}
				else {
					// continue absolute
				}

				direction = curscrolltop - prevscrolltop;
				prevscrolltop = curscrolltop;
			}

			function resizefunc() {
				h = $(sidebarid).height();
				ph = $(contentid).height() + parseInt($(contentid).css('margin-top'), 10) + parseInt($(contentid).css('margin-bottom'), 10);
				wh = $(window).height();
				prevscrolltop = -1;
				fixedsidebartop = -1;
				fixed = 0;
				direction = 0;

				$(sidebarid).css("position", "static");
				sidebaroffset = $(sidebarid).offset();
				if (!sidebaroffset) {
					return; // something wrong.
				}
				sidebaroffset.top -= sidebarmargintop;
				sidebarwidth = $(sidebarid).width();
				// Use a fixed width because the parent will change.

				// determine the adjustment value for the absolute position
				// find a parent which has a position other than static
				var o = $(sidebarid).parent();
				absolute_adjustment_top = 0;
				absolute_adjustment_left = 0;
				while (o && o.get(0).tagName && o.get(0).tagName.toUpperCase() !== "BODY") {
					if (o.css('position').toLowerCase() !== 'static') {
						absolute_adjustment_top  = o.offset().top;
						absolute_adjustment_left = o.offset().left;
						break;
					}
					o = o.parent();
				}

				// determine the adjustment value for the position diff between the content and the sidebar
				main_side_adjustment = $(sidebarid).offset().top - $(contentid).offset().top;

				sidebartop = wh - h - sidebarmargintop - sidebarmarginbottom;
				if (ph <= h || $(window).width() < swe.disable_iflt) {
					/* longer sidebar than the content || narrow window width */
					sidebartop = 1;
					/* special value for no-scroll */
				}
				else if (sidebartop > 0) { /* shorter sidebar than the window */
					sidebartop = 0;
				}
				scrollfunc();
			}

			swe.resizeHandler = resizefunc

			$(window).scroll(scrollfunc);
			$(window).resize(resizefunc);

			resizefunc();
		}
	}); // ready function
})(jQuery, window, document);
