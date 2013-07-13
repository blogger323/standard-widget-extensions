=== Plugin Name ===
Contributors: blogger323
Donate link: http://en.hetarena.com/donate
Tags: widget, sidebar
Requires at least: 3.2
Tested up to: 3.6
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds some nice features to your standard widgets.

== Description ==

Standard Widget Extensions is extension for WordPress standard themes, Twenty Thirteen/Twenty Twelve/Twenty Eleven/Twenty Ten.
It's also effective for themes which use widgets in a standard manner.

Current version of the plugin offers following two features.
You can individually turn on and off these features.

1. Accordion Widgets
1. Sticky Sidebar

Twenty Twelve/Twenty Eleven users can use this plugin out of the box.
Twenty Thirteen users have to need [some work to use it](http://en.hetarena.com/archives/207 "some work to use it").
Other theme users can adjust class names and/or IDs to suit the theme.
You can find options for the plugin by selecting a menu 'Settings' - 'Standard WE'. 
Detailed description for parameters are in [the plugin homepage](http://en.hetarena.com/standard-widget-extensions#setting "Parameter description").

I would appreciate your feedbacks of settings for your favorite themes.
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
2. Settings

== Changelog ==

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

= 1.1.1 =
* WordPress 3.6 (Twenty Thirteen) compliant.
* Now work with sidebars whose ancestor has the 'absolute' position.
* Difference between the content top and the sidebar top is now to be adjusted.
* Some minor changes.

