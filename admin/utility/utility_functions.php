<?php
// ----------------------------------
// UTILITY CODE FOR COMPAREPRESS
// ----------------------------------
// CONTENTS:
//  1. Functions to refactor widget rendering code
//  2. Misc widget modularised functions
//  3. ComparePressFakePage class
// ---------------------------------------------
// -------------------------------
//  START - 1. Functions to refactor widget rendering code
// ----------------------------------------------------------------------------
// Function for displaying ComparePress top items widget (modularised)
//
// @TODO: To remove hard coded /UK? links - see lines 111 / 112
// @TODO: Only need ComparePresstop or ComparePresslatest as these are both effectively doing same thing and control is now within /modules
// @TODO: Need to work on how to fake $post->ID so we can tap into plugins like Facebook one - maybe custom post type?

function widget_ComparePresstop($args, $instance) {
    global $module;
    extract($args);
    $bgcol = ($instance["bg_color"] == "" ? "#ffffff" : $instance["bg_color"]);
    $bd_th = ($instance["border_width"] == "" ? "0" : $instance["border_width"]);
    $bd_col = ($instance["border_color"] == "" ? "#C0C0C0" : $instance["border_color"]);
    $bgcol = colour_hash_value($bgcol);
    $bd_col = colour_hash_value($bd_col);
    echo "<style type=\"text/css\">#" . $args['widget_id'] . " { margin-bottom:10px; background-color: " . $bgcol . "; border: " . $bd_th . "px solid " . $bd_col . "; }</style>";
    echo "<div>";
    echo $before_widget;
    echo $before_title;
    echo $instance['title'];
    echo $after_title;
    $top_proc = "top" . $module;
    $top_proc($instance);
    echo $after_widget;
    echo "</div>";
}

