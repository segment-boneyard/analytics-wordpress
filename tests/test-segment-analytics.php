<?php

class Segment_Analytics_Test extends WP_UnitTestCase {
	protected $object;

	public function setUp() {
		parent::setUp();
		$this->object = Segment_Analytics::get_instance();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function test_segment_instance() {
		$this->assertClassHasStaticAttribute( 'instance', 'Segment_Analytics' );
	}

	/**
	 * @covers Segment_Analytics::setup_constants
	 */
	public function test_constants() {

		// Plugin File Path
		$path = str_replace( "/tests", '', dirname( __FILE__ ) );
		$this->assertSame( SEG_FILE_PATH, $path );

		// Plugin Folder
		$path = str_replace( "/tests", '', dirname( plugin_basename( __FILE__ ) ) );
		$this->assertSame( SEG_FOLDER, $path );

		// Plugin Root File
		$path = str_replace( "/tests", '', plugins_url( '', __FILE__ ) );
		$this->assertSame( SEG_URL, $path );

	}

}

