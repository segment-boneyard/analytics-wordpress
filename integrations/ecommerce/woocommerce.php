<?php

class Segment_Commerce_Woo extends Segment_Commerce {

	/**
	 * Init method registers two types of hooks: Standard hooks, and those fired in-between page loads.
	 *
	 * For all our events, we hook into either `segment_get_current_page` or `segment_get_current_page_track`
	 * depending on the API we want to use.
	 *
	 * For events that occur between page loads, we hook into the appropriate action and set a Segment_Cookie
	 * instance to check on the next page load.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 */
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

	/**
	 * Adds category name to analytics.page()
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses  func_get_args() Because our abstract class doesn't know how many parameters are passed to each hook
	 *                        for each different platform, we use func_get_args().
	 *
	 * @return array Filtered array of name and properties for analytics.page().
	 */
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

	/**
	 * Adds product properties to analytics.track() when product is viewed.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses  func_get_args() Because our abstract class doesn't know how many parameters are passed to each hook
	 *                        for each different platform, we use func_get_args().
	 *
	 * @return array Filtered array of name and properties for analytics.track().
	 */
	public function viewed_product() {

		$args  = func_get_args();
		$track = $args[0];

		if ( is_singular( 'product' ) ) {

				$product = get_product( get_queried_object_id() );

				$track = array(
					'event'      => __( 'Viewed Product', 'segment' ),
					'properties' => array(
						'id'       => $product->id,
						'sku'      => $product->get_sku(),
						'name'     => $product->get_title(),
						'price'    => $product->get_price(),
						'category' => implode( ', ', wp_list_pluck( wc_get_product_terms( $product->ID, 'product_cat' ), 'name' ) ),
					)
				);
		}

		return $track;
	}

	/**
	 * Adds product information to a Segment_Cookie when item is added to cart.
	 *
	 * @param string $key      Key name for item in cart.  A hash.
	 * @param int    $id       Product ID
	 * @param int    $quantity Item quantity
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses  func_get_args() Because our abstract class doesn't know how many parameters are passed to each hook
	 *                        for each different platform, we use func_get_args().
	 */
	public function add_to_cart( $key, $id, $quantity ) {

		if ( ! is_object( WC()->cart ) ) {
			return;
		}

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

	/**
	 * Adds product properties to analytics.track() when product added to cart.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses  func_get_args() Because our abstract class doesn't know how many parameters are passed to each hook
	 *                        for each different platform, we use func_get_args().
	 *
	 * @return array Filtered array of name and properties for analytics.track().
	 */
	public function added_to_cart() {
		$args = func_get_args();

		$track = $args[0];

		if ( false !== ( $cookie = Segment_Cookie::get_cookie( 'added_to_cart' ) ) ) {

			if ( ! is_object( WC()->cart ) ) {
				return $track;
			}

			$items    = WC()->cart->get_cart();
			$_product = json_decode( $cookie );

			if ( is_object( $_product ) ) {
				$product  = get_product( $_product->ID );

				if ( $product ) {
					$item = array(
						'id'       => $product->id,
						'sku'      => $product->get_sku(),
						'name'     => $product->get_title(),
						'price'    => $product->get_price(),
						'quantity' => $_product->quantity,
						'category' => implode( ', ', wp_list_pluck( wc_get_product_terms( $product->id, 'product_cat' ), 'name' ) ),
					);

					$track = array(
						'event'      => __( 'Added Product', 'segment' ),
						'properties' => $item,
						'http_event' => 'added_to_cart'
					);
				}
			}

		}

		return $track;
	}

	/**
	 * Adds product information to a Segment_Cookie when item is removed from cart.
	 *
	 * @param string $key      Key name for item in cart.  A hash.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses  func_get_args() Because our abstract class doesn't know how many parameters are passed to each hook
	 *                        for each different platform, we use func_get_args().
	 */
	public function remove_from_cart( $key ) {

		if ( ! is_object( WC()->cart ) ) {
			return;
		}

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

	/**
	 * Adds product properties to analytics.track() when product is removed from cart.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses  func_get_args() Because our abstract class doesn't know how many parameters are passed to each hook
	 *                        for each different platform, we use func_get_args().
	 *
	 * @return array Filtered array of name and properties for analytics.track().
	 */
	public function removed_from_cart() {
		$args = func_get_args();

		$track = $args[0];

		if ( false !== ( $cookie = Segment_Cookie::get_cookie( 'removed_from_cart' ) ) ) {

			if ( ! is_object( WC()->cart ) ) {
				return $track;
			}

			$items = WC()->cart->get_cart();

			$_product  = json_decode( $cookie );

			if ( is_object( $_product ) ) {
				$product  = get_product( $_product->ID );

				if ( $product ) {
					$item = array(
						'id'       => $product->ID,
						'sku'      => $product->get_sku(),
						'name'     => $product->get_title(),
						'price'    => $product->get_price(),
						'quantity' => 0,
						'category' => implode( ', ', wp_list_pluck( wc_get_product_terms( $product->ID, 'product_cat' ), 'name' ) ),
					);

					$track = array(
						'event'      => __( 'Removed Product', 'segment' ),
						'properties' => $item,
						'http_event' => 'removed_from_cart'
					);
				}
			}
		}

		return $track;
	}

	/**
	 * Adds product properties to analytics.track() when the order is completed successfully.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @uses  func_get_args() Because our abstract class doesn't know how many parameters are passed to each hook
	 *                        for each different platform, we use func_get_args().
	 *
	 * @return array Filtered array of name and properties for analytics.track().
	 */
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
					'event'      => __( 'Completed Order', 'segment' ),
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

/**
 * Bootstrapper for the Segment_Commerce_Woo class.
 *
 * @since  1.0.0
 */
function segment_commerce_woo() {
	$commerce = new Segment_Commerce_Woo();

	return $commerce->init();
}

add_action( 'plugins_loaded', 'segment_commerce_woo', 100 );