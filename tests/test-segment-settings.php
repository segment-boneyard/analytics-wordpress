<?php
class Segment_Settings_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
		wp_set_current_user( 0 );
	}

	public function test_core_validation_checkboxes() {

		$input = array(
			'track_posts'    => 'on',
			'track_pages'    => 'on',
			'track_archives' => 'on',
			'track_comments' => 'on',
			'track_logins'   => 'on'
		);

		$expected = array(
			'track_posts'      => '1',
			'track_pages'      => '1',
			'track_archives'   => '1',
			'track_comments'   => '1',
			'track_logins'     => '1',
			'track_login_page' => '0',
			'track_searches'   => '0',
		);

		$output = Segment_Settings::core_validation( $input );

		foreach ( $output as $name => $value ) {
			if ( isset( $expected[ $name ] ) ) {
				$this->assertEquals(
					$expected[ $name ],
					$output[ $name ]
				);
			}
		}

	}

	public function test_core_validation_text_fields() {

		$input = array(
			'api_key'                  => '		9sdfks929203',
			'use_intercom_secure_mode' => '  yetanotherapikey  		<p></p><script>alert("XSS");</script>'
		);

		$expected = array(
			'api_key'                  => '9sdfks929203',
			'use_intercom_secure_mode' => 'yetanotherapikey'
		);

		$output = Segment_Settings::core_validation( $input );

		foreach ( $output as $name => $value ) {
			if ( isset( $expected[ $name ] ) ) {
				$this->assertEquals(
					$expected[ $name ],
					$output[ $name ]
				);
			}
		}

	}

	public function test_core_validation_integers() {
		$input = array(
			'ignore_user_level' => '1'
		);

		$expected = array(
			'ignore_user_level' => 1
		);

		$output = Segment_Settings::core_validation( $input );

		foreach ( $output as $name => $value ) {
			if ( isset( $expected[ $name ] ) ) {
				$this->assertEquals(
					$expected[ $name ],
					$output[ $name ]
				);
			}
		}

	}

	public function test_core_validation_filter() {

		add_filter( 'segment_settings_core_validation', function( $input ) {

			if ( isset( $input['custom_input_validation'] ) ) {

				/* Random validation routine: Append a string to input */
				$input['custom_input_validation'] .= " when you're part of a team.";
			}

			return $input;
		} );

		$input = array(
			'custom_input_validation' => 'Everything is awesome! Everythng is cool'
		);

		$expected = array(
			'custom_input_validation' => "Everything is awesome! Everythng is cool when you're part of a team."
		);

		$output = Segment_Settings::core_validation( $input );

		foreach ( $output as $name => $value ) {
			if ( isset( $expected[ $name ] ) ) {
				$this->assertEquals(
					$expected[ $name ],
					$output[ $name ]
				);
			}
		}

	}

}
