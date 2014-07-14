<?php

class Segment_Settings {

	public static function general_section_callback() {
		?>
		<p style="max-width: 49em"><?php _e( 'Once you&rsquo;ve saved your API key, you can swap and add integrations right from the Segment.io interface. Any integrations you turn on will be live within 10 minutes. No more touching any code!', 'segment' ); ?></p>
		<?php
	}

	public static function advanced_section_callback() {
		?>
			<p style="max-width: 49em"><?php _e( 'These settings control which events get tracked for you automatically. Most of the time you shouldn&rsquo;t need to mess with these, but just in case you want to:', 'segment' ); ?></p>
		<?php
	}

	public static function api_key_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[api_key]';
	?>
			<input class="regular-text ltr" type="text" name="<?php echo esc_attr( $name ); ?>" id="api_key" value="<?php echo esc_attr( $settings['api_key'] ); ?>" />
			<p class="description"><?php _e( 'You can find your API Write Key in Project Settings > API Keys in your Segment.io Dashboard.', 'segment' ); ?></p>
		<?php

	}

	public static function ignore_user_level_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[ignore_user_level]';
	?>
		<select class="select" name="<?php echo esc_attr( $name ); ?>" id="ignore_user_level">
			<option value="11"<?php selected( 11, $settings['ignore_user_level'] ); ?>><?php _e( 'No One', 'segment' ); ?></option>
			<option value="8" <?php selected( 8, $settings['ignore_user_level'] );  ?>><?php _e( 'Administrators and Up', 'segment' ); ?></option>
			<option value="5" <?php selected( 5, $settings['ignore_user_level'] );  ?>><?php _e( 'Editors and Up', 'segment' ); ?></option>
			<option value="2" <?php selected( 2, $settings['ignore_user_level'] );  ?>><?php _e( 'Authors and Up', 'segment' ); ?></option>
			<option value="1" <?php selected( 1, $settings['ignore_user_level'] );  ?>><?php _e( 'Contributors and Up', 'segment' ); ?></option>
			<option value="0" <?php selected( 0, $settings['ignore_user_level'] );  ?>><?php _e( 'Everyone!', 'segment' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Users of the role you select and higher will be ignored.', 'segment' ); ?></p>
	<?php

	}

	public static function track_posts_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[track_posts]';
	?>
		<label for="track_posts">
			<input name="<?php echo esc_attr( $name ); ?>" type="checkbox" id="track_posts" value="1" <?php checked( 1, $settings['track_posts'] ); ?> />
			<?php _e( 'Automatically track events when your users view Posts.', 'segment' ); ?>
		</label>
		<p class="description"><?php _e( 'These will be "Viewed Post" events. If you use any custom post types we&rsquo;ll track those, too!', 'segment' ); ?></p>

	<?php

	}

	public static function track_pages_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[track_pages]';
	?>
		<label for="track_pages">
			<input name="<?php echo esc_attr( $name ); ?>" type="checkbox" id="track_pages" value="1" <?php checked( 1, $settings['track_pages'] ); ?> />
			<?php _e( 'Automatically track events when your users view Pages.', 'segment' ); ?>
		</label>
		<p class="description"><?php _e( 'These will be "Viewed Home Page" or "Viewed About Page" events for any of the pages you create.', 'segment' ); ?></p>

	<?php

	}

	public static function track_archives_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[track_archives]';
	?>
		<label for="track_archives">
			<input name="<?php echo esc_attr( $name ); ?>" type="checkbox" id="track_archives" value="1" <?php checked( 1, $settings['track_archives'] ); ?> />
			<?php _e( 'Automatically track events when your users view archive pages.', 'segment' ); ?>
		</label>
		<p class="description"><?php _e( 'These will be "Viewed Category Page" or "Viewed Author Page" events.', 'segment' ); ?></p>

	<?php

	}

	public static function track_comments_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[track_comments]';
	?>
		<label for="track_comments">
			<input name="<?php echo esc_attr( $name ); ?>" type="checkbox" id="track_comments" value="1" <?php checked( 1, $settings['track_comments'] ); ?> />
			<?php _e( 'Automatically track events when your comments are made.', 'segment' ); ?>
		</label>
		<p class="description"><?php _e( 'This event will be logged on the subsequent page load after a comment is made.', 'segment' ); ?></p>

	<?php

	}

	public static function track_logins_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[track_logins]';
	?>
		<label for="track_logins">
			<input name="<?php echo esc_attr( $name ); ?>" type="checkbox" id="track_logins" value="1" <?php checked( 1, $settings['track_logins'] ); ?> />
			<?php _e( 'Automatically track events when users log in.', 'segment' ); ?>
		</label>
		<p class="description"><?php _e( 'This event will be logged on the subsequent page load after a user logs in.', 'segment' ); ?></p>

	<?php

	}

	public static function track_login_page_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[track_login_page]';
	?>
		<label for="track_login_page">
			<input name="<?php echo esc_attr( $name ); ?>" type="checkbox" id="track_login_page" value="1" <?php checked( 1, $settings['track_login_page'] ); ?> />
			<?php _e( 'Automatically track events when the login page is viewed.', 'segment' ); ?>
		</label>
	<?php

	}

	public static function use_intercom_secure_mode() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[use_intercom_secure_mode]';
	?>
			<input class="regular-text ltr" type="text" name="<?php echo esc_attr( $name ); ?>" id="use_intercom_secure_mode" value="<?php echo esc_attr( $settings['use_intercom_secure_mode'] ); ?>" />
			<p class="description"><?php _e( 'Enter your Intercom API key here to use Secure Mode.  Your Intercom API key is found in Intercom’s secure mode setup guide.', 'segment' ); ?></p>
		<?php
	}

	public static function track_search_callback() {

		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[track_searches]';
	?>
		<label for="track_searches">
			<input name="<?php echo esc_attr( $name ); ?>" type="checkbox" id="track_searches" value="1" <?php checked( 1, $settings['track_searches'] ); ?> />
			<?php _e( 'Automatically track events when your users view the search results page.', 'segment' ); ?>
		</label>
		<p class="description"><?php _e( 'These will be "Viewed Search Page" events with a &ldquo;query&rdquo; property.', 'segment' ); ?></p>
	<?php

	}

	/**
	 * [exclude_custom_post_types description]
	 *
	 * @todo  If no post types exist, we shouldn't show this setting at all.
	 * @return [type] [description]
	 */
	public static function exclude_custom_post_types() {
		$cpts     = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
		$settings = Segment_Analytics_WordPress::get_instance()->get_settings();
		$name     = Segment_Analytics_WordPress::get_instance()->get_option_name() . '[exclude_custom_post_types][]';

		$settings['exclude_custom_post_types'] = isset( $settings['exclude_custom_post_types'] ) ? $settings['exclude_custom_post_types'] : array();

		foreach ( $cpts as $cpt ) {
			?>
			<label for="exclude_custom_post_types_<?php echo esc_attr( $cpt->name ); ?>">
				<input name="<?php echo esc_attr( $name ); ?>" type="checkbox" id="exclude_custom_post_types_<?php echo esc_attr( $cpt->name ); ?>" value="<?php echo esc_attr( $cpt->name ); ?>" <?php checked( in_array( $cpt->name, (array) $settings['exclude_custom_post_types'] ) ); ?> />
				<?php echo esc_html( $cpt->label ); ?><br />
			</label>
			<?php
		}
		?>
		<p class="description"><?php _e( 'Select which, if any, custom post types to exclude tracking on.  Selecting a post type here will exclude tracking on the single and archive pages for the post type.', 'segment' ); ?></p>
		<?php
	}

	/**
	 * Our core validation routine.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $input Unsanitized array of settings.
	 * @return array $input Validated/Sanitized array of settings.
	 */
	public static function core_validation( $input ) {

		$checkboxes = array(
			'track_posts',
			'track_pages',
			'track_archives',
			'track_comments',
			'track_logins',
			'track_login_page',
			'track_searches',
		);

		foreach ( $checkboxes as $checkbox ) {
			$input[ $checkbox ] = isset( $input[ $checkbox ] ) ? '1' : '0';
		}

		$text_fields = array( 'api_key', 'use_intercom_secure_mode' );

		foreach ( $text_fields as $text ) {
			$input[ $text ] = isset( $input[ $text ] ) ? sanitize_text_field( $input[ $text ] ) : '';
		}

		$int = 'ignore_user_level';

		$input[ $int ] = isset( $input[ $int ] ) ? absint( $input[ $int ] ) : '';



		return apply_filters( 'segment_settings_core_validation', $input );
	}

}