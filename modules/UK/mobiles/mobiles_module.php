<?php
/*
  ComparePress Module: Mobiles
  Version: 2.0.7
  Description: Module for adding ComparePress mobile phone based widgets to a site
  @TODO: Create Widget to show a user generated custom commma seperated list of any phones in the database
 */

// -----------------------------------------------------------
// START - Top Phones Widget Class
// -----------------------------------------------------------
class widget_ComparePressTopPhones extends WP_Widget {

    // Constructor - process new widget
    function widget_ComparePressTopPhones() {
        $widget_ops = array('classname' => 'widget_ComparePressTopPhones', 'description' => __('ComparePress Top Phones'));
        $this->WP_Widget('widget_ComparePressTopPhones_id', __('ComparePress Top Phones'), $widget_ops);
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
        $instance['feature'] = strip_tags($new_instance['feature']);
        $instance['linktext'] = $new_instance['linktext'];
        return $instance;
    }

    // Display widget
    function widget($args, $instance) {
        // Use existing function
        widget_ComparePresstop($args, $instance);
    }

}
// -----------------------------------------------------------
// END - Top Phones Widget Class
// -----------------------------------------------------------

// -----------------------------------------------------------
// START - Latest Phones Widget Class
// -----------------------------------------------------------
class widget_ComparePressLatestPhones extends WP_Widget {

    // Constructor - process new widget
    function widget_ComparePressLatestPhones() {
        $widget_ops = array('classname' => 'widget_ComparePressLatestPhones', 'description' => __('ComparePress Latest Phones'));
        $this->WP_Widget('widget_ComparePressLatestPhones_id', __('ComparePress Latest Phones'), $widget_ops);
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
        $instance['feature'] = strip_tags($new_instance['feature']);
        return $instance;
    }

    // Display widget
    function widget($args, $instance) {
        // Use existing function
        widget_ComparePresslatest($args, $instance);
    }
}
// -----------------------------------------------------------
// END - Latest Phones Widget Class
// -----------------------------------------------------------

// -----------------------------------------------------------
// START - Free Gifts Widget Class
// -----------------------------------------------------------
class widget_ComparePressFreeGifts extends WP_Widget {

    // Constructor - process new widget
    function widget_ComparePressFreeGifts() {
        $widget_ops = array('classname' => 'widget_ComparePressFreeGifts', 'description' => __('ComparePress Free Gifts'));
        $this->WP_Widget('widget_ComparePressFreeGifts_id', __('ComparePress Free Gifts'), $widget_ops);
    }

    // Build widget settings form
    function form($instance) {
        ComparePress_FreeGifts_control($this, $instance);
    }

    // Save widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['num_items'] = strip_tags($new_instance['num_items']);
        $instance['bg_color'] = strip_tags($new_instance['bg_color']);
        $instance['border_width'] = strip_tags($new_instance['border_width']);
        $instance['border_color'] = strip_tags($new_instance['border_color']);
        $instance['feature'] = strip_tags($new_instance['feature']);
        return $instance;
    }

    // Display widget
    function widget($args, $instance) {
        // Use existing function
        widget_ComparePressFreeGifts($args, $instance);
    }
}
// -----------------------------------------------------------
// END - Latest Phones Widget Class
// -----------------------------------------------------------

// ---------------------------------------
// START - Search Phones Widget Class
// -----------------------------------------------------------
class widget_ComparePressSearchPhones extends WP_Widget {

    // Constructor - process new widget
    function widget_ComparePressSearchPhones() {
        $widget_ops = array('classname' => 'widget_ComparePressSearchPhones', 'description' => __('ComparePress Mobile Search'));
        $this->WP_Widget('widget_ComparePressSearchPhones_id', __('ComparePress Mobile Search'), $widget_ops);
    }

    // Build widget settings form
    function form($instance) {
        ComparePress_MobileSearch_control($this, $instance);
    }

