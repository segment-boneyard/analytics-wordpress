<?php
/*
Plugin Name: Analytics for WordPress — by Segment.io
Plugin URI: https://segment.io/plugins/wordpress
Description: The hassle-free way to integrate any analytics service into your WordPress site.
Version: 0.6
License: GPLv2
Author: Segment.io
Author URI: https://segment.io
Author Email: friends@segment.io
*/

class Segment_Analytics {
	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new Segment_Analytics;
			self::$instance->setup_constants();

			if ( ! has_action( 'plugins_loaded', array( self::$instance, 'include_deprecated_files' ) ) ) {
				add_action( 'plugins_loaded', array( self::$instance, 'include_deprecated_files' ), 20 );
			}

		}

		return self::$instance;
	}

	public function setup_constants() {

		// Set the core file path
		define( 'SEG_FILE_PATH', dirname( __FILE__ ) );

		// Define the path to the plugin folder
		define( 'SEG_DIR_NAME',  basename( SEG_FILE_PATH ) );

		// Define the URL to the plugin folder
		define( 'SEG_FOLDER', dirname( plugin_basename( __FILE__ ) ) );
		define( 'SEG_URL'   , plugins_url( '', __FILE__ ) );

	}

	public function include_deprecated_files() {

		// Include old files for back compat
		include_once( SEG_FILE_PATH . '/class.analytics.php' );
		include_once( SEG_FILE_PATH . '/class.analytics-wordpress.php' );

	}

	// Render the Segment.io Javascript snippet.
	public static function initialize( $settings, $ignore = false ) {
		
		if ( ! isset( $settings['api_key'] ) || $settings['api_key'] == '' ) {
			return;
		}

		include_once( SEG_FILE_PATH . '/templates/snippet.php' );

	}

	// Render a Javascript `identify` call.
	public static function identify( $user_id, $traits = array(), $options = array() ) {
		
		// Set the proper `library` option so we know where the API calls come from.
		$options['library'] = 'analytics-wordpress';

		include_once( SEG_FILE_PATH. '/templates/identify.php' );
	}
 
	// Render a Javascript `track` call.
	public static function track( $event, $properties = array(), $options = array() ) {

		// Set the proper `library` option so we know where the API calls come from.
		$options['library'] = 'analytics-wordpress';

		include_once( SEG_FILE_PATH . '/templates/track.php' );
	}

	public static function page( $name, $properties = array() ) {}

}

class Segment_Analytics_WordPress {

	const SLUG    = 'analytics';
	const VERSION = '0.6';

	private static $instance;

	private $analytics;
	private $option   = 'analytics_wordpress_options';
	private $defaults = array(
		// Your Segment.io API key that we'll use to initialize analytics.js.
		'api_key'           => '',

		// Whether or not we should ignore users of above a certain permissions
		// level. (eg. `11` ignores nobody and `8` ignores Administrators)
		'ignore_user_level' => 11,

		// Whether or not we should track events for posts. This also includes
		// custom post types, for example a Product post type.
		'track_posts'       => true,

		// Whether or not we should track events for pages. This includes the
		// Home page and things like the About page, Contact page, etc.
		'track_pages'       => true,

		// Whether or not we should track custom events for archive pages like
		// the Category archive or the Author archive.
		'track_archives'    => true,

		// Whether or not we should track custom events for comments
		'track_comments'    => true,

		// Whether or not we should track custom events for users logging in
		'track_logins'      => true,

		// Whether or not we should track custom events for viewing the logged in page.
		'track_login_page'  => false,

		// Whether or not we should track custom events for the Search page.
		'track_searches'    => true
	);

