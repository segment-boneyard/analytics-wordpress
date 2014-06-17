<?php

if ( ! class_exists( 'Analytics_Wordpress' ) ) {
	class Analytics_Wordpress extends Segment_IO_Analytics_WordPress {
		public function __construct() {
			parent::__construct();
		}
	}	
}