    // Save widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['showmins'] = strip_tags($new_instance['showmins']);
        $instance['showlinerental'] = strip_tags($new_instance['showlinerental']);
        $instance['shownetworks'] = strip_tags($new_instance['shownetworks']);
        $instance['showdata'] = strip_tags($new_instance['showdata']);
        $instance['showcashback'] = strip_tags($new_instance['showcashback']);
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
        widget_MobileComparePresssearch($args, $instance);
    }
}

// -----------------------------------------------------------
// END - Search Phones Widget Class
// -----------------------------------------------------------
// Function for displaying ComparePress search widget (non-modularised - unique)
function widget_MobileComparePresssearch($args, $instance) {
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
        $show_labels = ".CP_mobiles_search_widget_title { display:block; }";
    } else {
        $show_labels = ".CP_mobiles_search_widget_title {display:none;}";
    }

    echo "<style type=\"text/css\">#" . $args['widget_id'] . " { margin-bottom:10px; background-color: " . $bgcol . "; border: " . $bd_th . "px solid " . $bd_col . "; }</style>";
    echo "<style type=\"text/css\">#CP_mobiles_search_widget{ padding:8px; } .CP_mobiles_search_widget_title, .CP_mobiles_search_widget_title_sort { color:" . $txt_col . ";} " . $show_labels . " </style>";
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

function mobiles_init() {
    global $module;
    $module = "mobiles";
    register_widget('widget_ComparePressTopPhones');
    register_widget('widget_ComparePressLatestPhones');
    register_widget('widget_ComparePressFreeGifts');
    register_widget('widget_ComparePressSearchPhones');
}
// Function for inserting mobiles-specific CSS/JS into header
function head_mobiles() {

    wp_register_script('CP_ajax', CP_BASE_URL . '/modules/UK/mobiles/search/ajax.js');
    wp_enqueue_script('CP_ajax');
    wp_register_script('CP_ask', CP_BASE_URL . '/modules/UK/mobiles/search/ask.js');
    wp_enqueue_script('CP_ask');

    if (!is_admin()) { // add the css to non-admin pages
        wp_enqueue_style('CP_mobiles_css', CP_BASE_URL . '/modules/UK/mobiles/css/mobiles.css');
        // Removed as this is now standard - forAJAX search shortcode
        // wp_enqueue_script('jquery', CP_BASE_URL . '/modules/UK/mobiles/search/js/jquery-1.4.4.min.js');
        wp_enqueue_script('myJavascript', CP_BASE_URL . '/modules/UK/mobiles/search/js/cpnav2.js', array('jquery'));
        wp_enqueue_script('myJavascript', CP_BASE_URL . '/modules/UK/mobiles/search/js/cpnav2.js', array('jquery'));
        wp_enqueue_script('jQueryGUI', CP_BASE_URL . '/modules/UK/mobiles/search/js/jquery-ui-1.10.0.custom.min.js', array('jquery'));
        wp_enqueue_style('newsearchnav', CP_BASE_URL . '/modules/UK/mobiles/search/css/newsearchnav.css');
    }
}

