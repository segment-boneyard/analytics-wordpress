=== Analytics for WordPress — by Segment ===
Contributors: segmentio, JustinSainton
Tags: analytics, web analytics, segment.io, google analytics, kissmetrics, mixpanel, chartbeat, hubspot, marketo, quantcast, tag manager
Requires at least: 3.6
Tested up to: 4.0
Stable tag: 1.0.9
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Analytics for WordPress lets you integrate more than 100 analytics and marketing tools with the flick of a switch.


== Description ==

Analytics for WordPress is the easiest way to integrate analytics and marketing tools into your WordPress site.

Instead of installing each tool individually, just install Segment's WordPress plugin and use Segment to integrate third-party analytics and marketing tools.   Once you're setup, you can swap and add new analytics services with the flick of a switch!

Here's how it works:
* Install the Segment plugin
* Segment will automatically start tracking how people are using your site – what pages they view and the information they provide to you, like their emails
* Go to the Segment control panel, and toggle on any tool you want to try like Google Analytics, KISSmetrics and Facebook Audiences
* Segment will send this data along to each tool
* The service is fully integrated into your site–zero code required!

You can use Segment to try out more than a hundred vendors in a number of categories. Here are just a few of our most popular integrations:
* **Advertising** – AdRoll, Google Adwords, Facebook Audiences, Twitter Ads, Quantcast, AppNexus
* **Analytics** – Google Analytics, KISSmetrics, Mixpanel, Amplitude, Chartbeat, Go Squared
* **CRM** – Salesforce, Zendesk, Gainsight, Frontleaf
* **Email** – Customer.io, Outbound, Vero, Marketo, MailChimp, Hubspot
* **Optimization** – Optimizely, CrazyEgg, Visual Website Optimizer

Get started with Analytics for WordPress today!

== Installation ==

1. Go to the **Plugins > Add New** page in your WordPress admin.
1. Search for "Segment" and install **Analytics for WordPress — by Segment.io**.
1. Click **Activate Plugin**.
1. Go to **Settings > Analytics** and enter your Segment API key. (If you haven't signed up for Segment yet, now's the time!)

That's it! You can now turn on any analytics service with the flick of a switch in the Segment control panel.


== Frequently Asked Questions ==

= What analytics services does it support? =
Tons! Google Analytics, Mixpanel, KISSmetrics, Chartbeat, Clicky, Intercom, CrazyEgg, Customer.io, Gauges, GoSquared, HubSpot, Marketo, Olark, Quantcast, Vero, and more... You can check out [the full list](https://segment.io/integrations).

= What user information does it record automatically? =
We automatically identify users that are logged in to your WordPress site, and record their `name`, `email`, `username`, and `website`, so you don't need to write any special code to handle that yourself. It also identifies commenters if it can.

= Which actions does it record automatically? =
We automatically record the different types of WordPress pages your users can visit. Things like "View About Page", "View Post", "View Author Page", etc. So you don't have to worry about recording them yourself. Just by enabling the plugin you'll already be recording all of those actions.

= Can I turn off the automatic event tracking? =
Yup! You can also turn off specific events, in case you only want a few.

= Can I ignore logged-in users? =
Yup! You can even _just_ ignore Administrators or Editors too.

= I'm a developer, what should I know? =

Lots of new goodies for developers in the 1.0.0 release!

So very much.  The two biggest enhancements for developers are the introduction of the `segment_get_current_user_identify` and `segment_get_current_page_track` filters.  As a developer, you can hook into these filters and add any sort of conditional logic you want to track any event on a page, or customize the user identification system to hook into your own user system as needed.  Super extensible!

Beyond that, we've completely refactored the plugin to use all of the appropriate WordPress APIs (Like the Settings API) and have added a easily extendable abstract class for eCommerce platforms.  On top of all of that, all of our filters, classes, functions and methods are fully documented inline.

Need to add custom settings?  By hooking into our `segment_default_settings` and `segment_settings_core_validation` filters, you can quickly and easily add your own settings to the Segment plugin.


== Screenshots ==


== Changelog ==

= 1.0.6 =
* Bump snippet to version 3.0.0
* Fix for category retrieval bug

= 1.0.5 =
* Add context information to page calls.

= 1.0.4 =
* Fixed identify() method to only be called for logged-in users and commenters.

= 1.0.3 =
* Fixed deployment logic

= 1.0.2 =
* Fixed deployment logic

= 1.0.1 =
* Fixed fatal error when tracking WooCommerce products.

= 1.0.0 =
* Total refactor for WordPress plugin best practices.
* Addition of several helpful filters.
* Now sends category data for posts.
* New options to allow you to track user logins.
* Added eCommerce tracking for WP eCommerce and WooCommerce.
* Added Intercom Secure Mode support.

= 0.5.6 =
* Fixed a bug with non-static methods in newer versions on PHP.

= 0.5.5 =
* Fixed a bug where admins weren't being properly ignored.

= 0.5.3 =
* Fixed a bug that occured in older versions of PHP.

= 0.5.2 =
* Added `'analytics-wordpress'` `library` option.

= 0.5.1 =
* Fixed a bug where the user permission level would be output.

= 0.5.0 =
* Added setting to ignore users by permission level (eg. Administrators).

= 0.4.3 =
* Made our page view events `noninteraction` in Google Analytics.

= 0.4.0 =
* Added settings to turn off automatic event tracking.

= 0.3.3 =
* Fixed a bug that occured on uncommon page types.

= 0.3.2 =
* Fixed a bug that occured when installing straight from WordPress admin.

= 0.3.0 =
* Exposed a global `Analytics` class for custom tracking.

= 0.2.0 =
* Added identifying commenters.
* Added tracking events for different page types.
* Bug fixes.

= 0.1.0 =
* Initial release!
