
  jQuery(document).ready(function() {
    var cook = null;
    var contentid = "#" + swe_params.maincol_id;
    var sidebarid = "#" + swe_params.sidebar_id;
    var widget    = '.' + swe_params.widget_class;

    if (swe_params.accordion_widget) {
      if (typeof JSON !== "undefined") {
        jQuery.cookie.json = true;
        cook = jQuery.cookie('hm_swe');
      }

      jQuery(sidebarid + ' ' + widget + ' h3').hover(
        function() {
          jQuery(this).css("cursor", "pointer");
        },
        function() {
          jQuery(this).css("cursor", "default");
        }
      );

      jQuery(sidebarid + ' ' + widget).each(function() {
        if (cook && cook[jQuery(this).attr('id')] == "t") {
          jQuery(this).children('h3').next().show();
          if (swe_params.heading_marker) {
            jQuery(this).children('h3').css('background', swe_params.buttonminusurl + " no-repeat left center");
          }
        }
        else {
          jQuery(this).children('h3').next().hide();
          if (swe_params.heading_marker) {
            jQuery(this).children('h3').css('background', swe_params.buttonplusurl + " no-repeat left center");
          }
        }
      });

      jQuery(sidebarid + ' ' + widget + ' h3').click(function() {
        var c = jQuery(this).next();
        if (c) {
          if (c.is(":hidden")) {
            c.slideDown(set_widget_status);
            if (swe_params.heading_marker) {
              jQuery(this).css('background', swe_params.buttonminusurl + " no-repeat left center");
            }
          }
          else {
            c.slideUp(set_widget_status);
            if (swe_params.heading_marker) {
              jQuery(this).css('background', swe_params.buttonplusurl + " no-repeat left center");
            }
          }
        }
      });

      function set_widget_status() {
        if (typeof JSON !== "undefined") {
          var c2 = {};
          jQuery(sidebarid + ' ' + widget + ' h3').each(function() {
            if (jQuery(this).next().is(':visible')) {
              c2[jQuery(this).parent().attr('id')] = "t";
            }
          });
          jQuery.cookie('hm_swe', c2, { path: '/' });
        }
        if (typeof resizefunc === 'function') {
          resizefunc(); /* because the height of the sidebar has changed. */
        }
      }

    }

    if (swe_params.scroll_stop && jQuery(sidebarid) && jQuery(contentid)) {
      var h, ph, wh, sidebaroffset, sidebarwidth, sidebartop;
      var sidebarmargintop    = parseInt(jQuery(sidebarid).css('margin-top'),  10);
      var sidebarmarginbottom = parseInt(jQuery(sidebarid).css('margin-bottom'), 10);

      function scrollfunc() {
        if (sidebartop == 1) {
          jQuery(sidebarid).css("position", "static");
          return;
        }
        var s = jQuery(window).scrollTop() - sidebaroffset.top;

        if (s - sidebarmargintop - sidebarmarginbottom >= ph - wh && sidebartop < 0) {
          jQuery(sidebarid).css("position", "absolute");
          jQuery(sidebarid).css("top", sidebaroffset.top + ph - h);
          jQuery(sidebarid).css("left", sidebaroffset.left);
          jQuery(sidebarid).css("width", sidebarwidth);
        }
        else if (sidebartop == 0 && s >= ph - h) {
          jQuery(sidebarid).css("position", "absolute");
          jQuery(sidebarid).css("top", sidebaroffset.top + ph - h);
          jQuery(sidebarid).css("left", sidebaroffset.left);
          jQuery(sidebarid).css("width", sidebarwidth);
        }
        else if (s >= - sidebartop && sidebartop <= 0) {
          jQuery(sidebarid).css("position", "fixed");
          jQuery(sidebarid).css("top", sidebartop);
          jQuery(sidebarid).css("left", sidebaroffset.left - jQuery(window).scrollLeft());
          jQuery(sidebarid).css("width", sidebarwidth);
        }
        else {
          jQuery(sidebarid).css("position", "static");
        }
      }

      function resizefunc() {
        h  = jQuery(sidebarid).height();
        ph = jQuery(contentid).height();
        wh = jQuery(window).height()

        jQuery(sidebarid).css("position", "static");
        sidebaroffset = jQuery(sidebarid).offset();
        sidebaroffset.top -= sidebarmargintop;
        sidebarwidth = jQuery(sidebarid).width();
        // Use a fixed width because the parent will change.

        sidebartop = wh - h  - sidebarmargintop - sidebarmarginbottom;
        if (ph <= h || jQuery(window).width() < swe_params.disable_iflt) {
        /* longer sidebar than the content || narrow window width */
          sidebartop = 1; /* special value for no-scroll */
        }
        else if (sidebartop > 0) { /* shorter sidebar than the window */
          sidebartop = 0;
        }
        scrollfunc(); 
      }

      jQuery(window).scroll(scrollfunc);
      jQuery(window).resize(resizefunc);

      resizefunc();
    }
  }); // ready function