function mobiles_dealSearch($instance) {

    $options = get_option("widget_ComparePress");
    // Gets the user's URL preferrence
    $url = get_option("widget_ComparePressurls");
    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix'];
        // used for base deal search url
        $CP_Deal_Search_URL = '';
    } else {
        if ($url['m4e_simpleurl'] != "")
            $CP_fake_Category = $url['m4e_simpleurl'];
        else
            $CP_fake_Category = 'compare-mobile-deals';

        $CP_Deal_Search_URL = "/" . $CP_fake_Category . "/";
    }

    if ($instance['showmins'] == "1") {
        $cpsearchmins = '';
        $cpsearchtexts = '';
        if (isset($_GET['minutes'])) {
            $cpsearchmins = $_GET['minutes'];
        }
        if (isset($_GET['texts'])) {
            $cpsearchtexts = $_GET['texts'];
        }
        $minute_search = "<div class=\"module\">
                            <div class=\"CP_mobiles_search_widget_title\">Minutes</div>
                                <div class=\"inputbox\">
                                    <select name=\"minutes\">
                                    <option value=\"-\">Any Minutes</option>
                                    <option value=\"less499\"" . ($cpsearchmins == 'less499' ? 'selected="selected"' : '') . ">less than 500</option>
                                    <option value=\"more500\"" . ($cpsearchmins == 'more500' ? 'selected="selected"' : '') . ">500 or more</option>
                                    <option value=\"more700\"" . ($cpsearchmins == 'more700' ? 'selected="selected"' : '') . ">700 or more</option>
                                    <option value=\"more1000\"" . ($cpsearchmins == 'more1000' ? 'selected="selected"' : '') . ">1000 or more</option>
                                    <option value=\"more1200\"" . ($cpsearchmins == 'more1200' ? 'selected="selected"' : '') . ">1200 or more</option>
                                    <option value=\"more2000\"" . ($cpsearchmins == 'more2000' ? 'selected="selected"' : '') . ">2000 or more</option>
                                    </select>
                                </div>

                           <div class=\"module\">
                            <div class=\"CP_mobiles_search_widget_title\">Texts</div>
                                <div class=\"inputbox\">
                                    <select name=\"texts\">
                                    <option value=\"-\">Any Texts</option>
                                    <option value=\"more100\"" . ($cpsearchtexts == 'more100' ? 'selected="selected"' : '') . ">100 or more</option>
                                    <option value=\"more300\"" . ($cpsearchtexts == 'more300' ? 'selected="selected"' : '') . ">300 or more</option>
                                    <option value=\"more400\"" . ($cpsearchtexts == 'more400' ? 'selected="selected"' : '') . ">400 or more</option>
                                    <option value=\"more500\"" . ($cpsearchtexts == 'more500' ? 'selected="selected"' : '') . ">500 or more</option>
                                    <option value=\"more750\"" . ($cpsearchtexts == 'more750' ? 'selected="selected"' : '') . ">750 or more</option>
                                    <option value=\"more1000\"" . ($cpsearchtexts == 'more1000' ? 'selected="selected"' : '') . ">1000 or more</option>
                                    </select>
                                </div>
                          </div>";
    } else {
        $minute_search = "<input type=\"hidden\" name=\"minutes\" value=\"-\" /><input type=\"hidden\" name=\"texts\" value=\"-\" /></div>";
    }

    if ($instance['showlinerental'] == "1") {
        $cpsearchlrental = '';
        if (isset($_GET['line_rental'])) {
            $cpsearchlrental = $_GET['line_rental'];
        }
        $line_rental_search = "<div class=\"module\">
                                    <div class=\"CP_mobiles_search_widget_title\">Line Rental</div>
                                        <div class=\"inputbox\">
                                        <select name=\"line_rental\">
                    <option value=\"-\">Any Cost per month</option>
                    <option value=\"less25\"" . ($cpsearchlrental == 'less25' ? 'selected="selected"' : '') . ">&pound;25 or less</option>
                    <option value=\"less30\"" . ($cpsearchlrental == 'less30' ? 'selected="selected"' : '') . ">&pound;30 or less</option>
                    <option value=\"less35\"" . ($cpsearchlrental == 'less35' ? 'selected="selected"' : '') . ">&pound;35 or less</option>
                    <option value=\"less40\"" . ($cpsearchlrental == 'less40' ? 'selected="selected"' : '') . ">&pound;45 or less</option>
                    <option value=\"more45\"" . ($cpsearchlrental == 'more45' ? 'selected="selected"' : '') . ">&pound;45 or more</option>
                    </select>
                                        </div>
                                </div>";
    } else {
        $line_rental_search = "<input type=\"hidden\" name=\"line_rental\" value=\"-\" />";
    }

    if ($instance['shownetworks'] == "1") {
        $cpsearchnetwork = '';
        if (isset($_GET['network'])) {
            $cpsearchnetwork = $_GET['network'];
        }

        $network_search = "<div class=\"module\">
                <div class=\"CP_mobiles_search_widget_title\">Network</div>
                                    <div class=\"inputbox\">
                                        <select name=\"network\" id=\"network\">
                                        <option value=\"-\">Any Network</option>
                                        <option value=\"3\"" . ($cpsearchnetwork == '3' ? 'selected="selected"' : '') . ">3</option>
                                        <option value=\"EE\"" . ($cpsearchnetwork == 'EE' ? 'selected="selected"' : '') . ">EE</option>
                                        <option value=\"O2\"" . ($cpsearchnetwork == 'O2' ? 'selected="selected"' : '') . ">O2</option>
                                        <option value=\"Orange\"" . ($cpsearchnetwork == 'Orange' ? 'selected="selected"' : '') . ">Orange</option>
                                        <option value=\"T-Mobile\"" . ($cpsearchnetwork == 'T-Mobile' ? 'selected="selected"' : '') . ">T-Mobile</option>
                                        <option value=\"Virgin\"" . ($cpsearchnetwork == 'Virgin' ? 'selected="selected"' : '') . ">Virgin</option>
                                        <option value=\"Vodafone\"" . ($cpsearchnetwork == 'Vodafone' ? 'selected="selected"' : '') . ">Vodafone</option>
                                        </select>
                                    </div>
                           </div>";
    } else {
        $network_search = "<input type=\"hidden\" name=\"network\" value=\"-\" />";
    }

    if ($instance['showdata'] == "1") {
        $cpsearchdata = '';
        if (isset($_GET['data'])) {
            $cpsearchdata = $_GET['data'];
        }
        $data_search = "<div class=\"module\">
                                <div class=\"CP_mobiles_search_widget_title\">Data</div>
                                    <div class=\"inputbox\">
                                        <select name=\"data\" id=\"widget-data\">
                                        <option value=\"-\">Any Internet</option>
                                        <option value=\"less500\"" . ($cpsearchdata == 'less500' ? 'selected="selected"' : '') . ">Up to 500MB</option>
                                        <option value=\"more500\"" . ($cpsearchdata == 'more500' ? 'selected="selected"' : '') . ">500 MB or more</option>
                                        <option value=\"more999\"" . ($cpsearchdata == 'more999' ? 'selected="selected"' : '') . ">1GB or more</option>
                                        <option value=\"more1999\"" . ($cpsearchdata == 'more1999' ? 'selected="selected"' : '') . ">2GB or more</option>
                                        <option value=\"more49999\"" . ($cpsearchdata == 'more49999' ? 'selected="selected"' : '') . ">Unlimited</option>
                                        </select>
                                    </div>
                        </div>";
    } else {
        $data_search = "<input type=\"hidden\" name=\"data\" value=\"-\" />";
    }


    if ($instance['showcashback'] == "1") {
        $cpsearchcashback = '';
        if (isset($_GET['cashback'])) {
            $cpsearchcashback = $_GET['cashback'];
        }
        $cashback_search = "<div class=\"module\">
                                <div class=\"CP_mobiles_search_widget_title\">Cashback</div>
                                    <div class=\"inputbox\">
                                        <select name=\"cashback\" id=\"widget-cashback\">
                                        <option value=\"-\">Any Cashback</option>
                                        <option value=\"0\"" . ($cpsearchcashback == '0' ? 'selected="selected"' : '') . ">Excl Cashback Deals</option>
                                        </select>
                                    </div>
                        </div>";
    } else {
        $cashback_search = "<input type=\"hidden\" name=\"cashback\" value=\"-\" />";
    }


    $url = get_option("widget_ComparePressurls");

    $cpsearchtype = '';
    $cpsort = '';
    if (isset($_GET['contract_type'])) {
        $cpsearchtype = $_GET['contract_type'];
    }
    if (isset($_GET['sort'])) {
        $cpsort = $_GET['sort'];
    }

    if (isset($_GET['manufacturer'])){$manu = $_GET["manufacturer"];}else{$manu = '';}

    echo '<form action="' . get_option('siteurl') . $CP_Deal_Search_URL . '" id="form">
        <div id="CP_mobiles_search_widget">
            <div class="module">
                            <div class="CP_mobiles_search_widget_title">Deal Type</div>
                              <div class="inputbox">
                                <select name="contract_type" onchange="hidesearch(this.value);">
                                <option value="contracts">All Contracts</option>
                                <option value="payg"' . ($cpsearchtype == 'payg' ? 'selected="selected"' : '') . '>PAYG / Sim Free</option>
                                </select>
                              </div>
                        </div>
            <div class="module">
                            <div class="CP_mobiles_search_widget_title">Manufacturer</div>
                                <div class="inputbox">
                                    <select name="manufacturer" id="manufacturer" onchange="getmodels(this.value, \'' . get_option('siteurl') . '\', this.form.contract_type.value);">
                                    <option value="-">All Manufacturers</option>
                                    <option value="3"' . ($manu == '3' ? 'selected="selected"' : '') . '>3</option>
                                    <option value="acer">Acer</option>";
                                    <option value="alcatel">Alcatel</option>
                                    <option value="apple">Apple</option>
                                    <option value="blackberry">Blackberry</option>
                                    <option value="google">Google</option>
                                    <option value="hp">HP</option>
                                    <option value="htc">HTC</option>
                                    <option value="huawei">Huawei</option>
                                    <option value="inq">INQ</option>
                                    <option value="jcb">JCB</option>
                                    <option value="land-rover">Land Rover</option>
                                    <option value="lg">LG</option>
                                    <option value="motorola">Motorola</option>
                                    <option value="nokia">Nokia</option>
                                    <option value="o2">O2</option>
                                    <option value="orange">Orange</option>
                                    <option value="palm">Palm</option>
                                    <option value="samsung">Samsung</option>
                                    <option value="sonim">Sonim</option>
                                    <option value="sony">Sony</option>
                                    <option value="sony-ericsson">Sony Ericsson</option>
                                    <option value="swap">Swap</option>
                                    <option value="tmobile">T-Mobile</option>
                                    <option value="vodafone">Vodafone</option>
                                    <option value="zte">ZTE</option>
                                    </select>
                </div>
                        </div>
            <div class="module">
                <div class="CP_mobiles_search_widget_title">Model</div>
                  <div class="inputbox">
                    <div id="CP_mobiles_select_model">
                                        <select name="phone">
                    <option value="-">Any Phone</option>
                    </select>
                    </div>
                  </div>
            </div>
        <div id="additionalsearch">' . $minute_search . $network_search . $line_rental_search . $data_search . $cashback_search . '</div>
     </div>';
    /**
     * Code that deals with ugly (not pretty) permalinks for deal searches
     *
     */
    if ($url['m4e-htaccessurl'] != "") {
        echo "<div class=\"submit\">
                  <input type=\"submit\" name=\"CP_mobiles_search_widget\" value=\"Search\" class=\"CP_mobiles_submitbutton\" onclick='location.href= \"" . get_option('siteurl') . "/" . $CP_fake_Category . "/search/\" + this.form.phone.options[this.form.phone.selectedIndex].value + \"/\" + this.form.manufacturer.value + \"/\" + this.form.network.value + \"/\" + this.form.contract_type.value + \"/\" + this.form.minutes.value + \"/\" + this.form.texts.value + \"/\" + this.form.data.value + \"/\" + this.form.line_rental.value + \"/\" + this.form.cashback.value + \"/\";return false;' />
                </div>
        </div>

        </form>";
    } else {
        echo "<div class=\"submit\">
                   <input type=\"submit\" name=\"CP_mobiles_search_widget\" value=\"submit\" class=\"CP_mobiles_submitbutton\" />
        </div>
        </div>

        </form>";
    }
}

