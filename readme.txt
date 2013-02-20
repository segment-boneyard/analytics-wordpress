=== Analytics for WordPress — by Segment.io ===
Contributors: segmentio
Tags: analytics, web analytics, segment.io, google analytics, kissmetrics, mixpanel, chartbeat, hubspot, marketo, quantcast, tag manager
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: 0.4.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Analytic for WordPress is the easiest way to integrate analytics into your WordPress site.


== Description ==

Analytics for WordPress is the easiest way to integrate analytics into your WordPress site.

By installing Segment.io's WordPress plugin you can add any analytics service to your site without touching any code.

Segment.io lets you send your analytics data to Google Analytics, Mixpanel, KISSmetrics, Chartbeat, and more... without having to integrate with each and every one, saving you time.

Once you're setup, you can swap and add new analytics services with the click of a button!


== Installation ==

1. Go to the **Plugins > Add New*** page in your WordPress admin.
1. Search for "Segment.io" and install **Analytics for WordPress — by Segment.io**.
1. Click **Activate Plugin**.
1. Go to **Settings > Analytics** and enter your Segment.io API key. (If you haven't signed up for Segment.io yet, now's the time!)
1. That's it! You can now turn on any analytics service with the click of a button in the Segment.io interface.


== Frequently Asked Questions ==

= What analytics services does it support? =
Tons! Google Analytics, Mixpanel, KISSmetrics, Chartbeat, Clicky, Intercom, CrazyEgg, Customer.io, Gauges, GoSquared, HubSpot, Marketo, Olark, Quantcast, Vero, and more... You can check out [the full list](https://segment.io/integrations).

= What user information does it record automatically? =
We automatically identify users that are logged in to your WordPress site, and record their `name`, `email`, `username`, and `website`, so you don't need to write any special code to handle that yourself. It also identifies commenters if it can.

= Which actions does it record automatically? =
We automatically record the different types of WordPress pages your users can visit. Things like "View About Page", "View Post", "View Author Page", etc. So you don't have to worry about recording them yourself. Just by enabling the plugin you'll already be recording all of those actions.

= Can I turn off the automatic event tracking? =
Yup! You can also turn off specific events, in case you only want a few.


== Screenshots ==


== Changelog ==

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
