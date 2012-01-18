=== Plugin Name ===
Contributors: jongsmith
Donate link: 
Tags: Facebook, events
Requires at least: 3.2.1
Tested up to: 3.3.1
Stable tag: 0.4

A plugin to generate a list of events from a Facebook fan page using a shortcode. 

== Description ==

This plugin generates a list of events from a Facebook fan page. The events list can be put in any post or page that uses shortcodes. 

You must have a Facebook developer account with an application ID and application secret. To request one, please visit [Facebook](https://developers.facebook.com/apps "Facebook developer site") 

To use the plugin, simply include the short-code [fb_event_list appid="" appsecret="" pageid=""] in a post or page. 

== Installation ==

1. Upload `fb_event_list.zip` to the `/wp-content/plugins/` directory and unzip
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[fb_event_list appid="" appsecret="" pageid="" locale=""]` in your post or page
1. Locale should be taken from the list of supported php timezone here: http://www.php.net/manual/en/timezones.php default is Europe/London

== Frequently Asked Questions ==

= How do I obtain an appid and appsecret? =

Please visit the [Facebook Developer site](https://developers.facebook.com/apps)

== Screenshots ==

== Changelog ==

= 0.1 =
* First version released as public beta.

= 0.4 =
* Includes locale updates and fix to remove past events from list

== Upgrade Notice ==

