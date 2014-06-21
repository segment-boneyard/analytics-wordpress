<?php 

class Segment_Commerce_WPSC extends Segment_Commerce {

	public function init() {

		$this->register_hook( 'segment_get_current_page'      , 'viewed_category'  , 1, $this );
		$this->register_hook( 'segment_get_current_page_track', 'viewed_product'   , 1, $this );
		$this->register_hook( 'wpsc_add_to_cart'              , 'added_to_cart'    , 2, $this );
		$this->register_hook( 'wpsc_remove_item'              , 'removed_from_cart', 2, $this );
		$this->register_hook( 'segment_get_current_page_track', 'completed_order'  , 1, $this );
	}
	
	public function viewed_category() {

		$args = func_get_args();
		$page = $args[0];

		if ( is_tax( 'wpsc_product_category' ) ) {
				$page = array(
					'page'       => single_term_title( '', false ),
					'properties' => array()
				);
		}

		return $page;
	}

	public function viewed_product() {

		$args  = func_get_args();
		$track = $args[0];

		if ( is_singular( 'wpsc-product' ) ) {
				$track = array(
					'event'      => 'Viewed Product',
					'properties' => array(
						'id'       => get_the_ID(),
						'sku'      => wpsc_product_sku(),
						'name'     => wpsc_the_product_title(),
						'price'    => wpsc_string_to_float( wpsc_the_product_price() ),
						'category' => implode( ', ', wp_list_pluck( wpsc_get_product_terms( get_the_ID(), 'wpsc_product_category' ), 'name' ) ),
					)
				);
		}
		
		return $track;
	}

	public function added_to_cart() {

	}

	public function removed_from_cart() {
		$args = func_get_args();
		$key  = $args[0];
		$cart = $args[1];

	}

	public function completed_order() {
		$args  = func_get_args();
		$track = $args[0];

		if ( did_action( 'wpsc_transaction_results_shutdown' ) && isset( $_GET['sessionid'] ) ) {

			$log = new WPSC_Purchase_Log( $_GET['sessionid'], 'sessionid' );

			/* We like checking is_order_received(), as that's what the manual payment gateway uses. */
			if ( $log->is_transaction_completed() || $log->is_order_received() ) {
				
				$gateway_data = $log->get_gateway_data();
				$items        = $log->get_cart_contents();
				$products     = array();

				foreach ( $items as $item ) {

					$product = array(
						'id'       => $item->prodid,
						'sku'      => wpsc_product_sku( $item->prodid ),
						'name'     => $item->name,
						'price'    => $item->price,
						'quantity' => $item->quantity,
						'category' => implode( ', ', wp_list_pluck( wpsc_get_product_terms( $item->prodid, 'wpsc_product_category' ), 'name' ) ),
					);

					$products[] = $product;

				}

				$track = array(
					'event'      => 'Completed Order',
					'properties' => array(
						'id'       => $log->get( 'id' )        ,
						'total'    => $log->get( 'totalprice' ),
						'revenue'  => $gateway_data['subtotal'],
						'shipping' => $gateway_data['shipping'],
						'tax'      => $gateway_data['tax'],
						'products' => $products
					)
				);

			}
		}

		return $track;
	}

}

function segment_commerce_wpsc() {
	$commerce = new Segment_Commerce_WPSC();

	return $commerce->init();
}

add_action( 'plugins_loaded', 'segment_commerce_wpsc', 100 );