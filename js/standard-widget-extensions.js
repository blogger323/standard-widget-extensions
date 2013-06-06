(function ($, window, document, undefined) {

	$(document).ready(function () {
		var cook = null;
		var contentid = "#" + swe_params.maincol_id;
		var sidebarid = "#" + swe_params.sidebar_id;
		var widget = '.' + swe_params.widget_class;
		var prevscrolltop = -1;
		var fixedsidebartop = -1;
		var mode = parseInt(swe_params.scroll_mode, 10);
		var fixed = 0;
		var direction = 0;

		if (swe_params.accordion_widget) {
			if (typeof JSON !== "undefined") {
				$.cookie.json = true;
				cook = $.cookie('hm_swe');
			}

			var i;
			for (i = 0; i < swe_params.accordion_widget_areas.length; i++) {
				var area =
						(swe_params.accordion_widget_areas[i] ? " #" + swe_params.accordion_widget_areas[i] : "");
				$(sidebarid + area + ' ' + widget + ' h3').hover(
						function () {
							$(this).css("cursor", "pointer");
						},
						function () {
							$(this).css("cursor", "default");
						}
				);

				$(sidebarid + area + ' ' + widget).each(function () {
					if (cook && cook[$(this).attr('id')] == "t") {
						$(this).children('h3').next().show();
						if (swe_params.heading_marker) {
							$(this).children('h3').css('background', swe_params.buttonminusurl + " no-repeat left center");
						}
					}
					else {
						$(this).children('h3').next().hide();
						if (swe_params.heading_marker) {
							$(this).children('h3').css('background', swe_params.buttonplusurl + " no-repeat left center");
						}
					}
				});

				$(sidebarid + area + ' ' + widget + ' h3').click(function () {
					var c = $(this).next();
					if (c) {
						if (c.is(":hidden")) {
							c.slideDown(set_widget_status);
							if (swe_params.heading_marker) {
								$(this).css('background', swe_params.buttonminusurl + " no-repeat left center");
							}
						}
						else {
							c.slideUp(set_widget_status);
							if (swe_params.heading_marker) {
								$(this).css('background', swe_params.buttonplusurl + " no-repeat left center");
							}
						}
					}
				});
			}
			/* for */

			function set_widget_status() {
				if (typeof JSON !== "undefined") {
					var c2 = {};
					var i;
					for (i = 0; i < swe_params.accordion_widget_areas.length; i++) {
						var area =
								(swe_params.accordion_widget_areas[i] ? " #" + swe_params.accordion_widget_areas[i] : "");

						$(sidebarid + area + ' ' + widget + ' h3').each(function () {
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

		if (swe_params.scroll_stop && $(sidebarid) && $(contentid)) {
			var h, ph, wh, sidebaroffset, sidebarwidth, sidebartop;
			var sidebarmargintop = parseInt($(sidebarid).css('margin-top'), 10);
			var sidebarmarginbottom = parseInt($(sidebarid).css('margin-bottom'), 10);

			function scrollfunc() {
				if (sidebartop === 1) {
					$(sidebarid).css("position", "static");
					return;
				}
				var curscrolltop = $(window).scrollTop();
				var s = curscrolltop - sidebaroffset.top;

				if ((s >= ph - wh && sidebartop < 0) ||
						(sidebartop === 0 && s >= ph - h - sidebarmargintop - sidebarmarginbottom)) {
                    // scroll again with footer
					$(sidebarid).css("position", "absolute");
					$(sidebarid).css("top", sidebaroffset.top + ph - h - sidebarmargintop - sidebarmarginbottom);
					$(sidebarid).css("left", sidebaroffset.left);
					$(sidebarid).css("width", sidebarwidth);
					fixedsidebartop = $(sidebarid).offset().top; // TODO: check condition
					fixed = 0;
				}
				else if (mode == 2 && (curscrolltop - prevscrolltop) * direction < 0 && fixed) {
					// mode2 absolute position
					$(sidebarid).css("position", "absolute");
					$(sidebarid).css("top", fixedsidebartop  - sidebarmargintop);
					$(sidebarid).css("left", sidebaroffset.left);
					$(sidebarid).css("width", sidebarwidth);
					fixed = 0;
				}
				else if (mode == 2 && curscrolltop < prevscrolltop &&
						curscrolltop < fixedsidebartop  - sidebarmargintop && curscrolltop > sidebaroffset.top) { // TODO: check condition
					// at the top of sidebar

					$(sidebarid).css("position", "fixed");
					$(sidebarid).css("top", 0 /*sidebarmargintop*/);
					$(sidebarid).css("left", sidebaroffset.left - $(window).scrollLeft());
					$(sidebarid).css("width", sidebarwidth);
					fixed = 1;
					fixedsidebartop = $(sidebarid).offset().top - sidebarmargintop;
				}
				else if ((mode == 2 && curscrolltop > prevscrolltop && fixedsidebartop > 0 && curscrolltop > fixedsidebartop + h - wh  ) ||
						((mode != 2 || (mode == 2 && fixedsidebartop < 0)) && s >= -sidebartop && sidebartop <= 0)) {
                    // at the bottom of sidebar
					$(sidebarid).css("position", "fixed");
					$(sidebarid).css("top", sidebartop);
					$(sidebarid).css("left", sidebaroffset.left - $(window).scrollLeft());
					$(sidebarid).css("width", sidebarwidth);

					fixed = 1;
					fixedsidebartop = $(sidebarid).offset().top; // TODO: check condition
				}
				else if (mode != 2 || curscrolltop < sidebaroffset.top) {
					$(sidebarid).css("position", "static");
					fixedsidebartop = -1;
					fixed = 0;
				}
				else {
					// continue absolute
				}
				//$('#dbgtext').text(prevscrolltop);
				direction = curscrolltop - prevscrolltop;
				prevscrolltop = curscrolltop;
			}

			function resizefunc() {
				h = $(sidebarid).height();
				ph = $(contentid).height();
				wh = $(window).height();
				prevscrolltop = -1;
				fixedsidebartop = -1;
				fixed = 0;
				direction = 0;

				$(sidebarid).css("position", "static");
				sidebaroffset = $(sidebarid).offset();
				sidebaroffset.top -= sidebarmargintop; // TODO: if sidebaroffset is null
				sidebarwidth = $(sidebarid).width();
				// Use a fixed width because the parent will change.

				sidebartop = wh - h - sidebarmargintop - sidebarmarginbottom;
				if (ph <= h || $(window).width() < swe_params.disable_iflt) {
					/* longer sidebar than the content || narrow window width */
					sidebartop = 1;
					/* special value for no-scroll */
				}
				else if (sidebartop > 0) { /* shorter sidebar than the window */
					sidebartop = 0;
				}
				scrollfunc();
			}

			$(window).scroll(scrollfunc);
			$(window).resize(resizefunc);

			resizefunc();
		}
	}); // ready function
})(jQuery, window, document);
