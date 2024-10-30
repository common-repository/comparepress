<?php
/*
  ComparePress Module: UK Video Games
  Version: 2.0.1
  Description: Module for adding ComparePress UK Video Games based widgets to a site
  Current Status: Placeholder - module is not selectable by ANY users as this is not 100% finished
  @TODO: Check site with DEBUG bar for warnings etc 
  @TODO: Need to make sure Sort By label is hidden when boxes are ticked.
  @TODO: Check site with DEBUG bar for warnings etc
 */

// -----------------------------------------------------------
// START - Latest UK Games Widget Class
// -----------------------------------------------------------
class widget_ComparePressUKGamesLatest extends WP_Widget {

    // Constructor - process new widget
    function widget_ComparePressUKGamesLatest() {
        $widget_ops = array('classname' => 'widget_ComparePressUKGamesLatest', 'description' => __('ComparePress Latest UK Games'));
        $this->WP_Widget('widget_ComparePressUKGamesLatest_id', __('ComparePress Latest UK Games'), $widget_ops);
    }

    // Build widget settings form
    function form($instance) {
        ComparePress_latest_control($this, $instance);
    }

    // Save widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['num_items'] = strip_tags($new_instance['num_items']);
        $instance['bg_color'] = strip_tags($new_instance['bg_color']);
        $instance['border_width'] = strip_tags($new_instance['border_width']);
        $instance['border_color'] = strip_tags($new_instance['border_color']);

        return $instance;
    }

    // Display widget
    function widget($args, $instance) {
        // Use existing function within comparepress/admin/utility/utility_functions.php
        widget_ComparePresslatest($args, $instance);
    }

}

// -----------------------------------------------------------
// END - Latest UK Games Widget Class
// -----------------------------------------------------------

// -----------------------------------------------------------
// START - Top Games Widget Class
// -----------------------------------------------------------
class widget_ComparePress_UK_Games_Top extends WP_Widget {

    // Constructor - process new widget
    function widget_ComparePress_UK_Games_Top() {
        $widget_ops = array('classname' => 'widget_ComparePress_UK_Games_Top', 'description' => __('ComparePress Top UK Games'));
        $this->WP_Widget('widget_ComparePress_UK_Games_Top_id', __('ComparePress Top UK Games'), $widget_ops);
    }

    // Build widget settings form
    function form($instance) {
        ComparePress_top_control($this, $instance);
    }

    // Save widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['num_items'] = strip_tags($new_instance['num_items']);
        $instance['bg_color'] = strip_tags($new_instance['bg_color']);
        $instance['border_width'] = strip_tags($new_instance['border_width']);
        $instance['border_color'] = strip_tags($new_instance['border_color']);
        return $instance;
    }

    // Display widget
    function widget($args, $instance) {
        // Use existing function within comparepress/admin/utility/utility_functions.php
        widget_ComparePresstop($args, $instance);
    }

}
// -----------------------------------------------------------
// END - Top UK Games Widget Class
// -----------------------------------------------------------

// -----------------------------------------------------------
// START - Search Games Widget Class 
// -----------------------------------------------------------
class widget_ComparePressUKGamesSearch extends WP_Widget {

    // Constructor - process new widget
    function widget_ComparePressUKGamesSearch() {
        $widget_ops = array('classname' => 'widget_ComparePressUKGamesSearch', 'description' => __('ComparePress UK Games Search'));
        $this->WP_Widget('widget_ComparePressUKGamesSearch_id', __('ComparePress UK Games Search'), $widget_ops);
    }

    // Build widget settings form
    function form($instance) {
        ComparePress_UKGamesSearch_control($this, $instance);
    }

