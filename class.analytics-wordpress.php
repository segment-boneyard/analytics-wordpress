<?php

if ( ! class_exists( 'Analytics_Wordpress' ) ) {
	class Analytics_Wordpress extends Segment_Analytics_WordPress {
		public function __construct() {
			return parent::get_instance();
		}
	}	
}