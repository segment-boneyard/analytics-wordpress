<?php 

class Segment_Commerce_Woo extends Segment_Commerce {

	public function init() {

		$this->register_hook( 'segment_get_current_page'      , 'viewed_category'  , 1, $this );
		$this->register_hook( 'segment_get_current_page_track', 'viewed_product'   , 1, $this );
		$this->register_hook( 'segment_get_current_page_track', 'completed_order'  , 1, $this );
		$this->register_hook( 'segment_get_current_page_track', 'added_to_cart'    , 2, $this );
		$this->register_hook( 'segment_get_current_page_track', 'removed_from_cart', 2, $this );

		/* HTTP actions */
		add_action( 'woocommerce_add_to_cart'                   , array( $this, 'add_to_cart' )     , 10, 3 );
		add_action( 'woocommerce_before_cart_item_quantity_zero', array( $this, 'remove_from_cart' ), 10 );
	}
	
	public function viewed_category() {

		$args = func_get_args();
		$page = $args[0];

		if ( is_tax( 'product_cat' ) ) {
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

		if ( is_singular( 'product' ) ) {
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

	public function add_to_cart( $key, $id, $quantity ) {
		
		$items     = WC()->cart->get_cart();
		$cart_item = $items[ $key ];

		Segment_Cookie::set_cookie( 'added_to_cart', json_encode( 
				array( 
					'ID'       => $id, 
					'quantity' => $quantity,
					'name'     => $cart_item['data']->post->post_title,
					'price'    => $cart_item['data']->get_price(),
					'key'      => $key
				)
			) 
		);
	}

	public function added_to_cart() {
		$args = func_get_args();

		$track = $args[0];

		if ( Segment_Cookie::get_cookie( 'added_to_cart' ) ) {

			$items    = WC()->cart->get_cart();
			$product  = json_decode( Segment_Cookie::get_cookie( 'added_to_cart' ) );
			$_product = $items[ $product->key ];

			$item = array(
				'id'       => $product->ID,
				'sku'      => $_product['data']->get_sku(),
				'name'     => $product->name,
				'price'    => $product->price,
				'quantity' => $product->quantity,
				'category' => implode( ', ', wp_list_pluck( wc_get_product_terms( $product->ID, 'product_cat' ), 'name' ) ),
			);

			$track = array(
				'event'      => 'Added Product',
				'properties' => $item,
				'http_event' => 'added_to_cart'
			);

		}

		return $track;
	}

	public function remove_from_cart( $key ) {
		$items     = WC()->cart->get_cart();
		$cart_item = $items[ $key ];

		Segment_Cookie::set_cookie( 'removed_from_cart', json_encode( 
				array( 
					'ID'       => $cart_item->product_id, 
					'quantity' => 0,
					'name'     => $cart_item['data']->post->post_title,
					'price'    => $cart_item['data']->get_price(),
					'key'      => $key
				)
			) 
		);
	}

	public function removed_from_cart() {
		$args = func_get_args();

		$track = $args[0];

		if ( Segment_Cookie::get_cookie( 'removed_from_cart' ) ) {
			$items    = WC()->cart->get_cart();
			$product  = json_decode( Segment_Cookie::get_cookie( 'removed_from_cart' ) );
			$_product = $items[ $product->key ];

			$item = array(
				'id'       => $product->ID,
				'sku'      => $_product['data']->get_sku(),
				'name'     => $product->name,
				'price'    => $product->price,
				'quantity' => 0,
				'category' => implode( ', ', wp_list_pluck( wc_get_product_terms( $product->ID, 'product_cat' ), 'name' ) ),
			);

			$track = array(
				'event'      => 'Removed Product',
				'properties' => $item,
				'http_event' => 'removed_from_cart'
			);

		}

		return $track;
	}

	public function completed_order() {
		$args  = func_get_args();
		$track = $args[0];

		if ( did_action( 'woocommerce_thankyou' ) ) {

			$order_number = get_query_var( 'order-received' );
			
			$order = new WC_Order( $order_number );

			/* Because gateways vary wildly in their usage of the status concept, we check for failure rather than success. */
			if ( 'failed' !== $order->status ) {
				
				$items        = $order->get_items();
				$products     = array();

				foreach ( $items as $item ) {
					$_product = $order->get_product_from_item( $item );
					$product = array(
						'id'       => $item->product_id,
						'sku'      => $_product->get_sku(),
						'name'     => $item['name'],
						'price'    => $item['line_subtotal'],
						'quantity' => $item['qty'],
						'category' => implode( ', ', wp_list_pluck( wc_get_product_terms( $item->product_id, 'product_cat' ), 'name' ) ),
					);

					$products[] = $product;

				}

				$track = array(
					'event'      => 'Completed Order',
					'properties' => array(
						'id'       => $order->get_order_number(),
						'total'    => $order->get_total(),
						'revenue'  => $order->get_total() - ( $order->get_total_shipping() + $order->get_total_tax() ),
						'shipping' => $order->get_total_shipping(),
						'tax'      => $order->get_total_tax(),
						'products' => $products
					)
				);

			}
		}

		return $track;
	}

}

function segment_commerce_woo() {
	$commerce = new Segment_Commerce_Woo();

	return $commerce->init();
}

add_action( 'plugins_loaded', 'segment_commerce_woo', 100 );