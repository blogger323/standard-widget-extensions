=== Plugin Name ===
Contributors: blogger323
Donate link: http://en.hetarena.com/donate
Tags: widget, sidebar
Requires at least: 3.6
Tested up to: 4.1
Stable tag: 1.7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds Sticky Sidebar and Accordion Widget features to your WordPress sites.

== Description ==

Standard Widget Extensions is extension for WordPress themes.
It's effective for themes which use widgets in a standard manner.

Current version of the plugin offers following two features.
You can individually turn on and off these features.

1. Accordion Widgets
1. Sticky Sidebar

You can use this plugin easily with default WordPress themes (Twenty Fourteen, Twenty Thirteen, ...). [Check setting information](http://en.hetarena.com/standard-widget-extensions/settings-for-standard-themes).

Other theme users can adjust class names and/or IDs to suit the theme.
You can find options for the plugin by selecting a menu 'Settings' - 'Standard WE'. 
Detailed description for parameters are in [the plugin homepage](http://en.hetarena.com/standard-widget-extensions#setting "Parameter description").

I would appreciate your feedback of settings for your favorite themes.
Or if you don't know how to set IDs/classes for your theme, don't hesitate to ask it in the support forum.

Related Links:

* [Plugin Homepage](http://en.hetarena.com/standard-widget-extensions "Plugin Homepage")
* [Parameter description](http://en.hetarena.com/standard-widget-extensions#setting "Parameter description")
* [Japanese Homepage](http://hetarena.com/standard-widget-extensions "Japanese Homepage")

== Installation ==

You can install Standard Widget Extensions in a standard manner.

1. Go to Plugins - Add New page of your WordPress and install Standard Widget Extensions plugin.
1. Or unzip 'standard-widget-extensions.*.zip' to your '/wp-content/plugins/' directory. The asterisk is a version number.

Don't forget to activate the plugin before use it.

== Frequently Asked Questions ==

= Sidebar drops to the bottom while resizing =
The sidebar width is fixed when you load the page if you enable sticky widget feature. 
Due to technical reason, it's not dynamically changed. 
So you may need reloading the page to get proper layout after resizing.

= IE7 seems to forget widget accordion status =
IE7 cannot memory widget accordion status. So all widgets are initially collapsed just after loading pages.

= IE6 seems blah, blah, blah.... =
Please tell your audience to upgrade browser.

== Screenshots ==

1. Widgets in Twenty Twelve/Twenty Eleven with custom icon/Twenty Ten
2. Global setting
3. Accordion Widgets setting
4. Sticky Sidebar setting

== Changelog ==

= 1.7.4 - Mar 5, 2015 =
* Possibly duplicate handlers in Ajax theme.
* Major version up is coming, hopefully soon :-) Stay tuned!

= 1.7.3 - Feb 5, 2015 =
* Toolbar adjustment for logged in users.
* Major version up is coming soon. Stay tuned!

= 1.7.2 - Dec 3, 2014 =
* [Fixed] swapped plus/minus sign for Accordion Widget

= 1.7.1 - Dec 2, 2014 =
* Accordion Widget Improvement
    * Now you can style headings with .hm-swe-expanded/.hm-swe-collapsed classes.
    * Better Ajax support. Working with such as YITH WooCommerce Ajax Navigation. Check [this entry.](http://en.hetarena.com/archives/350)
* Better Admin Screen
    * Tab interface
* Following options are withdrawn in this release.
    * Include Default CSS for Icons
    * Reload-me Warning After Resizing

= 1.6 - Sep 7, 2014 =
* A new option to enable Sticky Sidebar depending on css 'float' attribute. When you enable this option, your sidebar is fixed only when its float attribute is 'left' or 'right'. And you don't need to enter widths to enable/disable sidebars for responsive web design themes.
* A handler to re-initialize Accordion Widgets after refreshing by Ajax.
* WordPress 4.0 compatible. Any versions prior to 3.6 are no more supported.

= 1.5.2 - Jul 17, 2014 =
* [Fixed] Multiple Recalc timers occurred while resizing.
* Better responsive web design support. You don't need proportional settings anymore.
* Now Reload-me warning is not displayed while height resizing to avoid being annoying while in-page searching in Firefox. (But you wouldn't need this feature because of better responsive web design support.)

= 1.5 - Jul 12, 2014 =
* A option for floating header-space adjustment.
* Reload-me warning while resizing.
* Initial state of accordions can be reserved by CSS styling.
* Recalc Count for repeated auto size adjustment.
* Improved behavior of sticky sidebars.

= 1.4 - Jan 6, 2014 =
* Support for the 2nd sidebar.
* Support for sidebars using z-index or padding. Now working with Twenty Fourteen.

= 1.3 - Nov 5, 2013 =
* A new option, Proportional Sidebar, is introduced for better support of responsive web design.
* [Fixed] Support sidebars whose left margin is not zero.
* WordPress 3.7 compliant


= 1.2 - Aug 25, 2013 =
* New options: 'Recalc Timer', 'Slide Duration'.
* Now expert option's hidden/displayed status is saved.

= 1.1.1 - Jul 13, 2013 =
* WordPress 3.6 (Twenty Thirteen) compliant.
* Now work with sidebars whose ancestor has the 'absolute' position.
* Difference between the content top and the sidebar top is now to be adjusted.
* Some minor changes.

= 1.1 - Jun 12, 2013 = 
* Quick Switchback Mode
* Single Expansion Mode
* Ignore Footer Option
* Custom Heading/Widget Selector Option

= 1.0 - Mar 17, 2013 =
* Generating a style tag on the fly. It's convenient when you use Accordion Widget feature for non-standard themes. If you don't like this behavior, disable 'Include Default CSS' from the admin page. Existing users who are using a non-standard theme should be careful for this change.
* Support for a sidebar which has multiple widget-areas and only some of them to be collapsible.
* I18nized. Japanese resource included.
* To reduce the package size, screenshots become not included in the zip file.
* Code for the admin page refactored.
* Now the version number sounds formal ;-)

= 0.8.3 - Dec 18, 2012 =
* remove anonymous function for PHP under 5.3. No Need to update for users who have PHP 5.3 or 5.4.

= 0.8.2 - Dec 1, 2012 =
* Applied to horizontal scrolling.
* Added a screenshot of setting admin page.

= 0.8 - Dec 1, 2012 =
* The first version introduced in wordpress.org repository.

== Upgrade Notice ==

= 1.7.4 =
Possibly duplicate handlers in Ajax theme.
