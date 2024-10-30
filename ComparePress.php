<?php
/*
  Plugin Name: ComparePress
  Plugin URI: http://comparepress.com/
  Description: Turn your WordPress based blog or web site into an SEO price comparison, affiliate marketing money making machine with the ComparePress plugin. Currently with a UK mobile phone comparison service that compares over 1,000,000 UK mobile phone deals you can choose 1 or all of our widgets: Contract deals search widget, Top Phones Widgets, Latest Mobile Phones widget and Free Gifts with a mobile phone widget. For an example of everything you can do with ComparePress head over to our demo site: <a href='http://mobile-phonedeals.com/' title='Mobile Phone Deals'>Mobile Phone Deals</a>
  Version: 2.0.8
  Author: The Blog House
  Author URI: http://thebloghouse.com/

  ============================================================================================================
  ComparePress is Copyright 2010 - 2012 The Blog House Ltd

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License as
  published by the Free Software Foundation; either version 2 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
  General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
  USA
  ============================================================================================================ */
global $wpdb;
// Get array with token, and affiliateid (if exists) from options table
$validate = get_option("widget_comparePressAdmin");

// Setup some path constants
define("CP_BASE_DIR", WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)));
define("CP_BASE_URL", WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)));
define("CP_DB_BASE", $wpdb->prefix);
// Defines SOAP URLS
define("CP_SOAP_URL_UK_MOBILES", "http://comparepress.com/soap/mobiles/uk-standard-mobiles.php");
// Checks SOAP server is up
define("CP_SOAP_SERVER", "comparepress.com");
define("CP_SOAP_STATUS", "status.php");

// Checks SOAP server is up
$CP_host = CP_SOAP_SERVER;
$CP_up = CP_ping($CP_host);

// if ComparePress.com site is up, load the plugin
if ($CP_up) {
    // Include utility code:
    // - Widget modularisation functions, ComparePressFakePage class, Misc widget modularisation functions
    require_once(CP_BASE_DIR . '/admin/utility/utility_functions.php');

    // Plugin Hooks
    add_action('widgets_init', 'ComparePress_init');
    add_action('init', 'insert_into_head');
    //add_action('init', 'insert_into_admin_head');
    add_action('admin_menu', 'my_plugin_menu');
    add_action('init', 'admin_register_head');

    // Include utility code:
    // - Enqueues ajax.js, ask.js, admin.css for admin pages, and [module_name].css for non-admin pages
    require_once(CP_BASE_DIR . '/admin/utility/enqueue_scripts.php');

    // Add the Admin Menu
    function my_plugin_menu() {
        add_submenu_page('options-general.php', 'ComparePress', 'ComparePress', 'manage_options', __FILE__, 'ComparePress_navadmin');
    }

    // This admin page shown by default when ComparePress Menu link clicked
    function ComparePress_navadmin() {
        include(CP_BASE_DIR . "/admin/nav_admin.php");
    }

    function ComparePress_main_menu() {
        include(CP_BASE_DIR . "/admin/main_admin_page.php");
    }

    function modules_menu() {
        include(CP_BASE_DIR . "/admin/modules_admin_page.php");
    }

    function content_menu() {
        include(CP_BASE_DIR . "/admin/content_admin_page.php");
    }

    function description_content_menu() {
        include(CP_BASE_DIR . "/admin/description_content_admin_page.php");
    }

    function results_admin_menu() {
        include(CP_BASE_DIR . "/admin/results_admin_page.php");
    }

    function url_admin_menu() {
        include(CP_BASE_DIR . "/admin/url_admin_page.php");
    }

}
// otherwise, disable
else {
    add_action('admin_menu', 'my_plugin_menu');

    function my_plugin_menu() {
        add_submenu_page('options-general.php', 'ComparePress', 'ComparePress', 'manage_options', __FILE__, 'ComparePress_navadmin');
    }

    function ComparePress_navadmin() {
        include(CP_BASE_DIR . "/admin/nav_admin_down.php");
    }

}

// Utility function to check availability of ComparePress SOAP server
function CP_ping($CP_host, $port = 80, $timeout = 2) {
    $fsock = fsockopen($CP_host, $port, $errno, $errstr, $timeout);
    if (!$fsock) {
        return FALSE;
    } else {
        return TRUE;
    }
}

// Deprecated function to check availability of ComparePress SOAP server
function GetServerStatus($site, $path, $port, $find) {
    $fp = @fsockopen($site, $port, $errno, $errstr, 2);
    if (!$fp) {
        return false;
    } else {
        $header = "GET /$path HTTP/1.1\r\n";
        $header .= "Host: $site\r\n";
        $header .= "Connection: close\r\n\r\n";
        fputs($fp, $header);
        $str = '';
        while (!feof($fp)) {
            $str .= fgets($fp, 1024);
        }
        fclose($fp);
        return (strpos($str, $find) !== false);
    }
}
?>