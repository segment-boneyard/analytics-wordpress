<?php

/**
 * Filters the .identify() object if we have an authenticated user and Intercom API key is set.
 *
 * @since  1.0.0
 *
 * @param  array|bool $identify   Array of user identity, if one exists.  If none exists, returns false.
 * @param  array      $settings   Array of settings.
 *
 * @return array                  Modified array of user identity, passed to .identify() API.
 */
function segment_intercom_integration( $identify, $settings ) {
	$user_email = $identify['user_id'];

	if ( is_email( $user_email ) && ! empty( $settings['use_intercom_secure_mode'] ) ) {

		$identify['options'] = isset( $identify['options'] ) ? $identify['options'] : array();

		$identify['options']['Intercom'] = array(
			'userHash' => hash( 'sha256', $settings['use_intercom_secure_mode'] . $user_email )
		);
	}

	return $identify;
}

add_filter( 'segment_get_current_user_identify', 'segment_intercom_integration', 10, 2 );