function topmobiles($instance) {
    $url = get_option("widget_ComparePressurls");
    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix'];
        $using_htaccess = 1;
    } else {
        if ($url['m4e_simpleurl'] != "")
            $CP_fake_Category = $url['m4e_simpleurl'] . "/?phone=";
        else
            $CP_fake_Category = 'compare-mobile-deals' . "/?phone=";
    }

    global $validate;
    // connect and get the top phones
    $send = array(array("token" => $validate["m4e_token"], "top" => "y", "top_number" => ($instance["num_items"] > 1 ? $instance["num_items"] : "5"),"feature" => ($instance["feature"] <> "" ? $instance["feature"] : "")));

    include_once(CP_BASE_DIR . '/lib/nusoap.php');

    $client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
    $output = '';
    try {
        $results = $client->call("getTopHandsets", $send);
        foreach ($results["results"] as $key => $val) {
            $output .= "<div class='CP_mobiles_top_phones_widget_hs'><p>
        <a href=\"".get_option('siteurl')."/". $CP_fake_Category . "/" . str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">
        <img src=\"" . $val["image_small"] . "\" alt=\"" . $val["handset_name"] . "\" /></a>
        <a href=\"".get_option('siteurl')."/". $CP_fake_Category . "/" . str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">
        <br />" . $val["handset_name"] . "</a>
        </p>
        </div>
        ";
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }

    echo "<div id=\"CP_mobiles_top_phones_widget\">
        " . $output.
            "<div id=\"CP_mobiles_top_phones_widget_link_text\">"
          . $instance["linktext"]
          ."</div></div>";
}

