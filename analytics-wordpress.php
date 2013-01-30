<?php
/*
Plugin Name: Analytics Wordpress
Plugin URI: https://segment.io
Description: The hassle-free way to integrate any analytics service into your Wordpress site.

Version: 0.0.1
License: MIT

Author: Segment.io
Author URI: mailto:friends@segment.io

References:
http://www.onextrapixel.com/2009/07/01/how-to-design-and-style-your-wordpress-plugin-admin-panel/
http://planetozh.com/blog/2009/09/top-10-most-common-coding-mistakes-in-wordpress-plugins/
http://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
*/

class Analytics_Wordpress {

    public function __construct() {
        if (is_admin()) {
            // Add a menu item to the settings menu.
            add_action('admin_menu', array(&$this, 'render_settings_menu_item'));
        } else {
            // Add the Segment.io snippet to the <head>.
            add_action('wp_head', array(&$this, 'render_snippet'));
            // Add an identify call to the footer.
            add_action('wp_footer', array(&$this, 'render_identify'));
        }
    }


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
        check_admin_referrer('analytics_wordpress_settings');

        $settings = $this->get_settings();

        // If we're saving, update our settings.
        if (isset($_POST['save'])) {
            $settings['api_key'] = $_POST['api_key'];

            // Update the DB.
            update_option('analytics_wordpress_settings', $settings);

            // Tell the user what's going on.
            echo '<div class="updated"><p>Settings saved!</p></div>';
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


    public function get_settings() {
        // Grab our settings from the database, backed up by defaults.
        // http://codex.wordpress.org/Function_Reference/get_option
        $settings = get_option('analytics_wordpress_settings', array(
            'api_key' => ''
        ));

        return $settings;
    }

}