// Widget controls for top items widget (modularised)
function ComparePress_top_control(&$th, $instance) { // & to force passing of object by reference
    $defaults = array('title' => '', 'num_items' => '5', 'bg_color' => '', 'border_width' => '0', 'border_color' => '');
    $instance = wp_parse_args((array) $instance, $defaults);
    $title = strip_tags($instance['title']);
    $num_items = strip_tags($instance['num_items']);
    $bg_color = strip_tags($instance['bg_color']);
    $border_width = strip_tags($instance['border_width']);
    $border_color = strip_tags($instance['border_color']);
    $feature = strip_tags($instance['feature']);
    $linktext = $instance['linktext'];
    ?>
    <p><?php _e('Title') ?>: <input class="widefat" name="<?php echo $th->get_field_name('title'); ?>"  type="text" value="<?php echo esc_attr($title); ?>" /></p>
    <p><?php _e('Number of Items: ') ?>: <input class="widefat" name="<?php echo $th->get_field_name('num_items'); ?>"  type="text" value="<?php echo esc_attr($num_items); ?>" /></p>
    <p><?php _e('Background Colour (HEX value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('bg_color'); ?>"  type="text" value="<?php echo esc_attr($bg_color); ?>" /></p>
    <p><?php _e('Border Width (Pixel value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('border_width'); ?>"  type="text" value="<?php echo esc_attr($border_width); ?>" /></p>
    <p><?php _e('Border Colour (HEX value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('border_color'); ?>"  type="text" value="<?php echo esc_attr($border_color); ?>" /></p>
    <p><?php _e('Filter by feature (e.g. google android)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('feature'); ?>"  type="text" value="<?php echo esc_attr($feature); ?>" /></p>
    <p><?php _e('Add an extra link (html)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('linktext'); ?>"  type="text" value="<?php echo esc_attr($linktext); ?>" /></p>    
    <?php
}

// Function for displaying ComparePress latest items widget (modularised)
function widget_ComparePresslatest($args, $instance) {
    global $module;
    extract($args);
    $bgcol = ($instance["bg_color"] == "" ? "#ffffff" : $instance["bg_color"]);
    $bd_th = ($instance["border_width"] == "" ? "0" : $instance["border_width"]);
    $bd_col = ($instance["border_color"] == "" ? "#C0C0C0" : $instance["border_color"]);
    $bgcol = colour_hash_value($bgcol);
    $bd_col = colour_hash_value($bd_col);
    echo "<style type=\"text/css\">#" . $args["widget_id"] . " { margin-bottom:10px; background-color: " . $bgcol . "; border: " . $bd_th . "px solid " . $bd_col . "; }</style>";
    echo "<div>";
    echo $before_widget;
    echo $before_title;
    echo $instance['title'];
    echo $after_title;
    $latest_proc = "latest" . $module;
    $latest_proc($instance);
    echo $after_widget;
    echo "</div>";
}

// Widget controls for latest items widget (modularised)
function ComparePress_latest_control(&$th, $instance) {
    $defaults = array('title' => '', 'num_items' => '4', 'bg_color' => '', 'border_width' => '0', 'border_color' => '');
    $instance = wp_parse_args((array) $instance, $defaults);
    $title = strip_tags($instance['title']);
    $num_items = strip_tags($instance['num_items']);
    $bg_color = strip_tags($instance['bg_color']);
    $border_width = strip_tags($instance['border_width']);
    $border_color = strip_tags($instance['border_color']);
    $feature = strip_tags($instance['feature']);
    ?>
    <p><?php _e('Title') ?>: <input class="widefat" name="<?php echo $th->get_field_name('title'); ?>"  type="text" value="<?php echo esc_attr($title); ?>" /></p>
    <p><?php _e('Number of Items') ?>: <input class="widefat" name="<?php echo $th->get_field_name('num_items'); ?>"  type="text" value="<?php echo esc_attr($num_items); ?>" /></p>
    <p><?php _e('Background Colour (HEX value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('bg_color'); ?>"  type="text" value="<?php echo esc_attr($bg_color); ?>" /></p>
    <p><?php _e('Border Width (Pixel value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('border_width'); ?>"  type="text" value="<?php echo esc_attr($border_width); ?>" /></p>
    <p><?php _e('Border Colour (HEX value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('border_color'); ?>"  type="text" value="<?php echo esc_attr($border_color); ?>" /></p>
    <p><?php _e('Filter by feature(e.g. google android)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('feature'); ?>"  type="text" value="<?php echo esc_attr($feature); ?>" /></p>    
    <?php
}

// Function for displaying ComparePress free gifts widget (modularised)
function widget_ComparePressFreeGifts($args, $instance) {
    global $module;
    extract($args);
    $bgcol = ($instance["bg_color"] == "" ? "#ffffff" : $instance["bg_color"]);
    $bd_th = ($instance["border_width"] == "" ? "0" : $instance["border_width"]);
    $bd_col = ($instance["border_color"] == "" ? "#C0C0C0" : $instance["border_color"]);
    $bgcol = colour_hash_value($bgcol);
    $bd_col = colour_hash_value($bd_col);
    echo "<style type=\"text/css\">#" . $args["widget_id"] . " { margin-bottom:10px; background-color: " . $bgcol . "; border: " . $bd_th . "px solid " . $bd_col . "; }</style>";
    echo "<div>";
    echo $before_widget;
    echo $before_title;
    echo $instance['title'];
    echo $after_title;
    $latest_proc = "FreeGifts" . $module;
    $latest_proc($instance);
    echo $after_widget;
    echo "</div>";
}

// Widget controls for free gifts widget (modularised)
function ComparePress_FreeGifts_control(&$th, $instance) {
    $defaults = array('title' => '', 'num_items' => '4', 'bg_color' => '', 'border_width' => '0', 'border_color' => '');
    $instance = wp_parse_args((array) $instance, $defaults);
    $title = strip_tags($instance['title']);
    $num_items = strip_tags($instance['num_items']);
    $bg_color = strip_tags($instance['bg_color']);
    $border_width = strip_tags($instance['border_width']);
    $border_color = strip_tags($instance['border_color']);
    $feature = strip_tags($instance['feature']);
    ?>
    <p><?php _e('Title') ?>: <input class="widefat" name="<?php echo $th->get_field_name('title'); ?>"  type="text" value="<?php echo esc_attr($title); ?>" /></p>
    <p><?php _e('Number of Items') ?>: <input class="widefat" name="<?php echo $th->get_field_name('num_items'); ?>"  type="text" value="<?php echo esc_attr($num_items); ?>" /></p>
    <p><?php _e('Background Colour (HEX value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('bg_color'); ?>"  type="text" value="<?php echo esc_attr($bg_color); ?>" /></p>
    <p><?php _e('Border Width (Pixel value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('border_width'); ?>"  type="text" value="<?php echo esc_attr($border_width); ?>" /></p>
    <p><?php _e('Border Colour (HEX value)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('border_color'); ?>"  type="text" value="<?php echo esc_attr($border_color); ?>" /></p>
    <p><?php _e('Filter by gift name (e.g. laptop)') ?>: <input class="widefat" name="<?php echo $th->get_field_name('feature'); ?>"  type="text" value="<?php echo esc_attr($feature); ?>" /></p>    
    <?php
}
// ----------------------------------------------------------------------------
//  END - 1. Functions to refactor widget rendering code
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
//  START - 2. Misc widget modularised functions
// ----------------------------------------------------------------------------
// New module registration loop
require_once(CP_BASE_DIR . '/lib/nusoap.php');
$client = new soapclientNusoap("http://comparepress.com/soap/moduleReg.php");
try {
    $results = $client->call("getAllModules");
    foreach ($results["results"] as $regkey => $regval) {
        $widget_options = get_option("widget_ComparePressModules");
        $option_name = "ComparePress_module_" . $regval['module_id'];
        if ($widget_options[$option_name] == "y") {
            include (CP_BASE_DIR . "/modules/UK/" . $regval['module_id'] . "/" . $regval['module_id'] . "_shortcodes.php");
            include (CP_BASE_DIR . "/modules/UK/" . $regval['module_id'] . "/" . $regval['module_id'] . "_module.php");
        }
    }
} catch (SoapFault $exception) {
    echo $exception;
}

// Function to register widgets in WP (modularised)
// Callback function for hook: 'widgets_init' (triggered after default WP widgets registered)
function ComparePress_init() {
    $client = new soapclientNusoap("http://comparepress.com/soap/moduleReg.php");
    try {
        $results = $client->call("getAllModules");
        foreach ($results["results"] as $key => $val) {
            $widget_options = get_option("widget_ComparePressModules");
            $option_name = "ComparePress_module_" . $val['module_id'];
            if ($widget_options[$option_name] == 'y') {
                $initproc = $val['module_id'] . "_init";
                if (function_exists($initproc)) {      // Initialise widgets ONLY if they are defined
                    $geo_options = get_option("widget_geo_options");
                    if ($geo_options["chk_geoip_UK"] == "1") {
                        if (check_user_country())
                            $initproc(); // Initialise widgets ONLY if user is from UK
                    }
                    else {
                        $initproc(); // Show to everyone
                    }
                }
            }
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }
}

// Check if user country is UK, from IP address
function check_user_country($country_id = "UK") {
    $ip = $_SERVER['REMOTE_ADDR'];
    $ctry_code = countryCityFromIP($ip);
    if (strtoupper($ctry_code["country_code"]) == $country_id) {
        return true;
    } else {
        return false;
    }
}

// Checks if user has added a '#' char to the colour value. If not, then one is added.
function colour_hash_value($colour_str) {
    if (substr($colour_str, 0, 1) != '#') {
        $colour_str = "#" . $colour_str;
    }
    return $colour_str;
}

function countryCityFromIP($ipAddr) {
// function to find country and city from IP address of visitor to disable ComparePress if affilaite want this to happen.
// not currently used due to issues
// Developed by Roshan Bhattarai [url]http://roshanbh.com.np[/url]
// verify the IP address 
    ip2long($ipAddr) == -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
    $ipDetail = array(); //initialize a blank array
//get the XML result from hostip.info
    $xml = file_get_contents("http://api.hostip.info/?ip=" . $ipAddr);

//get the city name inside the node <gml:name> and </gml:name>
    preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si", $xml, $match);

//assing the city name to the array
    $ipDetail["city"] = isset($match[2]);

//get the country name inside the node <countryName> and </countryName>
    preg_match("@<countryName>(.*?)</countryName>@si", $xml, $matches);

//assign the country name to the $ipDetail array
    $ipDetail['country'] = $matches[1];

//get the country name inside the node <countryName> and </countryName>
    preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si", $xml, $cc_match);
    $ipDetail['country_code'] = $cc_match[1]; //assing the country code to array
//return the array containing city, country and country code
    return $ipDetail;
}
// ----------------------------------------------------------------------------
//  END - 2. Misc widget modularised functions
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
//  START - 3. ComparePressFakePage class
// ----------------------------------------------------------------------------
/*
 * The following ComparePressFakePage class is based on the Fake Page Plugin 2 by Scott Sherrill-Mix
 * Plugin URI: http://scott.sherrillmix.com/blog/blogger/creating-a-better-fake-post-with-a-wordpress-plugin/
 * Used to create fake / virtual pages for the ComparePress comparison system
 * @TODO: Try to use a fake custom post type for this instead
 */
class ComparePressFakePage {

    var $page_slug;
    var $page_title;
    var $page_content;
    var $ping_status = 'open';

    function ComparePressFakePage($options) {
        $this->page_slug = $options['slug'];
        $this->page_title = $options['title'];
        $this->page_content = $options['content'];
        /*
         * New template redirect function so user can use a new WordPress template
         * just for the fake (mobile phone deals) pages
         */
        add_action('template_redirect', array(&$this, 'getTemplate'));
        add_filter('the_posts', array(&$this, 'detectPost'));
    }

    /*
     * New template redirect function
    */

    function getTemplate() {
        # If a user wants to customise the look of the fake deals page they need to create this page:
        $this->_template = 'compare-press-fake-page.php';

        if (file_exists(TEMPLATEPATH . '/' . $this->_template))
            include(TEMPLATEPATH . '/' . $this->_template);
        else
        # use standard single.php
            include(TEMPLATEPATH . '/single.php');
        die();
    }

    function createPost() {
        $post = new stdClass;
        $post->post_author = 1;
        $post->post_name = $this->page_slug;
        $post->guid = get_bloginfo('wpurl') . '/' . $this->page_slug;
        $post->post_title = $this->page_title;
        $post->post_content = $this->getContent();
//  Need to work on how to fake $post->ID so we can tap into plugins like Facebook one
//  $post->ID = 1;
//  Make this a page so we don't get post_type errors
        $post->post_type = 'page';
        $post->post_status = 'publish';
        $post->comment_status = 'closed';
        $post->ping_status = 'closed';
        $post->comment_count = 0;
        $post->post_date = current_time('mysql');
        $post->post_date_gmt = current_time('mysql', 1);
        /* Can specify a fake post parent - good for breadcrumbs */
//        $post->post_parent = 12;                 
        return $post;
    }

    function getContent() {
        return $this->page_content;
    }

    function detectPost($posts) {
        global $wp;
        global $wp_query;
        if ($this->requestingThisPage()) {
            $posts = NULL;
            $posts[] = $this->createPost();
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
            $wp_query->is_home = false;
            $wp_query->is_archive = false;
            $wp_query->is_category = false;
            unset($wp_query->query["error"]);
            $wp_query->query_vars["error"] = "";
            $wp_query->is_404 = false;
        }
        return $posts;
    }

    function requestingThisPage() {
        global $wp;
        global $wp_query;
        return strtolower($wp->request) == strtolower($this->page_slug) || isset($wp->query_vars['page_id']) == $this->page_slug;
    }

}
// ----------------------------------------------------------------------------
//  END - 3. ComparePressFakePage class
// ----------------------------------------------------------------------------
?>