<?php

/**
 * We have an interesting problem we attempt to solve with Segment_Cookie.  A few facts, please.
 * 
 * 1) It's preferred to use the client-side JS API to phone home to Segment.io.
 * 2) We need a decent way to output this tracking script based on a server-side event.
 * 3) We can't check `did_action()` on this event, as often times, we've been redirected after the fact.
 * 
 * Ergo, Segment_Cookie.  A simple API, provided to do one simple task: Track server-side events, on the client-side.
 * Two great examples: `wp_login` and `wp_insert_comment`. 
 * 
 * You'll swiftly notice that we essentially log two cookies per event...the hash/payload and the tracker.  
 * The hash is what we'll actually check against on the event to output the tracking script.  \
 * But we cannot unset the cookie on the same event, so on the next page load, we check for the tracker cookie.
 * This cookie is set after the tracking has occurred.  If it's set, we unset both cookies and are good to go. 
 * 
 */

class Segment_Cookie {

	public static function set_cookie(  ) {

	}

	public static function get_cookie() {

	}

}