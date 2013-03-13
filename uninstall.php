<?php
/*
Reference:
http://jacobsantos.com/2008/general/wordpress-27-plugin-uninstall-methods/
*/

if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

delete_option('analytics_wordpress_options');