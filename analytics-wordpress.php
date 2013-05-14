<?php
/*
Plugin Name: Analytics for WordPress — by Segment.io
Plugin URI: https://segment.io/plugins/wordpress
Description: The hassle-free way to integrate any analytics service into your Wordpress site.

Version: 0.5.3
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
    include(plugin_dir_path(__FILE__) . 'templates/snippet.php');
  }

  // Render a Javascript `identify` call.
  public function identify($user_id, $traits = array(), $options = array()) {
    // A user ID is required.
    if (!$user_id) return;

    // Set the proper `library` option so we know where the API calls come from.
    $options['library'] = 'analytics-wordpress';

    include(plugin_dir_path(__FILE__) . 'templates/identify.php');
  }

  // Render a Javascript `track` call.
  public function track($event, $properties = array(), $options = array()) {
    // An event is required.
    if (!$event) return;

    // Set the proper `library` option so we know where the API calls come from.
    $options['library'] = 'analytics-wordpress';

    include(plugin_dir_path(__FILE__) . 'templates/track.php');
  }

}


// The plugin itself, which automatically identifies users and commenters and
// tracks different types of page view events.
class Analytics_Wordpress {

  const SLUG    = 'analytics';
  const VERSION = '0.5.3';

  private $option   = 'analytics_wordpress_options';
  private $defaults = array(
    // Your Segment.io API key that we'll use to initialize analytics.js.
    'api_key' => '',
    // Whether or not we should ignore users of above a certain permissions
    // level. (eg. `11` ignores nobody and `8` ignores Administrators)
    'ignore_user_level' => 11,
    // Whether or not we should track events for posts. This also includes
    // custom post types, for example a Product post type.
    'track_posts' => true,
    // Whether or not we should track events for pages. This includes the
    // Home page and things like the About page, Contact page, etc.
    'track_pages' => true,
    // Whether or not we should track custom events for archive pages like
    // the Category archive or the Author archive.
    'track_archives' => true,
    // Whether or not we should track custom events for the Search page.
    'track_searches' => true
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
    $settings_link = '<a href="options-general.php?page=' . self::SLUG . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
  }

  public function plugin_row_meta($links, $file) {
    // Not for other plugins, silly. NOTE: This doesn't work properly when
    // the plugin for testing is a symlink!! If you change this, test it.
    if ($file != plugin_basename(__FILE__)) return $links;

    // Add a settings and docs link to the end of the row of links row of links.
    $settings_link = '<a href="options-general.php?page=' . self::SLUG . '">Settings</a>';
    $docs_link = '<a href="https://segment.io/plugins/wordpress" target="_blank">Docs</a>';
    array_push($links, $settings_link, $docs_link);
    return $links;
  }

  public function admin_menu() {
    // Render an "Analytics" menu item in the "Settings" menu.
    // http://codex.wordpress.org/Function_Reference/add_options_page
    add_options_page(
      'Analytics',                // Page Title
      'Analytics',                // Menu Title
      'manage_options',           // Capability Required
      self::SLUG,                 // Menu Slug
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
    // Checkboxes have a value of 1, so either they're sent or not?
    if (isset($_POST['submit']) && check_admin_referer($this->option)) {
      $settings['api_key']           = $_POST['api_key'];
      $settings['ignore_user_level'] = $_POST['ignore_user_level'];
      $settings['track_posts']       = isset($_POST['track_posts']) ? true : false;
      $settings['track_pages']       = isset($_POST['track_pages']) ? true : false;
      $settings['track_archives']    = isset($_POST['track_archives']) ? true : false;
      $settings['track_searches']    = isset($_POST['track_searches']) ? true : false;

      $this->set_settings($settings);
    }

    include(plugin_dir_path(__FILE__) . 'templates/settings.php');
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
    $settings = $this->get_settings();
    $user = wp_get_current_user();
    $commenter = wp_get_current_commenter();

    // If our user's permissions level is greater than or equal to our
    // ignored level, get out of here.
    if (($user->user_level >= $settings['ignore_user_level'])) return false;

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
    $settings = $this->get_settings();
    $user = wp_get_current_user();

    // If our user's permissions level is greater than or equal to our
    // ignored level, get out of here.
    if (($user->user_level >= $settings['ignore_user_level'])) return false;

    // Posts
    // -----
    if ($settings['track_posts']) {
      // A post or a custom post. `is_single` also returns attachments, so
      // we filter those out. The event name is based on the post's type,
      // and is uppercased.
      if (is_single() && !is_attachment()) {
        $track = array(
          'event'      => 'Viewed ' . ucfirst(get_post_type()),
          'properties' => array(
            'title' => single_post_title('', false)
          )
        );
      }
    }

    // Pages
    // -----
    if ($settings['track_pages']) {
      // The front page of their site, whether it's a page or a list of
      // recent blog entries. `is_home` only works if it's not a page,
      // that's why we don't use it.
      if (is_front_page()) {
        $track = array(
          'event' => 'Viewed Home Page'
        );
      }
      // A normal WordPress page.
      else if (is_page()) {
        $track = array(
          'event' => 'Viewed ' . single_post_title('', false) . ' Page'
        );
      }
    }

    // Archives
    // --------
    if ($settings['track_archives']) {
      // An author archive page. Check the `wp_title` docs to see how they
      // get the title of the page, cuz it's weird.
      // http://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/general-template.php#L0
      if (is_author()) {
        $author = get_queried_object();
        $track = array(
          'event'      => 'Viewed Author Page',
          'properties' => array(
            'author' => $author->display_name
          )
        );
      }
      // A tag archive page. Use `single_tag_title` to get the name.
      // http://codex.wordpress.org/Function_Reference/single_tag_title
      else if (is_tag()) {
        $track = array(
          'event'      => 'Viewed Tag Page',
          'properties' => array(
            'tag' => single_tag_title('', false)
          )
        );
      }
      // A category archive page. Use `single_cat_title` to get the name.
      // http://codex.wordpress.org/Function_Reference/single_cat_title
      else if (is_category()) {
        $track = array(
          'event'      => 'Viewed Category Page',
          'properties' => array(
            'category' => single_cat_title('', false)
          )
        );
      }
    }

    // Searches
    // --------
    if ($settings['track_searches']) {
      // The search page.
      if (is_search()) {
        $track = array(
          'event'      => 'Viewed Search Page',
          'properties' => array(
            'query' => get_query_var('s')
          )
        );
      }
    }

    // We don't have a page we want to track.
    if (!isset($track)) return false;

    // All of these are checking for pages, and we don't want that to throw
    // off Google Analytics's bounce rate, so mark them `noninteraction`.
    $track['properties']['noninteraction'] = true;

    // Clean out empty properties before sending it back.
    $track['properties'] = $this->clean_array($track['properties']);

    return $track;
  }


  // Utils
  // -----

  // Removes any empty keys in an array.
  private function clean_array($array) {
    // In case they pass in some weird stuff.
    if (!is_array($array)) return $array;

    foreach ($array as $key => $value) {
      if ($array[$key] == '') unset($array[$key]);
    }
    return $array;
  }

}

// Start the party.
$analytics_wordpress = new Analytics_Wordpress();
