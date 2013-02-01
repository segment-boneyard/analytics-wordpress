<?php
/*
Plugin Name: Analytics for WordPress — by Segment.io
Plugin URI: https://segment.io/plugins/wordpress
Description: The hassle-free way to integrate any analytics service into your Wordpress site.

Version: 0.3.0
License: GPLv2

Author: Segment.io
Author URI: https://segment.io
Author Email: friends@segment.io

References:
https://github.com/convissor/oop-plugin-template-solution
http://planetozh.com/blog/2009/09/top-10-most-common-coding-mistakes-in-wordpress-plugins/
http://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
http://teleogistic.net/2011/05/revisiting-git-github-and-the-wordpress-org-plugin-repository/
*/


// The public analytics methods, in case you want to reference them in other
// parts of your WordPress code.
class Analytics {

    // Render the Segment.io Javascript snippet.
    public function initialize($settings) {
        if (!isset($settings['api_key']) || $settings['api_key'] == '') return;

        include(WP_PLUGIN_DIR . '/analytics-wordpress/templates/snippet.php');
    }

    // Render a Javascript `identify` call.
    public function identify($user_id, $traits = false) {
        if (!$user_id) return;

        include(WP_PLUGIN_DIR . '/analytics-wordpress/templates/identify.php');
    }

    // Render a Javascript `track` call.
    public function track($event, $properties = false) {
        if (!$event) return;

        include(WP_PLUGIN_DIR . '/analytics-wordpress/templates/track.php');
    }

}


// The plugin itself, which automatically identifies users and commenters and
// tracks different types of page view events.
class Analytics_Wordpress {

    const ID      = 'analytics-wordpress';
    const NAME    = 'Analytics Wordpress';
    const VERSION = '0.3.0';

    private $option   = 'analytics_wordpress_options';
    private $defaults = array(
        'api_key' => ''
    );

    public function __construct() {
        // Setup our Wordpress hooks, using a slightly higher priority for the
        // analytics Javascript includes in the header and footer.
        if (is_admin()) {
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);
            add_filter('plugin_row_meta', array(&$this, 'plugin_row_meta'), 10, 2);
        } else {
            add_action('wp_head', array(&$this, 'wp_head'), 9);
            add_action('wp_footer', array(&$this, 'wp_footer'), 9);
        }