function latestmobiles($instance) {
    $options = get_option("widget_ComparePresslatest");
    $url = get_option("widget_ComparePressurls");
    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix'];
        $using_htaccess = 1;
    } else {
        if ($url['m4e_simpleurl'] != "")
            $CP_fake_Category = $url['m4e_simpleurl'] . "/?phone=";
        else
            $CP_fake_Category = 'compare-mobile-deals' . "/?phone=";
    }

    global $validate;

    // connect and get the latest phones
    $send = array(array("token" => $validate['m4e_token'], "latest" => "y", "latest_number" => ($instance['num_items'] > 1 ? $instance['num_items'] : "5"),"feature" => ($instance["feature"] <> "" ? $instance["feature"] : "")));

    include_once(CP_BASE_DIR . '/lib/nusoap.php');

    $client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
    $output = '';
    try {
        $results = $client->call("getTopHandsets", $send);
        foreach ($results["results"] as $key => $val) {
            $output .= "<div class=\"CP_mobiles_latest_phones_widget_hs\">
        <p>
        <a href=\"".get_option('siteurl')."/". $CP_fake_Category . "/" . str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">
        <img src=\"" . $val["image_small"] . "\" alt=\"" . $val["handset_name"] . "\" /></a>
        <a href=\"".get_option('siteurl')."/". $CP_fake_Category . "/" . str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">
        <br />" . $val["handset_name"] . "</a>
        </p>
        </div>
        ";
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }

    echo "<div id=\"CP_mobiles_latest_phones_widget\">
        " . $output . "
    </div>";
}

