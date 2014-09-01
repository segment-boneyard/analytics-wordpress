<?php

class Segment_Analytics_WordPress_Test extends WP_UnitTestCase {
	protected $object;

	public function setUp() {
		parent::setUp();
		$this->object = Segment_Analytics_WordPress::get_instance();
	}

	public function tearDown() {
		parent::tearDown();
		wp_set_current_user( 0 );
	}

	public function test_segment_instance() {
		$this->assertClassHasStaticAttribute( 'instance', 'Segment_Analytics_WordPress' );
	}

	public function test_includes() {
		$this->assertFileExists( SEG_FILE_PATH . '/includes/class.segment-settings.php' );
		$this->assertFileExists( SEG_FILE_PATH . '/includes/class.segment-cookie.php' );
		$this->assertFileExists( SEG_FILE_PATH . '/integrations/ecommerce.php' );
		$this->assertFileExists( SEG_FILE_PATH . '/integrations/intercom.php' );
	}

	public function test_admin_actions() {

		$this->assertEquals( has_action( 'admin_menu'          , array( $this->object, 'admin_menu' ) ), 10 );
		$this->assertEquals( has_filter( 'plugin_action_links' , array( $this->object, 'plugin_action_links' ) ), 10 );
		$this->assertEquals( has_filter( 'plugin_row_meta'     , array( $this->object, 'plugin_row_meta' ) ), 10 );
		$this->assertEquals( has_action( 'admin_init'          , array( $this->object, 'register_settings' ) ), 10 );
	}

	public function test_frontend_hooks() {

		$this->assertEquals( has_filter( 'wp_head'     , array( $this->object, 'wp_head' ) )  , 9 );
		$this->assertEquals( has_filter( 'admin_head'  , array( $this->object, 'wp_head' ) )  , 9 );
		$this->assertEquals( has_filter( 'login_head'  , array( $this->object, 'wp_head' ) )  , 9 );
		$this->assertEquals( has_filter( 'wp_footer'   , array( $this->object, 'wp_footer' ) ), 9 );
		$this->assertEquals( has_filter( 'login_footer', array( $this->object, 'wp_footer' ) ), 9 );
		$this->assertEquals( has_filter( 'admin_footer', array( $this->object, 'wp_footer' ) ), 9 );
		$this->assertEquals( has_filter( 'wp_insert_comment', array( $this->object, 'insert_comment' ) ), 9 );
		$this->assertEquals( has_filter( 'wp_login'         , array( $this->object, 'login_event' ) )   , 9 );
		$this->assertEquals( has_filter( 'user_register'    , array( $this->object, 'user_register' ) ) , 9 );
	}

	public function test_ignore_user_level() {

		$ignore_user_level = array(
			'0'  => 'subscriber',
			'1'  => 'contributor',
			'2'  => 'author',
			'5'  => 'editor',
			'8'  => 'administrator'
		);

		foreach ( $ignore_user_level as $level => $role ) {

			wp_set_current_user( $this->factory->user->create( array( 'role' => $role ) ) );

			$this->assertLessThanOrEqual( wp_get_current_user()->user_level, $level );
		}
	}

	public function test_register_settings_is_setting_registered() {
		global $new_whitelist_options;

		$this->object->register_settings();

		$plugin = $this->object;
		$slug   = $plugin::SLUG;

		$this->assertArrayHasKey( $slug, $new_whitelist_options );

		$name = $new_whitelist_options[ $slug ];

		$this->assertContains( $this->object->get_option_name(), $name );
	}

	public function test_register_settings_default_settings_sections_and_fields_exist() {
		global $wp_settings_sections, $wp_settings_fields;

		$plugin = $this->object;
		$slug   = $plugin::SLUG;

		$plugin->register_settings();

		$this->assertarrayHasKey( $slug, $wp_settings_sections );
		$this->assertarrayHasKey( $slug, $wp_settings_fields );

		$settings = $this->object->_get_default_settings();

		foreach ( $settings as $section_name => $section ) {

			$this->assertArrayHasKey( $section_name, $wp_settings_sections[ $slug ] );
			$this->assertTrue( is_array( $wp_settings_sections[ $slug ][ $section_name ] ) );
			$this->assertEquals( $wp_settings_sections[ $slug ][ $section_name ]['id'], $section_name );
			$this->assertEquals( $wp_settings_sections[ $slug ][ $section_name ]['title'], $section['title'] );
			$this->assertEquals( $wp_settings_sections[ $slug ][ $section_name ]['callback'], $section['callback'] );

		 	foreach ( $section['fields'] as $field ) {

				$this->assertArrayHasKey( $section_name, $wp_settings_fields[ $slug ] );
				$this->assertTrue( is_array( $wp_settings_fields[ $slug ][ $section_name ] ) );

				$this->assertArrayHasKey( $field['name'], $wp_settings_fields[ $slug ][ $section_name ] );
				$this->assertTrue( is_array( $wp_settings_fields[ $slug ][ $section_name ][ $field['name'] ] ) );

				$this->assertEquals( $wp_settings_fields[ $slug ][ $section_name ][ $field['name'] ]['id'], $field['name'] );
				$this->assertEquals( $wp_settings_fields[ $slug ][ $section_name ][ $field['name'] ]['title'], $field['title'] );
				$this->assertEquals( $wp_settings_fields[ $slug ][ $section_name ][ $field['name'] ]['callback'], $field['callback'] );

		 	}
		}
	}

	public function test_register_settings_remove_default_settings() {

		add_filter( 'segment_default_settings', function( $settings ) {
			unset( $settings['general'] );

			return $settings;
		} );

		$this->assertArrayNotHasKey( 'general', $this->object->_get_default_settings() );
	}

	public function test_register_settings_add_default_settings() {

		add_filter( 'segment_default_settings', function( $settings ) {

			$settings['third-party'] = array(
				'title'    => '',
				'callback' => '',
				'fields'   => array()
			);

			return $settings;
		} );

		$this->assertArrayHasKey( 'third-party', $this->object->_get_default_settings() );
	}

	public function test_logged_out_user_does_not_identify() {
		wp_set_current_user( 0 );
		$this->assertFalse( $this->object->get_current_user_identify() );
	}

	public function test_logged_in_user_identify() {
		wp_set_current_user( $this->factory->user->create( array( 'role' => 'subscriber' ) ) );
		$this->assertNotEmpty( $this->object->get_current_user_identify() );
	}

}