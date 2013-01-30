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
http://www.onextrapixel.com/2009/07/01/how-to-design-and-style-your-wordpress-plugin-admin-panel/
http://planetozh.com/blog/2009/09/top-10-most-common-coding-mistakes-in-wordpress-plugins/
http://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
https://github.com/convissor/oop-plugin-template-solution
*/

class Analytics_Wordpress {

    const ID          = 'analytics-wordpress';
    const NAME        = 'Analytics Wordpress';
    const VERSION     = '0.0.1';
    const OPTION_NAME = 'analytics_wordpress_options';


    // Setup
    // -----

    // Setup our Wordpress hooks.
    public function __construct() {
        if (is_admin()) {
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action((is_multisite() ? 'network_admin_menu' : 'admin_menu'), array(&$this, 'admin_menu'));
        } else {
            add_action('wp_head', array(&$this, 'wp_head'));
            add_action('wp_footer', array(&$this, 'wp_footer'));
        }
    }

    public function admin_init() {}

    public function admin_menu() {
        $this->render_settings_menu_item();
    }

    public function wp_head() {
        $this->render_snippet();
    }

    public function wp_footer() {
        $this->render_identify();
    }


    // Getters + Setters
    // -----------------

    public function get_settings() {
        // Grab our settings from the database, backed up by defaults.
        // http://codex.wordpress.org/Function_Reference/get_option
        $settings = get_option(self::OPTION_NAME, array(
            'api_key' => ''
        ));

        return $settings;
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
        check_admin_referrer(self::OPTION_NAME);

        $settings = $this->get_settings();

        // If we're saving, update our settings.
        if (isset($_POST['save'])) {
            $settings['api_key'] = $_POST['api_key'];

            // Update the DB.
            update_option(self::OPTION_NAME, $settings);

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


    // Helpers
    // -------

    /**
     * A filter to add a "Settings" link in this plugin's description
     *
     * NOTE: This method is automatically called by WordPress for each
     * plugin being displayed on WordPress' Plugins admin page.
     *
     * @param array $links  the links generated thus far
     * @return array
     */
    public function plugin_action_links($links) {
        // Translation already in WP.
        $links[] = '<a href="' . $this->hsc_utf8($this->page_options)
                . '?page=' . self::ID . '">'
                . $this->hsc_utf8(__('Settings')) . '</a>';

        return $links;
    }

}