function FreeGiftsmobiles($instance) {
    $options = get_option("widget_ComparePressFreeGifts");
    $url = get_option("widget_ComparePressurls");
    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix'];
        $using_htaccess = 1;
    } else {
        if ($url['m4e_simpleurl'] != "")
            $CP_fake_Category = $url['m4e_simpleurl'] . "/?phone=";
        else
            $CP_fake_Category = 'compare-mobile-deals' . "/?phone=";
    }

    global $validate;

    // connect and get the free gifts
    $send = array(array("token" => $validate['m4e_token'], "gifts" => "y", "gifts_number" => ($instance['num_items'] > 1 ? $instance['num_items'] : "5"),"feature" => ($instance["feature"] <> "" ? $instance["feature"] : "")));

    include_once(CP_BASE_DIR . '/lib/nusoap.php');

    $client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
    $output = '';
    try {
        $results = $client->call("getTopHandsets", $send);
        foreach ($results["results"] as $key => $val) {
            $output .= "<div class=\"CP_mobiles_latest_phones_widget_hs\">
        <p>
        <a href=\"".get_option('siteurl')."/". $CP_fake_Category . "/free-" . str_replace(" ", "-", $val["gift_name"]) . "/\" title=\"" . $val["gift_name"] . " deals\">
        <img src=\"" . $val["image_small"] . "\" alt=\"" . $val["gift_name"] . "\" /></a>
        <a href=\"".get_option('siteurl')."/". $CP_fake_Category . "/free-" . str_replace(" ", "-", $val["gift_name"]) . "/\" title=\"" . $val["gift_name"] . " deals\">
        <br />" . $val["gift_name"] . "</a>
        </p>
        </div>
        ";
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }

    echo "<div id=\"CP_mobiles_latest_phones_widget\">
        " . $output . "
    </div>";
}