    // Save widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['showlabels'] = strip_tags($new_instance['showlabels']);
        $instance['bg_color'] = strip_tags($new_instance['bg_color']);
        $instance['labeltextcolor'] = strip_tags($new_instance['labeltextcolor']);
        $instance['border_width'] = strip_tags($new_instance['border_width']);
        $instance['border_color'] = strip_tags($new_instance['border_color']);
        return $instance;
    }

    // Display widget
    function widget($args, $instance) {
        // Use existing function
        widget_ComparePresssUKGamesearch($args, $instance);
    }
}
//// -----------------------------------------------------------
//// END - Search Games Widget Class
//// -----------------------------------------------------------

//Initialise the widgets
function games_init() {
    global $module;
    $module = "games";
    
    register_widget('widget_ComparePressUKGamesSearch');    
    register_widget('widget_ComparePressUKGamesLatest');
    register_widget('widget_ComparePress_UK_Games_Top');
}

// Function for inserting UK Vidoe Games specific CSS/JS into header
function head_games() {
    wp_enqueue_style('ComparePress-UK-Games', CP_BASE_URL . '/modules/UK/games/css/games.css');
    wp_register_script('CP_ajax', CP_BASE_URL . '/modules/UK/games/search/ajax.js');
    wp_enqueue_script('CP_ajax');
}

function latestgames($instance) {
    $options = get_option("widget_ComparePresslatest");
    $url = get_option("widget_ComparePressurls");

    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix-games'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix-games'];
    } else {
        if ($url['m4e_simpleurl_games'] != "")
            $CP_fake_Category = $url['m4e_simpleurl_games'];
        else
            $CP_fake_Category = 'compare-games-deals';
    }

    global $validate;
    // connect and get the latest games
    $send = array(array("token" => $validate['m4e_token'], "sort" => 4, "releasefrom" => date("d/m/y", time() - (7 * 24 * 60 * 60))));

    include_once(CP_BASE_DIR . '/lib/nusoap.php');
    $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");

    try {
        $results = $client->call("getGamesByQuery", $send);
        $count = 0;
        $output = '';
        foreach ($results["games"]["game"] as $key => $val) {
            if ($count < ($instance['num_items'] > 1 ? $instance['num_items'] : "5")) {
                $output .= "<div class=\"CP_games_latest_games_widget_hs\">
						<p> 
						<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val["id"]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val["title"] . " deals\">
                                                <div><img src=\"" . $val['thumbnailURL'] . "\" alt=\"" . $val["title"] . "\"></a>
                                                <a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val["id"]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val["title"] . " deals\">
						<br />" . $val["title"] . "</a></div></p></div>
						";
            }
            $count++;
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }
    echo "<div id=\"CP_games_latest_games_widget\">
		" . $output . "<div class=\"CP-clearfix\"></div>
		</div>";
}

function topgames($instance) {
    $options = get_option("widget_ComparePresstop");
    $url = get_option("widget_ComparePressurls");

    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix-games'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix-games'];
    } else {
        if ($url['m4e_simpleurl_games'] != "")
            $CP_fake_Category = $url['m4e_simpleurl_games'];
        else
            $CP_fake_Category = 'compare-games-deals';
    }

    global $validate;
    // connect and get the top games
    $send = array(array("token" => $validate['m4e_token'], "sort" => 4, "releasefrom" => date("d/m/y", time() - (365 * 24 * 60 * 60)))); // Last 365 days only

    include_once(CP_BASE_DIR . '/lib/nusoap.php');
    $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");
    try {
        $results = $client->call("getGamesByQuery", $send);
        $count = 0;
        $output ='';
        foreach ($results["games"]["game"] as $key => $val) {
            if ($count < ($instance['num_items'] > 1 ? $instance['num_items'] : "5")) {
                $output .= "<div class=\"CP_games_top_games_widget\">
                            <p> 
                            <a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val["id"]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val["title"] . " deals\">
                            <div><div style='height: 70px'><img src=\"" . $val['thumbnailURL'] . "\" alt=\"" . $val["title"] . "\"></div></a>
                            <a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val["id"]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val["title"] . " deals\">
                            <br /><p class='from_price'>&pound;" . money_format('%.2n', $val['minimumPrice']) . "</p>" . $val["title"] . "</a></div></p></div>
                            ";
            }
            $count++;
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }
    echo "<div id=\"CP_games_top_games_widget\">
		" . $output . "<div class=\"CP-clearfix\"></div>
		</div>";
}

