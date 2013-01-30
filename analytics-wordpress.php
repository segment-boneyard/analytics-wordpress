<?php
/*
Plugin Name: Analytics Wordpress
Plugin URI: https://segment.io
Description: The hassle-free way to integrate any analytics service into your Wordpress site.

Version: 0.0.1
License: MIT

Author: Segment.io
Author URI: https://segment.io
Author Email: friends@segment.io

References:
https://github.com/convissor/oop-plugin-template-solution
http://planetozh.com/blog/2009/09/top-10-most-common-coding-mistakes-in-wordpress-plugins/
http://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
*/

class Analytics_Wordpress {

    const ID      = 'analytics-wordpress';
    const NAME    = 'Analytics Wordpress';
    const VERSION = '0.0.1';

    private $option_name = 'analytics_wordpress_options';
    private $defaults    = array(
        'api_key' => ''
    );


    // Setup
    // -----

    public function __construct() {
        // Setup our Wordpress hooks.
        if (is_admin()) {
            add_action('admin_menu', array(&$this, 'render_settings_menu_item'));
        } else {
            add_action('wp_head', array(&$this, 'render_snippet'));
            add_action('wp_footer', array(&$this, 'render_identify'));
        }

        // Make sure our settings object exists and is backed by our defaults.
        $settings = $this->get_settings();
        if (!is_array($settings)) $settings = array();
        $settings = array_merge($this->defaults, $settings);
        $this->set_settings($settings);
    }


    // Get + Set
    // ---------

    private function get_settings() {
        return get_option($this->option_name);
    }

    private function set_settings($settings) {
        return update_option($this->option_name, $settings);
    }


    // Render
    // ------

    // Render an "Analytics" menu item in the "Settings" menu.
    public function render_settings_menu_item() {
        // http://codex.wordpress.org/Function_Reference/add_options_page
        add_options_page(
            'Analytics',                          // Page Title
            'Analytics',                          // Menu Title
            'manage_options',                     // Capability Required
            'analytics-wordpress',                // Menu Slug
            array(&$this, 'render_settings_page') // Function
        );
    }

    // Render the settings page.
    public function render_settings_page() {
        // Make sure the user has the required permissions.
        if (!current_user_can('manage_options')) {
            wp_die('Sorry, you don\'t have the permissions to access this page.');
        }

        $settings = $this->get_settings();

        // If we're saving and the nonce matches, update our settings.
        if (isset($_POST['submit']) && check_admin_referer($this->option_name)) {
            $settings['api_key'] = $_POST['api_key'];
            $this->set_settings($settings);
        }

        include(WP_PLUGIN_DIR . '/analytics-wordpress/templates/settings.php');
    }

    // Render the Segment.io snippet complete with API key.
    public function render_snippet() {
        $settings = $this->get_settings();

        // If you don't have the settings we need, get out of here.
        if (!isset($settings['api_key']) || $settings['api_key'] == '') return;

        include(WP_PLUGIN_DIR . '/analytics-wordpress/templates/snippet.php');
    }

    // Render a Javascript `identify` call if the user is logged in.
    public function render_identify() {
        if (!is_user_logged_in()) return;

        $user = wp_get_current_user();

        include(WP_PLUGIN_DIR . '/analytics-wordpress/templates/identify.php');
    }

}

$analytics_wordpress = new Analytics_Wordpress();
