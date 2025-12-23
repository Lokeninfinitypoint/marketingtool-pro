=== Ajax Load More: REST API ===
Contributors: dcooney, connekthq
Author: Darren Cooney
Author URI: https://connekthq.com/
Plugin URI: https://connekthq.com/ajax-load-more/extensions/rest-api/
Donate link: https://connekthq.com/donate/
Tags: ajax load more, rest api, endpoints, lazy load, infinite scroll
Requires at least: 4.0 
Tested up to: 6.8
Stable tag: 1.2.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An Ajax Load More extension that adds compatibility for the WP REST API.


== Description ==

The REST API extension will enable compatibility between Ajax Load More and the WP REST API plugin.

Easily access your website data (as JSON) through an HTTP REST API and display the results using the beauty of infinite scrolling with Ajax Load More.

https://connekthq.com/plugins/ajax-load-more/extensions/rest-api/


= How It Works =

The REST API add-on works by routing the standard Ajax Load More admin-ajax.php requests through to API endpoints for data retrieval. The data is returned as JSON, then parsed and displayed using Underscore.js styled templates on the front-end of your website.

Using a simple GET request, a JavaScript Repeater Template and a custom endpoint (/wp-json/ajaxloadmore/posts) developed specifically for Ajax Load More, users are able to access website data and infinite scroll the results using the WP REST API.

**[View Example](https://connekthq.com/plugins/ajax-load-more/examples/rest-api-example/)**

== Frequently Asked Questions ==

= What is a Namespace in an API endpoint? =
Namespaces are the first part of the URL for the endpoint. Namespaces allows for two plugins to add a route of the same name, with different functionality.

Need more info? Read the official documentation on Namespacing.
http://v2.wp-api.org/extending/adding/#namespacing

= I want to create some custom API endpoints, where should I save them? =
You should add your custom endpoints to the functions.php file in your current theme directory.

= Can I use a regular PHP based Repeater Template with this add-on? =
No, all Repeater Templates that are used with the REST API add-on must be coded as a JavaScript template.

= Are all Ajax Load More shortcode parameters available with the REST API add-on? =
Yes! As long as you are using the default /wp-json/ajaxloadmore/posts endpoint all values passed via shortcode will be parsed in your API call.

== Screenshots ==

== Installation ==

= Uploading in WordPress Dashboard =
1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-rest-api.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =
1. Download `ajax-load-more-rest-api.zip`.
2. Extract the `ajax-load-more-rest-api` directory to your computer.
3. Upload the `ajax-load-more-rest-api` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the WP plugin dashboard.

== Changelog ==

= 1.2.6 - June 5, 2025 = 
* NEW: Adding Ajax Load More as a required plugin.
* UPDATE: Code cleanup, readme and WP tested version.

= 1.2.4 - November 11, 2024 = 
* UPDATE: Readme, contributors, WordPress tested version

= 1.2.3 - February 23, 2023 =
* FIX: Fixed up PHP warnign messages found in debug log for default endpoint.

= 1.2.2 - December 30, 2022 =
* FIX: Added fix for saving posts in editor when REST API shortcode in place.
* UPDATE: Updated custo endpoint to rely on core ALM query params.
* UPDATE: Code cleanup.

= 1.2.1 - January 20, 2021 =
* UPDATE - Updated REST API endpoint to include `permissions_callback` which is now required to remove PHP notices/warnings.

= 1.2 - March 14, 2017 =
* NOTICE - Moved extension to .org repo.
* UPDATE - Removed REST API plugin Requirement if core WP 4.7 or greater is running.

= 1.1 - September 5, 2016 =
* MILESTONE - Must update core Ajax Load More to 2.12.0 when updating this add-on.
* UPDATE - Updated endpoint + js function to hold new return data to match core ALM for posts and total posts

= 1.0 - June 5, 2016=
* Initial Release.



== Upgrade Notice ==
* None
