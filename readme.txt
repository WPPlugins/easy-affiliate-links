=== Easy Affiliate Links ===
Contributors: BrechtVds
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QG7KZMGFU325Y
Tags: affiliate, affiliate links, cloak, cloaking, shortlink, seo, redirect, pretty links, link masking, clicks
Requires at least: 3.5
Tested up to: 4.7
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily manage and cloak all your affiliate links.

== Description ==

Easy Affiliate Links helps you manage all the affiliate links on your website.

See the plugin in action and find all documentation on [our website](http://bootstrapped.ventures/easy-affiliate-links/)!

Current features:

*   Create **shortlinks** to cloak your affiliate links
*   Easily access your links in the **visual and html editor**
*   Assign **categories** to your links
*   Tracking of monthly and lifetime **clicks**
*   **Import affiliate links** from XML
*   Ability to **export your links** to XML

Premium add-ons:

*   Get valuable insights with [Easy Affiliate Links - Statistics](http://bootstrapped.ventures/downloads/easy-affiliate-links-statistics/)

Upcoming add-ons:

*   Geospecific links
*   A/B testing for link text
*   Broken links checker

This plugin is under active development, so just let us know if you have any requests at all!

== Installation ==

1. Upload the `easy-affiliate-links` directory (directory included) to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

= How can I add affiliate links? =

Just click on the blue URL icon in the Visual Editor or on "easy affiliate link" in the HTML Editor.

= Can I track affiliate links in Google Analytics? =

We recommend using the free [Google Analytics by Yoast](https://wordpress.org/plugins/google-analytics-for-wordpress/) plugin for this. You'll have to enable "Track outbound click and downloads" on its settings page and set the slug you're using for your affiliate links in the advanced "Set path for internal links to track as outbound links" setting as well.

= Where can I find more information? =

[http://bootstrapped.ventures/easy-affiliate-links/](http://bootstrapped.ventures/easy-affiliate-links/)

== Screenshots ==

1. Manage all your affiliate links in one place with click statistics
2. Full control over each affiliate link's options
3. Easily add affiliate links to any post or page by clicking on the icon
4. Just search and select the link you want to use to insert it
5. Affiliate links are indicated with a blue line and can be edited with a simple click

== Changelog ==

= 2.4.0= 
* Improvement: Add class to affiliate link output
* Improvement: Spacing on manage page to prevent accidental clicks
* Fix: Don't add modal content to WordFence pages

= 2.3.0 =
* Feature: Clean up clicks and exclude IPs
* Improvement: Stay on correct page after reloading datatable
* Improvement: More details on Statistics page
* Improvement: Better compatibility with other plugins for the modal
* Fix: Broken JavaScript dependencies

= 2.2.0 =
* Feature: View details of last click data
* Improvement: Better check for crawlers
* Fix: Datatables errors on some other plugin pages
* Fix: Prevent datatables errors from showing up as alert
* Fix: EAFL Button when visual editor is disabled

= 2.1.0 =
* Feature: Order by lifetime clicks on manage page
* Improvement: Custom database for link clicks
* Improvement: Max width for the description in the overview
* Fix: Escaping for XML export
* Fix: Wordfence compatibility issue

= 2.0.1 =
* Improvement: Affiliate links show up when adding links
* Improvement: Use selected text as default option for affiliate link
* Improvement: Use selected text when creating new link
* Improvement: Automatically flush permalinks
* Fix: Problem with URLs containing encoded characters

= 2.0.0 =
* Plugin built from the ground up with cleaner and leaner code
* Performance improvements
* Support for upcoming add-ons

= 1.4 =
* Feature: Copy shortlink to clipboard button
* Improvement: Use dashicon instead of image in menu
* Fix: Prevent issue with other plugins or themes using Browser
* Fix: PHP7 warnings caused by the VafPress library

= 1.3 =
* Improvement: Streamline link selection process
* Improvement: Setting to disable click counter
* Improvement: Setting to decide what to show in the link column on the admin overview page
* Improvement: Ability to filter link values via plugin hook before saving

= 1.2 =
* Feature: Import affiliate links from XML
* Feature: Export affiliate links to XML
* Feature: Private description field for affiliate links
* Improvement: Ability to reset click counters
* Improvement: selected text in Visual Editor available as text variant
* Fix: Cannot edit shortcode preview in Visual Editor anymore

= 1.1 =
* Feature: Use custom link text in the shortcode
* Feature: Define multiple link text variants
* Setting: Set required capability for seeing the shortcode button
* Fix: Exclude affiliate links from search to prevent redirect
* Fix: A few unwanted PHP notices
* Fix: Cache update after saving an affiliate link
* Fix: @ sign in mailto urls
* Fix: Removed trailing slash in URLs for consistency

= 1.0 =
* Feature: Edit an affiliate link by clicking on it in the visual editor
* Feature: Tracking of monthly and lifetime clicks

= 0.0.1 =
* Very first version of this plugin

== Upgrade notice ==

= 2.4.0 =
Update to prevent WordFence compatibility issues

= 2.3.0 =
Update required to use our new Statistics add-on

= 2.2.0 =
Update to fix potential issues with other plugins

= 2.1.0 =
Update for some performance improvements

= 2.0.1 =
Minor update with improvements to make sure the plugin works like the previous version

= 2.0.0 =
Update for a performance boost, especially when using lots of affiliate links

= 1.4 =
Update for an easier way to copy the shortlink

= 1.3 =
Update for a few nice improvements and WordPress 4.5 compatibility

= 1.2 =
Update for plenty of new features

= 1.1 =
Update recommend, some new features and bug fixes

= 1.0 =
Update for some new features

= 0.0.1 =
First version, no upgrades needed.