        // Make sure our settings object exists and is backed by our defaults.
        $settings = $this->get_settings();
        if (!is_array($settings)) $settings = array();
        $settings = array_merge($this->defaults, $settings);
        $this->set_settings($settings);
    }


    // Hooks
    // -----

    public function wp_head() {
        // Render the snippet.
        Analytics::initialize($this->get_settings());
    }

    public function wp_footer() {
        // Identify the user if the current user merits it.
        $identify = $this->get_current_user_identify();
        if ($identify) Analytics::identify($identify['user_id'], $identify['traits']);

        // Track a custom page view event if the current page merits it.
        $track = $this->get_current_page_track();
        if ($track) Analytics::track($track['event'], $track['properties']);
    }

    public function plugin_action_links($links, $file) {
        // Not for other plugins, silly. NOTE: This doesn't work properly when
        // the plugin for testing is a symlink!! If you change this, test it.
        if ($file != plugin_basename(__FILE__)) return $links;

        // Add settings link to the beginning of the row of links.
        $settings_link = '<a href="options-general.php?page=' . self::ID .'">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function plugin_row_meta($links, $file) {
        // Not for other plugins, silly. NOTE: This doesn't work properly when
        // the plugin for testing is a symlink!! If you change this, test it.
        if ($file != plugin_basename(__FILE__)) return $links;

        // Add a settings and docs link to the end of the row of links row of links.
        $settings_link = '<a href="options-general.php?page=' . self::ID .'">Settings</a>';
        $docs_link = '<a href="https://segment.io/plugins/wordpress" target="_blank">Docs</a>';
        array_push($links, $settings_link, $docs_link);
        return $links;
    }

    public function admin_menu() {
        // Render an "Analytics" menu item in the "Settings" menu.
        // http://codex.wordpress.org/Function_Reference/add_options_page
        add_options_page(
            'Analytics',                   // Page Title
            'Analytics',                   // Menu Title
            'manage_options',              // Capability Required
            'analytics-wordpress',         // Menu Slug
            array(&$this, 'admin_page') // Function
        );
    }

    public function admin_page() {
        // Make sure the user has the required permissions to view the settings.
        if (!current_user_can('manage_options')) {
            wp_die('Sorry, you don\'t have the permissions to access this page.');
        }

        $settings = $this->get_settings();

        // If we're saving and the nonce matches, update our settings.
        if (isset($_POST['submit']) && check_admin_referer($this->option)) {
            $settings['api_key'] = $_POST['api_key'];
            $this->set_settings($settings);
        }

        include(WP_PLUGIN_DIR . '/analytics-wordpress/templates/settings.php');
    }


    // Getters + Setters
    // -----------------

    // Get our plugin's settings.
    private function get_settings() {
        return get_option($this->option);
    }

    // Store new settings for our plugin.
    private function set_settings($settings) {
        return update_option($this->option, $settings);
    }

    // Based on the current user or commenter, see if we have enough information
    // to record an `identify` call. Since commenters don't have IDs, we
    // identify everyone by their email address.
    private function get_current_user_identify() {
        $user = wp_get_current_user();
        $commenter = wp_get_current_commenter();

        // We've got a logged-in user.
        // http://codex.wordpress.org/Function_Reference/wp_get_current_user
        if (is_user_logged_in() && $user) {
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
        else if ($commenter) {
            $identify = array(
                'user_id' => $commenter['comment_author_email'],
                'traits'  => array(
                    'email' => $commenter['comment_author_email'],
                    'name'  => $commenter['comment_author'],
                    'url'   => $commenter['comment_author_url']
                )
            );
        }
        // We don't have a user.
        else return false;

        // Clean out empty traits before sending it back.
        $identify['traits'] = $this->clean_array($identify['traits']);

        return $identify;
    }

    // Based on the current page, get the event and properties that should be
    // tracked for the custom page view event. Getting the title for a page is
    // confusing depending on what type of page it is... so reference this:
    // http://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/general-template.php#L0
    private function get_current_page_track() {
        // The front page of their site, whether it's a page or a list of
        // recent blog entries. `is_home` only works if it's not a page, that's
        // why we don't use it.
        if (is_front_page()) {
            $track = array(
                'event' => 'View Home Page'
            );
        }
        // A normal WordPress page.
        else if (is_page()) {
            $track = array(
                'event' => 'View ' . single_post_title('', false) . ' Page'
            );
        }
        // An author archive page. Check the `wp_title` docs to see how they get
        // the title of the page, cuz it's weird.
        // http://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/general-template.php#L0
        else if (is_author()) {
            $author = get_queried_object();
            $track = array(
                'event'      => 'View Author Page',
                'properties' => array(
                    'author' => $author->display_name
                )
            );
        }
        // A tag archive page. Use `single_tag_title` to get the name.
        // http://codex.wordpress.org/Function_Reference/single_tag_title
        else if (is_tag()) {
            $track = array(
                'event'      => 'View Tag Page',
                'properties' => array(
                    'tag' => single_tag_title('', false)
                )
            );
        }
        // A category archive page. Use `single_cat_title` to get the name.
        // http://codex.wordpress.org/Function_Reference/single_cat_title
        else if (is_category()) {
            $track = array(
                'event'      => 'View Category Page',
                'properties' => array(
                    'category' => single_cat_title('', false)
                )
            );
        }
        // The search page.
        else if (is_search()) {
            $track = array(
                'event'      => 'View Search Page',
                'properties' => array(
                    'query' => get_query_var('s')
                )
            );
        }
        // A post or a custom post. `is_single` also returns attachments, so we
        // filter those out. The event name is based on the post's type, and is
        // uppercased.
        else if (is_single() && !is_attachment()) {
            $track = array(
                'event'      => 'View ' . ucfirst(get_post_type()),
                'properties' => array(
                    'title' => single_post_title('', false)
                )
            );
        }
        // We don't have a page we want to track.
        else return false;

        // Clean out empty properties before sending it back.
        $track['properties'] = $this->clean_array($track['properties']);

        return $track;
    }


    // Utils
    // -----

    // Removes any empty keys in an array.
    private function clean_array($array) {
        foreach ($array as $key => $value) {
            if ($array[$key] == '') unset($array[$key]);
        }
        return $array;
    }

}

// Start the party.
$analytics_wordpress = new Analytics_Wordpress();
