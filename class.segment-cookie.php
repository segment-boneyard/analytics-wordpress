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
 */

class Segment_Cookie {

}