function games_dealSearch($instance) {
    $options = get_option("widget_ComparePress");
    // Gets the user's URL preferrence
    $url = get_option("widget_ComparePressurls");
    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix-games'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix-games'];
        // used for base deal search url
        $CP_Deal_Search_URL = '';
    } else {
        if ($url['m4e_simpleurl_games'] != "")
            $CP_fake_Category = $url['m4e_simpleurl_games'];
        else
            $CP_fake_Category = 'compare-games-deals';
        $CP_Deal_Search_URL = "/" . $CP_fake_Category . "/";
    }

    echo '<form action="' . $CP_Deal_Search_URL . '" id="form">
		<input type="hidden" name="page" value="1">
		
  		<div id="CP_games_search_widget">
			<div class="module">
				<div class="CP_games_search_widget_title">Platform</div>
					<div class="inputbox">
						<select name="platform">';
    include_once(CP_BASE_DIR . '/lib/nusoap.php');
    $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");

    $results = $client->call("getPlatforms");
    if (!$results) {
        echo "<option>no results</option>";
    } else {
        echo "<option value='all'>All Platforms</option>";
        foreach ($results["gamePlatforms"]["gamePlatform"] as $key => $val) {
            echo "<option value='$val[platform]'" . ($_GET['platform'] == $val[platform] ? " selected " : "") . ">$val[platformName]</option>";
        }
    }
    echo '</select>';
    echo '
					</div>
			  	</div>
				
			<div class="module">
				<div class="CP_games_search_widget_title">Title</div>
				<div class="inputbox">
					<input type="text" name="game" id="game" value="' . ($_GET['game'] == "" ? 'Search Games...' : $_GET['game']) . '" onfocus="this.value = ( this.value == \'Search Games...\' ) ? \'\' : this.value;return true;"/>

				</div>
			</div>
					  					
				<div class="module">
					<div class="CP_games_filter_widget_title">Sort by</div>
                     <div class="inputbox">
						<select name="sort" id="sort">
							<option value="0">Best Match</option>
							<option value="1">Total Cost</option>
							<option value="2">Alphabetically</option>
							<option value="3">Release Date</option>
							<option value="4">Most Popular</option>
						</select>
					</div>
				</div>
				<br />
			';

    /**
     * Code that deals with the non SEO deal searh
     *
     * @todo
     * - Check all possible SEO urls work ok..
     */
    if ($url['m4e-htaccessurl-games'] != "") {
        echo "<div class=\"submit\">
<input type=\"submit\" name=\"CP_games_search_widget\" value=\"submit\" class=\"m4e_submitbutton\" onclick='location.href= \"" . get_option('siteurl') . "/" . $CP_fake_Category . "/\" + this.form.platform.value + \"/\" + this.form.game.value + \"/\" + this.form.sort.value + \"/\" + this.form.page.value + \"/\";return false;' />


			</div>
		</div>
		</form>";
    } else {
        echo "<div class=\"submit\">
				<input type=\"submit\" name=\"CP_games_search_widget\" value=\"submit\" class=\"m4e_submitbutton\" />
			</div>
		</div>
		</form>";
    }
}