	public static function get_instance() {
		
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Segment_Analytics_WordPress ) ) {
			
			self::$instance = new Segment_Analytics_WordPress;
			self::$instance->load_textdomain();
			self::$instance->admin_hooks();
			self::$instance->frontend_hooks();

			// A bit of a hack until we properly implement the Settings API.
			if ( is_admin() && isset( $_POST ) ) {
				self::$instance->init_settings();
			}

			self::$instance->analytics = Segment_Analytics::get_instance();

		}

		return self::$instance;
	}

	public function admin_hooks() {

		if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )  {

			add_action( 'admin_menu'         , array( $this, 'admin_menu' ) );
			add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
			add_filter( 'plugin_row_meta'    , array( $this, 'plugin_row_meta' )    , 10, 2 );

		}

	}

	public function frontend_hooks() {

		add_action( 'wp_head'          , array( $this, 'wp_head' )       , 9    );
		add_action( 'login_head'       , array( $this, 'wp_head' )       , 9    );
		add_action( 'wp_footer'        , array( $this, 'wp_footer' )     , 9    );
		add_action( 'login_footer'     , array( $this, 'wp_footer' )     , 9    );
		add_action( 'wp_insert_comment', array( $this, 'insert_comment' ), 9, 2 );
		add_action( 'wp_login'         , array( $this, 'login_event'    ), 9, 2 );
	}

	public function init_settings() {

		// Make sure our settings object exists and is backed by our defaults.
		$settings = $this->get_settings();

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$settings = array_merge( $this->defaults, $settings );

		$this->set_settings( $settings );
	}

	public function __construct() {}

	public function load_textdomain() {
		// Set filter for plugin's languages directory
		$segment_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$segment_lang_dir = apply_filters( 'segment_languages_directory', $segment_lang_dir );

		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale',  get_locale(), 'segment' );
		$mofile        = sprintf( '%1$s-%2$s.mo', 'segment', $locale );

		// Setup paths to current locale file
		$mofile_local  = $segment_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/segment/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/segment folder
			load_textdomain( 'segment', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/analytics-wordpress/languages/ folder
			load_textdomain( 'segment', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'segment', false, $segment_lang_dir );
		}
	}

	public function wp_head() {

		// Figure out whether the user should be ignored or not.
		$ignore = false;

		$settings = $this->get_settings();
		$user     = wp_get_current_user();

		if ( $user->user_level >= $settings['ignore_user_level'] ) {
			$ignore = true;
		}

		// Render the snippet.
		self::$instance->analytics->initialize( $settings, $ignore );
	}

	public function wp_footer() {

		// Identify the user if the current user merits it.
		$identify = $this->get_current_user_identify();
		
		if ( $identify ) {
			self::$instance->analytics->identify( $identify['user_id'], $identify['traits'] );
		}

		// Track a custom page view event if the current page merits it.
		$track = $this->get_current_page_track();

		if ( $track ) {
			self::$instance->analytics->track( $track['event'], $track['properties'] );
		}
	}

	public function insert_comment( $id, $comment ) {
		$commenter = wp_get_current_commenter();
		$hash      = md5( json_encode( $commenter ) );

		setcookie( 'segment_left_comment_' . COOKIEHASH, $hash, time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	public function login_event( $login, $user ) {

		$hash = md5( json_encode( $user ) );

		setcookie( 'segment_logged_in_' . COOKIEHASH, $hash, time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	public function plugin_action_links( $links, $file ) {
	
		// Not for other plugins, silly. NOTE: This doesn't work properly when
		// the plugin for testing is a symlink!! If you change this, test it.
		// Note: Works fine as of 3.9, see @link: https://core.trac.wordpress.org/ticket/16953
		if ( $file != plugin_basename( __FILE__ ) ) {
			return $links;
		}

		// Add settings link to the beginning of the row of links.
		$settings_link = '<a href="options-general.php?page=' . self::SLUG . '">Settings</a>';
		
		array_unshift( $links, $settings_link );
		
		return $links;
	}

	public function plugin_row_meta( $links, $file ) {
		// Not for other plugins, silly. NOTE: This doesn't work properly when
		// the plugin for testing is a symlink!! If you change this, test it.
		// Note: Works fine as of 3.9, see @link: https://core.trac.wordpress.org/ticket/16953
		if ( $file != plugin_basename( __FILE__ ) ) {
			return $links;	
		}

		// Add a settings and docs link to the end of the row of links row of links.
		$settings_link = '<a href="options-general.php?page=' . self::SLUG . '">Settings</a>';
		$docs_link     = '<a href="https://segment.io/plugins/wordpress" target="_blank">Docs</a>';
		
		array_push( $links, $settings_link, $docs_link );
		
		return $links;
	}

	public function admin_menu() {

		// Render an "Analytics" menu item in the "Settings" menu.
		// http://codex.wordpress.org/Function_Reference/add_options_page
		add_options_page(
			'Analytics',                 // Page Title
			'Analytics',                 // Menu Title
			'manage_options',            // Capability Required
			self::SLUG,                  // Menu Slug
			array( $this, 'admin_page' ) // Function
		);

	}

	public function admin_page() {
		// Make sure the user has the required permissions to view the settings.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Sorry, you don\'t have the permissions to access this page.' );
		}

		$settings = $this->get_settings();

		// If we're saving and the nonce matches, update our settings.
		// Checkboxes have a value of 1, so either they're sent or not?
		if ( isset( $_POST['submit'] ) && check_admin_referer( $this->option ) ) {
			$settings['api_key']           = sanitize_text_field( $_POST['api_key'] );
			$settings['ignore_user_level'] = absint( $_POST['ignore_user_level'] );
			$settings['track_posts']       = isset( $_POST['track_posts'] )   ;
			$settings['track_pages']       = isset( $_POST['track_pages'] )   ;
			$settings['track_archives']    = isset( $_POST['track_archives'] );
			$settings['track_comments']    = isset( $_POST['track_comments'] );
			$settings['track_searches']    = isset( $_POST['track_searches'] );

			$this->set_settings( $settings );
		}

		include_once( SEG_FILE_PATH . '/templates/settings.php');
	}

	// Get our plugin's settings.
	private function get_settings() {
		return apply_filters( 'segment_get_settings', get_option( $this->option ), $this );
	}

	// Store new settings for our plugin.
	private function set_settings( $settings ) {
		return update_option( $this->option, $settings );
	}

	// Based on the current user or commenter, see if we have enough information
	// to record an `identify` call. Since commenters don't have IDs, we
	// identify everyone by their email address.
	private function get_current_user_identify() {
		$settings  = $this->get_settings();
		
		$user      = wp_get_current_user();
		$commenter = wp_get_current_commenter();
		$identify  = false;

		// We've got a logged-in user.
		// http://codex.wordpress.org/Function_Reference/wp_get_current_user
		if ( is_user_logged_in() && $user ) {
			$identify = array(
				'user_id' => $user->user_email,
				'traits'  => array(
					'username'  => $user->user_login,
					'email'     => $user->user_email,
					'name'      => $user->display_name,
					'firstName' => $user->user_firstname,
					'lastName'  => $user->user_lastname,
					'url'       => $user->user_url
				)
			);
		}
		// We've got a commenter.
		// http://codex.wordpress.org/Function_Reference/wp_get_current_commenter
		else if ( $commenter ) {
			$identify = array(
				'user_id' => $commenter['comment_author_email'],
				'traits'  => array(
					'email' => $commenter['comment_author_email'],
					'name'  => $commenter['comment_author'],
					'url'   => $commenter['comment_author_url']
				)
			);
		}

		if ( $identify ) {
			// Clean out empty traits before sending it back.
			$identify['traits'] = array_filter( $identify['traits'] );
		}

		return apply_filters( 'segment_get_current_user_identify', $identify, $settings, $this );
	}

	// Based on the current page, get the event and properties that should be
	// tracked for the custom page view event. Getting the title for a page is
	// confusing depending on what type of page it is... so reference this:
	// http://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/general-template.php#L0
	private function get_current_page_track() {

		$settings = $this->get_settings();

		// Posts
		// -----
		if ( $settings['track_posts'] ) {
			// A post or a custom post. `is_single` also returns attachments, so
			// we filter those out. The event name is based on the post's type,
			// and is uppercased.
			if ( is_single() && ! is_attachment() ) {
				$categories = implode( ', ', wp_list_pluck( get_categories( get_the_ID() ), 'name' ) );
				$track = array(
					'event'      => 'Viewed ' . ucfirst( get_post_type() ),
					'properties' => array(
						'title'      => single_post_title( '', false ),
						'category' => $categories
					)
				);
			}
		}

		// Pages
		// -----
		if ( $settings['track_pages'] ) {
			// The front page of their site, whether it's a page or a list of
			// recent blog entries. `is_home` only works if it's not a page,
			// that's why we don't use it.
			if ( is_front_page() ) {
				$track = array(
					'event' => 'Viewed Home Page'
				);
			}
			// A normal WordPress page.
			else if ( is_page() ) {
				$track = array(
					'event' => 'Viewed ' . single_post_title( '', false ) . ' Page'
				);
			}
		}

		// Archives
		// --------
		if ( $settings['track_archives'] ) {
			// An author archive page. Check the `wp_title` docs to see how they
			// get the title of the page, cuz it's weird.
			// http://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/general-template.php#L0
			if ( is_author() ) {
			$author = get_queried_object();
			$track  = array(
				'event'      => 'Viewed Author Page',
				'properties' => array(
					'author' => $author->display_name
					)
				);
			}
			// A tag archive page. Use `single_tag_title` to get the name.
			// http://codex.wordpress.org/Function_Reference/single_tag_title
			else if ( is_tag() ) {
				$track = array(
				'event'      => 'Viewed Tag Page',
				'properties' => array(
					'	tag' => single_tag_title( '', false )
					)
				);
			}
			// A category archive page. Use `single_cat_title` to get the name.
			// http://codex.wordpress.org/Function_Reference/single_cat_title
			else if ( is_category() ) {
				$track = array(
				'event'      => 'Viewed Category Page',
				'properties' => array(
						'category' => single_cat_title('', false)
					)
				);
			}
		}

		// Comments
		// --------
		if ( $settings['track_comments'] ) {

			$commenter = wp_get_current_commenter();
			$hash      = md5( json_encode( $commenter ) );

			if ( isset( $_COOKIE['segment_left_comment_' . COOKIEHASH] ) && $hash === $_COOKIE['segment_left_comment_' . COOKIEHASH] ) {

				$track = array(
					'event'      => 'Commented',
					'properties' => array(
						'commenter' => $commenter
					)
				);

				setcookie( 'segment_left_comment_' . COOKIEHASH, $hash, time() - DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
			}

		}

		// Login Event
		// --------
		if ( $settings['track_logins'] ) {

			$user = wp_get_current_user();
			$hash = md5( json_encode( $user ) );

			if ( isset( $_COOKIE['segment_logged_in_' . COOKIEHASH] ) && $hash === $_COOKIE['segment_left_comment_' . COOKIEHASH] ) {

				$track = array(
					'event'      => 'Logged In',
					'properties' => array(
						'user' => $user
					)
				);

				setcookie( 'segment_logged_in_' . COOKIEHASH, $hash, time() - DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
			}

		}

		// Login Page
		// --------
		if ( $settings['track_login_page'] ) {

			if ( did_action( 'login_init' ) ) {

				$track = array(
					'event'      => 'Viewed Login Page'
				);

			}

		}

		// Searches
		// --------
		if ( $settings['track_searches'] ) {
			// The search page.
			if ( is_search() ) {
				$track = array(
					'event'      => 'Viewed Search Page',
					'properties' => array(
						'query' => get_query_var( 's' )
					)
				);
			}
		}

		// We don't have a page we want to track.
		if ( ! isset( $track ) ) {
			$track = false;
		}

		if ( $track ) {
			// All of these are checking for pages, and we don't want that to throw
			// off Google Analytics's bounce rate, so mark them `noninteraction`.
			$track['properties']['noninteraction'] = true;

			// Clean out empty properties before sending it back.
			$track['properties'] = array_filter( $track['properties'] );
		}

		return apply_filters( 'segment_get_current_page_track', $track, $settings, $this );
	}

	private function clean_array( $array ) {
		return array_filter( $array );
	}

}

add_action( 'plugins_loaded', 'Segment_Analytics_WordPress::get_instance' );