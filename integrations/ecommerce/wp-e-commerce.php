<?php 

class Segment_Commerce_WPSC extends Segment_Commerce {

	public function init() {

		$this->register_hook( 'segment_get_current_page_track', 'viewed_category'  , 1, $this );
		$this->register_hook( 'segment_get_current_page_track', 'viewed_product'   , 1, $this );
		$this->register_hook( 'wpsc_add_to_cart'              , 'added_to_cart'    , 2, $this );
		$this->register_hook( 'wpsc_remove_item'              , 'removed_from_cart', 2, $this );
		$this->register_hook( 'wpsc_', 'completed_order', $this );
	}
	
	public function viewed_category( $track ) {

	}

	public function viewed_product( $track ) {

	}

	public function added_to_cart( $product, $cart_item ) {

	}

	public function removed_from_cart( $key, $cart ) {

	}

	public function completed_order(  ) {

	}

}

function segment_commerce_wpsc() {
	$commerce = new Segment_Commerce_WPSC();

	return $commerce->init();
}

add_action( 'plugins_loaded', 'segment_commerce_wpsc', 100 );