// -----------------------------------------------------------
// START - Search Games Widget 
// -----------------------------------------------------------
function widget_ComparePresssUKGamesearch($args, $instance) {
    global $module;
    extract($args);
    $bgcol = ($instance["bg_color"] == "" ? "#ffffff" : $instance["bg_color"]);
    $bd_th = ($instance["border_width"] == "" ? "0" : $instance["border_width"]);
    $bd_col = ($instance["border_color"] == "" ? "#C0C0C0" : $instance["border_color"]);
    $txt_col = ($instance["labeltextcolor"] == "" ? "#000000" : $instance["labeltextcolor"]);
    $bgcol = colour_hash_value($bgcol);
    $bd_col = colour_hash_value($bd_col);
    $txt_col = colour_hash_value($txt_col);

    if ($instance["showlabels"] == '1') {
        $show_labels = ".CP_games_search_widget_title { display:block; }";
    } else {
        $show_labels = ".CP_games_search_widget_title {display:none;}";
    }

    echo "<style type=\"text/css\">#" . $args['widget_id'] . " { margin-bottom:10px; background-color: " . $bgcol . "; border: " . $bd_th . "px solid " . $bd_col . "; }</style>";
    echo "<style type=\"text/css\">#CP_games_search_widget{ padding:8px; } .CP_games_search_widget_title, .CP_games_search_widget_title_sort { color:" . $txt_col . ";} " . $show_labels . " </style>";
    echo "<div>";
    echo $before_widget;
    echo $before_title;
    echo $instance['title'];
    echo $after_title;
    $top_proc = $module . "_dealSearch";
    $top_proc($instance);
    echo $after_widget;
    echo "</div>";
}

// Widget controls for search items widget (non-modularised as it is unique)
function ComparePress_UKGamesSearch_control(&$th, $instance) {
    $defaults = array('title' => '', 'showlabels' => '1', 'bg_color' => '', 'labeltextcolor' => '', 'border_width' => '0', 
        'border_color' => '');                   
    $instance = wp_parse_args((array) $instance, $defaults);
    $title = strip_tags($instance['title']);
    $show_labels = strip_tags($instance['showlabels']);
    $bg_color = strip_tags($instance['bg_color']);
    $label_text_color = strip_tags($instance['labeltextcolor']);
    $border_width = strip_tags($instance['border_width']);
    $border_color = strip_tags($instance['border_color']);
    ?>
    <p><?php _e('Title') ?>: <input class="widefat" name="<?php echo $th->get_field_name('title'); ?>"  type="text" value="<?php echo esc_attr($title); ?>" /></p>
    <p>
        <input class="checkbox" name="<?php echo $th->get_field_name('showlabels'); ?>" type="checkbox" <?php checked($show_labels, 1); ?> value="1" />
        <label for="<?php echo $th->get_field_name('showlabels'); ?>"> <?php _e('Show Labels') ?></label><br />
    </p>
    <!-- convert these and incorporate the ones below  -->
    <p><?php _e('Background Colour (HEX value): ') ?>: <input class="widefat" name="<?php echo $th->get_field_name('bg_color'); ?>"  type="text" value="<?php echo esc_attr($bg_color); ?>" /></p>
    <p><?php _e('Border Width (Pixel value): ') ?>: <input class="widefat" name="<?php echo $th->get_field_name('border_width'); ?>"  type="text" value="<?php echo esc_attr($border_width); ?>" /></p>
    <p><?php _e('Label Text Colour') ?>: <input class="widefat" name="<?php echo $th->get_field_name('labeltextcolor'); ?>"  type="text" value="<?php echo esc_attr($label_text_color); ?>" /></p>
    <p><?php _e('Border Colour (HEX value): ') ?>: <input class="widefat" name="<?php echo $th->get_field_name('border_color'); ?>"  type="text" value="<?php echo esc_attr($border_color); ?>" /></p>
    <?php
}