// Widget controls for search items widget (non-modularised as it is unique)
function ComparePress_MobileSearch_control(&$th, $instance) {
    $defaults = array('title' => '', 'showmins' => '1', 'showlinerental' => '1', 'shownetworks' => '1', 'showdata' => '1', 'showcashback' => '1','showlabels' => '1', 'bg_color' => '', 'labeltextcolor' => '', 'border_width' => '0', 'border_color' => '');
    $instance = wp_parse_args((array) $instance, $defaults);
    $title = strip_tags($instance['title']);
    $show_mins = strip_tags($instance['showmins']);
    $show_linerental = strip_tags($instance['showlinerental']);
    $show_networks = strip_tags($instance['shownetworks']);
    $show_data = strip_tags($instance['showdata']);
    $show_cashback = strip_tags($instance['showcashback']);
    $show_labels = strip_tags($instance['showlabels']);
    $bg_color = strip_tags($instance['bg_color']);
    $label_text_color = strip_tags($instance['labeltextcolor']);
    $border_width = strip_tags($instance['border_width']);
    $border_color = strip_tags($instance['border_color']);
    ?>
    <p><?php _e('Title') ?>: <input class="widefat" name="<?php echo $th->get_field_name('title'); ?>"  type="text" value="<?php echo esc_attr($title); ?>" /></p>
    <p>
        <input class="checkbox" name="<?php echo $th->get_field_name('showmins'); ?>" type="checkbox" <?php checked($show_mins, 1); ?> value="1" />
        <label for="<?php echo $th->get_field_name('showmins'); ?>"> <?php _e('Show Mins/Texts') ?></label><br />
        <input class="checkbox" name="<?php echo $th->get_field_name('showlinerental'); ?>" type="checkbox" <?php checked($show_linerental, 1); ?> value="1" />
        <label for="<?php echo $th->get_field_name('showlinerental'); ?>"> <?php _e('Show Line Rental') ?></label><br />
        <input class="checkbox" name="<?php echo $th->get_field_name('shownetworks'); ?>" type="checkbox" <?php checked($show_networks, 1); ?> value="1" />
        <label for="<?php echo $th->get_field_name('shownetworks'); ?>"> <?php _e('Show Networks') ?></label><br />
        <input class="checkbox" name="<?php echo $th->get_field_name('showdata'); ?>" type="checkbox" <?php checked($show_data, 1); ?> value="1" />
        <label for="<?php echo $th->get_field_name('showdata'); ?>"> <?php _e('Show Data') ?></label><br />
        <input class="checkbox" name="<?php echo $th->get_field_name('showcashback'); ?>" type="checkbox" <?php checked($show_cashback, 1); ?> value="1" />
        <label for="<?php echo $th->get_field_name('showcashback'); ?>"> <?php _e('Show Cashback') ?></label><br />
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

function show_page($content) {
    /**
     * Show the plugin content on the pages which have added any shortcodes
     * e.g. here http://mobile-phonedeals.com/phones/windows-phone-7/
     */
    global $wp_query;
    //$content = get_the_content();
    $handsetid = '';
    // grab the custom fields
    $feature = get_post_meta($wp_query->post->ID, "feature", true);
    $make = get_post_meta($wp_query->post->ID, "make", true);
    $network_deals = get_post_meta($wp_query->post->ID, "network_deals", true);
    $handsetID = get_post_meta($wp_query->post->ID, "handsetID", true);
    $launchdate = get_post_meta($wp_query->post->ID, "launchdate", true);
    $DataToAppend = '';

    // if we have custom fields, format and include
    if ($feature) {
        $DataToAppend = "<br />[showphones feature='" . $feature . "']";
    }

    if ($make) {
        $make = "<br />[showphones make='" . $make . "']";
    }

    if ($network_deals) {
        $network_deals = "<br />[showphones network_deals='" . $network_deals . "']";
    }

    if ($launchdate) {
        $DataToAppend = "<br />[showphones launchdate='" . $launchdate . "']";
    }

    if ($handsetid) {
        $DataToAppend = "<br />[showphones handsetid='" . $handsetID . "']";
    }
    // Make sure we get the p tags etc included
    // and check if php exec is present and if so
    // alter the content accordingly

    if (class_exists('ExecPhp_Manager')) {
        $openTagPos = stripos($content, '<?php');
        $closeTagPos = stripos($content, '?>');
        if ($openTagPos && $closeTagPos) {
            $contentLeft = substr($content, 0, $openTagPos - 1);
            $contentPHP = substr($content, $openTagPos, ($closeTagPos - $openTagPos) + 3);
            $contentRight = substr($content, $closeTagPos + 2, strlen($content) - ($closeTagPos));
            $content = wpautop($contentLeft) . $contentPHP . wpautop($contentRight);
        } else {
            $content = wpautop($content);
        }
    } else {
        $content = wpautop($content);
    }

    return $content . $DataToAppend;
}

# This is a virtual ComparePress page e.g. http://mobile-phonedeals.com/deals/Samsung-Galaxy-S-III-blue/
if ((isset($_GET['phone']) != "") || ((isset($_GET['manufacturer']) != "") != "") || ((isset($_GET['gift']) != "") != "")) {

# This gets the user's simple url settings to create the fake page slug
    $options = get_option('widget_ComparePressurls');
    $m4eDealUrl = $options['m4e_simpleurl'];
    /**
     * Show the plugin content on the phone or search specific pages using ComparePressFakePage class
     * e.g. http://mobile-phonedeals.com/deals/?phone=HTC-Desire-HD
     * @todo : Work on turning these ugly URLS into some form of 'pretty' URLs like they used to be
     * e.g. http://mobile-phonedeals.com/deals/HTC-Desire-HD
     */
    include(CP_BASE_DIR . '/modules/UK/mobiles/search/mobiles_search_results.php');

    new ComparePressFakePage(array(
                'slug' => '' . $m4eDealUrl . '',
                'title' => $ComparePressPageTitle,
                'content' => $comparePressMobile
            ));
} else
/**
 * This ia an actual WordPress page the user has created and has added the ComparePress content to it via the shortcodes
 * e.g. have feature windows os which gives them a page like: http://mobile-phonedeals.com/phones/windows-phone-7/ */ {
    add_filter('the_content', 'show_page', 12);
}

// this is for various shortcodes we provide and make use of
add_shortcode('showphones', 'sc_showphones');

// this is for adding AJAX search functionality
add_shortcode('showphonedealsearch', 'sc_ajaxsearch');
?>