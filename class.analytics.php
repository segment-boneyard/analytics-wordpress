<?php

if ( ! class_exists( 'Analytics' ) ) {
	class Analytics extends Segment_IO_Analytics {
		public function __construct() {
			parent::__construct();
		}
	}	
}