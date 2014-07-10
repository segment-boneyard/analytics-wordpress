<?php

/**
 * This exists solely for the purpose of backwards compatibility.  We do _not_ recommend using the Analytics class.
 * If you do, you do so at your own risk, a risk primarily of namespace clashes.
 */
if ( ! class_exists( 'Analytics' ) ) {
	class Analytics extends Segment_Analytics {
		public function __construct() {
			return parent::get_instance();
		}
	}
}