// Function to show module homepage on static pages or using [] shortcode - moved from comparepress.php
function sc_homecontent() {
    /**
     * Adds the ComparePress Home Page Content contents on ANY blog HOME page that is a static page or
     * if user wants can use the shortocde [homecontent] and not tick the Activate Homepage Phone Handsets
     * box to show this same content on any other blog page
     * @todo
     * - If the user has the latest blog posts set as home page then this will repeat on each blog post
     * - User will need to add the following to thier index.php template using the show_games fucntion:
     * - <?php show_games(); ?>
     */
    $DataToAppend = CP_BASE_DIR . '/modules/UK/games/search/home-page-deals_new.php';
    ob_start();
    include($DataToAppend);
    $yourfile = ob_get_clean();
    return $yourfile;
    ob_end_flush();
    exit();
}

// Function to create fake pages 
function show_page($content) {
    /**
     * Show the plugin content on the pages which have added any custom fields
     * e.g. here http://mysite.com/mobile-phones/windows-mobile/
     * @todo : nothing?
     *
     */
    global $wp_query;
    //$content = get_the_content();
    // grab the custom fields
    $title = get_post_meta($wp_query->post->ID, "title", true);
    $platform = get_post_meta($wp_query->post->ID, "platform", true);
    $id = get_post_meta($wp_query->post->ID, "id", true);
    $DataToAppend  ='';
    // if we have custom fields, format and include
    if ($title) {
        $DataToAppend = "<br />[showgames title='" . $title . "']";
    }

    if ($platform) {
        $DataToAppend = "<br />[showgames platform='" . $platform . "']";
    }

    if ($id) {
        $DataToAppend = "<br />[showgames id='" . $id . "' type='full']";
    }

    //Make sure we get the p tags etc included
    if (function_exists('eval_php')) {
        $openTagPos = stripos($content, '<?php');
        $closeTagPos = stripos($content, '?>');
        if ($openTagPos && $closeTagPos) {
            $contentLeft = substr($content, 0, $openTagPos - 1);
            $contentPHP = substr($content, $openTagPos, ($closeTagPos - $openTagPos) + 2);
            $contentRight = substr($content, $closeTagPos, strlen($content) - ($closeTagPos + 2));
            $content = wpautop($contentLeft) . $contentPHP . wpautop($contentRight);
        } else {
            $content = wpautop($content);
        }
    } else {
        $content = wpautop($content);
    }

    return $content . $DataToAppend;
}

# Start checking to see what sort of ComparePress based query we are dealing with
if (($_GET['platform'] != "") || ($_GET['name'] != "") || ($_GET['game'] != "")) {
# This gets the user's simple url settings to create the fake page slug
    $options = get_option('widget_ComparePressurls');
    $m4eDealUrl = $options['m4e_simpleurl_games'];

    include(CP_BASE_DIR . '/modules/UK/games/search/games_search_results.php');

    new ComparePressFakePage(array(
                'slug' => '' . $m4eDealUrl . '',
                'title' => $ComparePressPageTitle,
                'content' => $comparePressMobile
            ));
} else if (($homepage == get_option('siteurl') ) && ($validate['m4e_hpactivate'] == "y")) {
    add_filter('the_content', 'deals_hp_new');
} else if ($_GET['id'] != "") {
    # This gets the user's simple url settings to create the fake page slug
    $options = get_option('widget_ComparePressurls');

    $m4eDealUrl = $options['m4e_simpleurl_games'];
    include(CP_BASE_DIR . '/modules/UK/games/search/games_price_results.php');

    new ComparePressFakePage(array(
                'slug' => '' . $m4eDealUrl . '',
                'title' => $ComparePressPageTitle,
                'content' => $comparePressMobile
            ));
} else if ($_GET['platformpage'] != "") {
    $pagestring = " page=" . $_GET['page'];
    if ($_GET['categorypage'] != "") {
        if (is_numeric($_GET['categorypage'])) {
            $comparePressMobile = "<br />[showgames platform='" . $_GET['platformpage'] . "' format='long' page=" . $_GET['categorypage'] . "]";
        } else if ($_GET['categorypage'] == "action") {
            $comparePressMobile = "<br />[showgames platform='" . $_GET['platformpage'] . "' category='action%20and%20shooter' format='long'" . $pagestring . "]";
        } else if ($_GET['categorypage'] == "arcade") {
            $comparePressMobile = "<br />[showgames platform='" . $_GET['platformpage'] . "' category='Arcade%20and%20Platform' format='long'" . $pagestring . "]";
        } else {
            $comparePressMobile = "<br />[showgames platform='" . $_GET['platformpage'] . "' category='" . $_GET['categorypage'] . "' format='long'" . $pagestring . "]";
        }
    } else {
        $comparePressMobile = "<br />[showgames platform='" . $_GET['platformpage'] . "'" . $pagestring . "]";
    }

    $m4eDealUrl = $options['m4e_simpleurl_games'];

    new ComparePressFakePage(array(
                'slug' => '' . $m4eDealUrl . '',
                'title' => $ComparePressPageTitle,
                'content' => $comparePressMobile
            ));
} else
/**
 * This ia an actual WordPress page the user has created and has added the
 * ComparePress content to it via the shortcodes
 * e.g. have [showgames platform='Xbox360' format='long']
 * which gives them
 * e.g. http://gamesprices.co.uk/xbox-360/
 */ {
    add_filter('the_content', 'show_page');
}

