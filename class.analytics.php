<?php

if ( ! class_exists( 'Analytics' ) ) {
	class Analytics extends Segment_Analytics {
		public function __construct() {
			return parent::get_instance();
		}
	}	
}