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

	private static $whitelist = array(
		'logged_in',
		'left_comment'
	);

	public static function set_cookie( $key, $value ) {
		setcookie( 'segment_' . $key . '_' . COOKIEHASH, $value, time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		$_COOKIE[ 'segment_' . $key . '_' . COOKIEHASH ] = $value;
	}

	public static function get_cookie( $key, $value ) {
		return isset( $_COOKIE[ 'segment_' . $key . '_' . COOKIEHASH ] ) && $value === $_COOKIE[ 'segment_' . $key . '_' . COOKIEHASH ];
	}

	public static function unset_cookie( $key = '' ) {

		if ( isset( $_POST['key'] ) && in_array( $_POST['key'], self::$whitelist ) ) {
			$key = sanitize_text_field( $_POST['key'] );
		}

		setcookie( 'segment_' . $key . '_' . COOKIEHASH, '', time() - DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		unset( $_COOKIE[ 'segment_' . $key . '_' . COOKIEHASH ] );

		wp_send_json_success( $key );
	}


}

add_action( 'wp_ajax_segment_unset_cookie'       , array( 'Segment_Cookie', 'unset_cookie' ) );
add_action( 'wp_ajax_nopriv_segment_unset_cookie', array( 'Segment_Cookie', 'unset_cookie' ) );