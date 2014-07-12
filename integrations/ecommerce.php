<?php

/**
 * The Segment_Commerce abstract class is a handy, dandy abstract for eCommerce platforms to extend.
 * With a minimal amount of code, an eCommerce platform can hook into each of the registered events in Segment.
 * For examples, see the integrations in our /integrations/ecommerce folder.
 *
 * @package Segment
 * @since  1.0.0
 *
 */
abstract class Segment_Commerce {

	/**
	 * An array of default registered events.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $registered_events = array();

	/**
	 * Sets the default registered events and returns the object.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct() {

		$this->registered_events = array(
			'viewed_category',
			'viewed_product',
			'added_to_cart',
			'removed_from_cart',
			'completed_order',
		);

		return $this;
	}

	/**
	 * Registers hooks for the Segment Commerce system.
	 *
	 * Usable by plugins to register methods or functions to hook into different eCommerce events.
	 * Someday, late static binding will be available to all WordPress users, which will make this a bit less hacky.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param  string $hook  The WordPress action ( e.g. do_action( '' ) )
	 * @param  string $event The name of the function or method that handles the tracking output.
	 * @param  object $class The class, if any, that contains the $event method.
	 *
	 * @return mixed  $registered False if no event was registered, string if function was registered, array if method.
	 */
	public function register_hook( $hook, $event, $args = 1, $class = '' ) {

		$registered_events = $this->get_registered_hooks();

		if ( ! in_array( $event, $registered_events ) ) {
			return false;
		}

		if ( ! empty( $class ) && is_callable( array( $class, $event ) ) ) {

			$this->registered_events[ $hook ] = array( $class, $event );

			$registered = add_filter( $hook, array( $class, $event ), 10, $args );

		} else if ( is_callable( $event ) ) {

			$this->registered_events[ $hook ] = $event;
			$registered = add_filter( $hook, $event, 10, $args );

		} else {

			$registered = false;

		}

		return $registered;
	}

	/**
	 * Returns the registered events.
	 *
	 * Sub-classes can filter this to add additional events to be triggered.
	 *
	 * @since  1.0.0
	 * @access  public
	 *
	 * @return array Filtered events.
	 */
	public function get_registered_hooks() {
		return apply_filters( 'segment_commerce_events', array_filter( $this->registered_events ), $this );
	}

	/**
	 * Basic bootstrap for core integrations.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function bootstrap() {

		if ( class_exists( 'WP_eCommerce' ) ) {
			include_once SEG_FILE_PATH . '/integrations/ecommerce/wp-e-commerce.php';
		} else if ( class_exists( 'WooCommerce' ) ) {
			include_once SEG_FILE_PATH . '/integrations/ecommerce/woocommerce.php';
		}

	}

	/**
	 * Event to be fired when a product category is viewed.
	 * Abstract method, must be overridden by sub-class.
	 *
	 * @since  1.0.0
	 */
	abstract function viewed_category();

	/**
	 * Event to be fired when a product is viewed.
	 * Abstract method, must be overridden by sub-class.
	 *
	 * @since  1.0.0
	 */
	abstract function viewed_product();

	/**
	 * Event to be fired when a product is added to the cart.
	 * Abstract method, must be overridden by sub-class.
	 *
	 * @since  1.0.0
	 */
	abstract function added_to_cart();

	/**
	 * Event to be fired when a product removed from the cart.
	 * Abstract method, must be overridden by sub-class.
	 *
	 * @since  1.0.0
	 */
	abstract function removed_from_cart();

	/**
	 * Event to be fired when an order is successfully completed.
	 * Abstract method, must be overridden by sub-class.
	 *
	 * @since  1.0.0
	 */
	abstract function completed_order();

}

Segment_Commerce::bootstrap();