// this is the home page shortcode call
add_shortcode('homecontent', 'sc_homecontent');

// this is for content page hames call
add_shortcode('showgames', 'sc_showgames');

add_shortcode('showhardware', 'sc_showhardware');

//////////////////////////////////////////////////////////////
//// Deprectated stuff / to work on?    
//////////////////////////////////////////////////////////////

//function relatedgames() {
//    $options = get_option("widget_ComparePressrelated");
//    $url = get_option("widget_ComparePressurls");
//
//    // logic to see what sort of URLS we are using...
//    if ($url['ComparePress-htaccessPrefix-games'] != "") {
//        $CP_fake_Category = $url['ComparePress-htaccessPrefix-games'];
//    } else {
//        if ($url['m4e_simpleurl_games'] != "")
//            $CP_fake_Category = $url['m4e_simpleurl_games'];
//        else
//            $CP_fake_Category = 'compare-games-deals';
//    }
//
//    global $validate;
//    // connect and get the top games
//    $send = array(array("token" => $validate['m4e_token'], "sort" => 4, "releasefrom" => date("d/m/y", time() - (365 * 24 * 60 * 60)))); // Last 365 days only
//
//    include_once(CP_BASE_DIR . '/lib/nusoap.php');
//    $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");
//
//    try {
//        $results = $client->call("getGamesByQuery", $send);
//        $count = 0;
//        foreach ($results[games][game] as $key => $val) {
//            if ($count < $options[topnumber]) {
//                $output .= "<div class=\"CP_games_top_games_widget\">
//						<p> 
//						<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val["id"]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val["title"] . " deals\">
//                                                <div><div style='height: 70px'><img src=\"" . $val['thumbnailURL'] . "\" alt=\"" . $val["title"] . "\"></div></a>
//                                                <a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val["id"]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val["title"] . " deals\">
//						<br /><p class='from_price'>&pound;" . money_format('%.2n', $val['minimumPrice']) . "</p>" . $val["title"] . "</a></div></p></div>
//						";
//            }
//            $count++;
//        }
//    } catch (SoapFault $exception) {
//        echo $exception;
//    }
//    echo "<div id=\"CP_games_top_games_widget\">
//		" . $output . "<div class=\"CP-clearfix\"></div>
//		</div>";
//}  


//function widget_ComparePressrelated($args) {
//    if ($_GET['id'] != "") {
//        global $module;
//        extract($args);
//        $options = get_option("widget_ComparePressrelated");
//        echo $before_widget;
//        echo $before_title;
//        echo $options['toptitle'];
//        echo $after_title;
//        $top_proc = "related" . $module;
//        $top_proc();
//        echo $after_widget;
//    }
//}
?>