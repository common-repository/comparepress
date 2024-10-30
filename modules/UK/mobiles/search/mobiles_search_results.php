<?php
// this is sent back to the ComparePressFakePage class and place within a users
// normal blog template as a fake page
// Stops <p> tags being added to content and messing up styling
remove_filter('the_content', 'wpautop');
// @TODO: Create one central query for the custom handset title / descriptions

// This adds the AJAX JS to the head on the deals pages only.
function insert_CP_AJAX_into_head() {
    global $wp_scripts;
    if (( version_compare("1.4.4", $wp_scripts->registered['jquery']->ver) == 1)) {
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', CP_BASE_URL . '/modules/UK/mobiles/search/js/jquery-1.4.4.min.js', false, "1.4.4");
    }
// Added so that AJAX search JS works on single.php only
    wp_enqueue_script('myJavascript', CP_BASE_URL . '/modules/UK/mobiles/search/js/cpnav2.js', array('jquery'));
    wp_enqueue_script('jQueryGUI', CP_BASE_URL . '/modules/UK/mobiles/search/js/jquery-ui-1.10.0.custom.min.js', array('jquery'));
    wp_enqueue_style('jQueryUI-hotsneakstheme', CP_BASE_URL . '/modules/UK/mobiles/search/css/hot-sneaks/jquery-ui-1.10.0.custom.css');
    wp_enqueue_style('newsearchnav', CP_BASE_URL . '/modules/UK/mobiles/search/css/newsearchnav.css');
}
add_action('wp_head', 'insert_CP_AJAX_into_head', 1);
$validate = get_option("widget_comparePressAdmin");
$table_class = ""; // Sets up class for handset-free search
$using_htaccess = 0; // Sets up var to hold URL type
//$phone_name = $_GET["phone"];
if (isset($_GET['phone'])){$phone_name = $_GET["phone"];}else{$phone_name = '';}

if (isset($_GET['manufacturer'])){$manu = addslashes($_GET["manufacturer"]);}else{$manu = '';}
if (isset($_GET['contract_type'])){$contract_type = addslashes($_GET["contract_type"]);}else{$contract_type = '';}
if (isset($_GET['m4e_tarifflength'])){$tarifflength = addslashes($_GET["m4e_tarifflength"]);}else{$tarifflength = '';}
if (isset($_GET['network'])){$network = addslashes($_GET["network"]);}else{$network = '';}
if (isset($_GET['minutes'])){$minutes = addslashes($_GET["minutes"]);}else{$minutes = '';}
if (isset($_GET['texts'])){$texts = addslashes($_GET["texts"]);}else{$texts = '';}
if (isset($_GET['line_rental'])){$linerental = addslashes($_GET["line_rental"]);}else{$linerental = '';}
if (isset($_GET['data'])){$data = addslashes($_GET["data"]);}else{$data = '';}
if (isset($_GET['cash'])){$cash = addslashes($_GET["cash"]);}else{$cash = '';}
if (isset($_GET['sort'])){$sort = addslashes($_GET["sort"]);}else{$sort = '';}
if (isset($_GET['m4e_cashback'])){$cashback = addslashes($_GET["m4e_cashback"]);}else{$cashback = '';}
if (isset($_GET['m4e_effective_rental'])){$effectiverental = addslashes($_GET["m4e_effective_rental"]);}else{$effectiverental = '';}
if (isset($_GET['m4e_total_cost'])){$totalcost = $_GET["m4e_total_cost"];}else{$totalcost = '';}
if (isset($_GET['gift'])){$gift = $_GET["gift"];}else{$gift = '';}

$list = '';
$output = '';
$comparePressMobileAdvancedSearch2 = '';
$headings = '';

// Gets the user's URL preference
$url = get_option("widget_ComparePressurls");
// logic to see what sort of URLS we are using...
if ($url['ComparePress-htaccessPrefix'] != "") {
    $CP_fake_Category = $url['ComparePress-htaccessPrefix'];
    $using_htaccess = 1;
} else {
    if ($url['m4e_simpleurl'] != "")
        $CP_fake_Category = $url['m4e_simpleurl']."/?phone=";
    else
        $CP_fake_Category = 'compare-mobile-deals'."/?phone=";
}
//Gets the title preferences
$CP_fake_Category_title = '';
$CP_fake_Category_title = $url['m4e-deals-titles'];

if ($contract_type == "")
    $contract_type = "contracts";

if ($phone_name == "" | $phone_name == "/" | $phone_name == null) {
    $send = array(array("token" => $validate['m4e_token'], "manufacturer" => addslashes($manu), "contract" => $contract_type,
        "tarifflength" => $tarifflength, "network" => $network, "minutes" => $minutes,
        "texts" => $texts, "linerental" => $linerental, "inclusive_data" => $data,
        "cashback" => $cash, "effectiverental" => $effectiverental,
        "gift" => $gift,
        "totalcost" => $totalcost, "number" => "10", "sort" => $sort, "full" => ""));
} else {
    $send = array(array("token" => $validate['m4e_token'], "manufacturer" => addslashes($manu), "contract" => addslashes($contract_type),
        "tarifflength" => $tarifflength, "handset" => addslashes($_GET['phone']), "network" => $network, "minutes" => $minutes,
        "texts" => $texts, "linerental" => $linerental, "inclusive_data" => $data,
        "cashback" => $cash, "effectiverental" => $effectiverental,
        "totalcost" => $totalcost,
        "number" => "10", "sort" => $sort, "full" => "y"));
}

require_once(CP_BASE_DIR . '/lib/nusoap.php');
$client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
try {
    $results = $client->call("getHandsetsByQuery", $send);
    $comparePressMobile = "<div id=\"CP_mobiles_results\">";

    if (!empty($results["details"]["handset_name"]) != "") {

        //Gets the custom description if set
        $custom = "SELECT mhd_description
                   FROM " . CP_DB_BASE . "comparepress_mobiles_hs_description
                   WHERE mhd_handset_id = '" . str_replace(" ", "-", $results["details"]["handset_name"]) . "'";
        $customresult = mysql_query($custom);

        if (@mysql_num_rows($customresult) == 1) {
            $row = mysql_fetch_array($customresult);
            $customresult = stripslashes($row['mhd_description']);

            // Also check for a custom handset name if set
            $customTitle = "SELECT mhd_handset_title
                    FROM " . CP_DB_BASE . "comparepress_mobiles_hs_description
                    WHERE mhd_handset_id = '" . str_replace(" ", "-", $results["details"]["handset_name"]) . "'";
            $customTitleresult = mysql_query($customTitle);

            if (@mysql_num_rows($customTitleresult) == 1) {
                $row = mysql_fetch_array($customTitleresult);
                $customTitleresult = stripslashes($row['mhd_handset_title']);
            }
        }
        else {
            $customresult = "<ul id=\"handset_features\">" . html_entity_decode($results["details"]["features"])."</ul>";
            $customTitleresult = $results["details"]["handset_name"];
        }

        //Sets the title preferences
        $ComparePressPageTitle = $customTitleresult . " ".$CP_fake_Category_title;

        $comparePressMobile .= "<div id=\"handset_detail\">
        <div class=\"CP_mobiles_results_Imageright\"><img src=\"" . $results["details"]["handset_image"] . "\" alt=\"" . $results["details"]["handset_name"] . "\" /></div>
                <p>" . $customresult . "</p>
                </div>";
    } else{
        $ComparePressPageTitle = ucwords($manu . " Mobile Phones " . ($gift == "" ? "" : "with a free " . str_replace("-", " ", $gift)). $network. " " );
        $comparePressMobile .= "<div id=\"handset_detail\">
        <p>Compare all ". ucwords($manu) . " Mobile Phones " . ($gift == "" ? "" : " that come with a Free " . ucwords(str_replace("-", " ", $gift)." gift")). $network. " below and use the
        search filers below if you know what network you want to be on or need a certain amount of texts, minutes or internet per month.
        </p>
        </div>";
    }

    if (!empty($results["results"])) {
        $options = get_option("widget_ComparePress_order_results");
        $column_headings = get_option("widget_ComparePress_order_headings");

        if ($options[1] == "") {
            $options = array("1" => "network", "2" => "minutes", "3" => "texts", "4" => "information", "5" => "costpermonth", "6" => "buy");
            update_option("widget_ComparePress_order_results", $options);
        }

        if ($column_headings["network"] == "") {
            $column_headings = array("network" => "Network", "minutes" => "Minutes", "texts" => "Texts", "information" => "Information", "costpermonth" => "Cost p/m", "buy" => "Buy");
            update_option("widget_ComparePress_order_headings", $column_headings);
        }

        foreach ($results["results"] as $key => $val) {
            $list = "";
            foreach ($options as $optionskey => $optionsval) {

                switch ($optionsval) {
                    case "network":
                        if (!empty($results["details"]["handset_name"]) != "") {
                            $list .= "<td class=\"CP_mobiles_results_network\"><p><img src=\"".CP_BASE_URL."/modules/UK/mobiles/images/" . $val["network"] . ".gif\" alt=\"" . $val["network"] . "\" /><br />" . $val["tariff"] . "</p></td>";
                        } else {
                            $list .= "<td class=\"CP_mobiles_results_network\"><p><a href=\"".get_option('siteurl')."/". $CP_fake_Category ."/" . str_replace(" ", "-", $val["handset_name"]) . "/\"' title=\"" . $val["handset_name"] . " Deals\" /> <img src=\"" . $val['image_small'] . "\"  alt=\"" . str_replace(" ", "-", $val["handset_name"]) . "\" /><br/>" . $val["handset_name"] . "</a></p></td>";
                            $list .= "<td class=\"CP_mobiles_results_network\"><p><img src=\"".CP_BASE_URL."/modules/UK/mobiles/images/" . $val["network"] . ".gif\" alt=\"" . $val["network"] . "\" /><br />" . $val["tariff"] . "</p></td>";
                            // For the free gifts searches
                            $customTitleresult = $val["handset_name"];
                        }
                        break;

                    case "minutes":
                        $list .= "<td class=\"CP_mobiles_results_minutes\"><p><span class=\"ComparePess_bigValue\">" . $val["anytime_minutes"] . "</span><br/><span class=\"ComparePess_bigText\">Minutes</span></p></td>";
                        break;

                    case "texts":
                        $list .= "<td class=\"CP_mobiles_results_texts\"><p><span class=\"ComparePess_bigValue\">" . $val["texts"] . "</span><br/><span class=\"ComparePess_bigText\">Texts</span></p></td>";
                        break;

                    case "information":
                        $list .= "<td class=\"CP_mobiles_results_information\">".($val["gift"] != "" ? "<img src=\"" . $val["gift_image_small"] . "\" class=\"CP_mobiles_results_Imageright\">" : "")."<p><strong>" . $val["price"]. $customTitleresult . "</strong><br/>" . ($val["cashback"] != "" ? "<strong>" . $val["cashback"] . "</strong><br/>" : "") . ($val["gift"] != "" ? "<strong>FREE " . $val["gift"] . "</strong><br/>" : "") . str_replace(".", "<br/>", html_entity_decode($val["description"])) . "Total cost " . $val["totalcost"] . " " . $val["tarifflength"] . "</p></td>";
                        break;

                    case "costpermonth":
                        $list .= "<td class=\"CP_mobiles_results_permonth\"><p><strong>" . $val["effectiverental"] . "</strong><br /> a month<br />" . ($val["cashback"] != "" ? "<br />(" . $val["linerental"] . " before cashback)" : "") . "</p></td>";
                        break;

                    case "buy":
                        $list .= "<td class=\"CP_mobiles_results_buy\"><p><a href=\"" . $val["deeplink"] . "&site=" . str_replace("www.", "", $_SERVER['HTTP_HOST']) . "\" target=\"_blank\" rel=\"nofollow\" onclick=\"_gaq.push(['_trackEvent', 'Outbound Links', 'Click', '" . $val["handset_name"] . "-" . $val["retailer"] . "']);\"><img src=\"http://www.mobiles4everyone.com/retailers/" . $val["retailer"] . ".gif\"  alt=\"" . $val["retailer"] . "\" /><br/><img src=\"".CP_BASE_URL."/modules/UK/mobiles/images/See-Mobile-Deal.jpg\"  alt=\"Buy Mobile Deal\"  width=\"74\" height=\"35\"/></a></p></td>";
                }
            }
            $output .= "<tr class=\"CP_mobile_results_buy\">" . $list . "</tr>";
        }

        foreach ($options as $key => $val) {
            switch ($val) {
                case "network":
                    if (!empty($results["details"]["handset_name"]) != "") {
                        $headings .= "<th>" . $column_headings[$val] . "</th>";
                    } else {
                        $headings .= "<th>Handset</th>";
                        $headings .= "<th>Tariff</th>";
                        $table_class = "no_handset_search";
                    }
                    break;

                case "minutes":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "texts":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "information":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "costpermonth":
                   $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "buy":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
            }
        }
        /**
         * Add the new advanced deal search to handset deals pages
         */
        $comparePressMobileAdvancedSearch2 .="";
        $comparePressMobileAdvancedSearch2 .="<ul id=\"CP_mobiles_search_tabs\">";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>All</a></li>";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>Vodafone</a></li>";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>EE</a></li>";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>O2</a></li>";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>Orange</a></li>";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>T-Mobile</a></li>";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>Virgin</a></li>";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>3</a></li>";
        $comparePressMobileAdvancedSearch2 .="<li><a href='#'>PAYG</a></li>";
        $comparePressMobileAdvancedSearch2 .="</ul>";
        $comparePressMobile .= $comparePressMobileAdvancedSearch2;

        $comparePressMobile .= "<div id=\"cpbaseurl\" style=\"display: none\">" . CP_BASE_URL . "</div>

                                <div id=\"manu\" style=\"display: none\">" . $manu . "</div>

                                <div id=\"newnavcontrols\">

                                <div class=\"newnavpanel\">

                                        <div class=\"nav-slider\" id=\"minutes-slider\"></div>

                                        <div class=\"slider-label\">Minutes</div>

                                        <div class=\"slider-value\" id=\"minutes-value\">Any</div>

                                        <div class=\"slider-pass\" id=\"minutes-pass\"></div>

                                </div>

                                <div class=\"newnavpanel\">

                                        <div class=\"nav-slider\" id=\"texts-slider\"></div>

                                        <div class=\"slider-label\">Texts</div>

                                        <div class=\"slider-value\" id=\"texts-value\">Any</div>

                                        <div class=\"slider-pass\" id=\"texts-pass\"></div>

                                </div>

                                <div class=\"newnavpanel\">

                                        <div class=\"nav-slider\" id=\"cost-slider\"></div>

                                        <div class=\"slider-label\">Cost</div>

                                        <div class=\"slider-value\" id=\"cost-value\">Any</div>

                                        <div class=\"slider-pass\" id=\"cost-pass\"></div>

                                </div>

                                <div class=\"newnavpanel\">

                                        <div class=\"nav-slider\" id=\"data-slider\"></div>

                                        <div class=\"slider-label\">Internet</div>

                                        <div class=\"slider-value\" id=\"data-value\">Any</div>

                                        <div class=\"slider-pass\" id=\"data-pass\"></div>

                                </div>

                                <div class=\"newnavpanel\">

                                        <div class=\"nav-slider\" id=\"sort-slider\"></div>

                                        <div class=\"slider-label\">Sort</div>

                                        <div class=\"slider-value\" id=\"sort-value\">Cost per month</div>

                                        <div class=\"slider-pass\" id=\"sort-pass\"></div>

                                </div>
                                <input type=\"hidden\" id=\"phone_name\" value=\"". $phone_name . "\">
                                <input type=\"hidden\" id=\"gift\" value=\"". $gift. "\"></div>
        <p style=\"clear: both; padding: 0; margin: 0;\">&nbsp;</p>";

        $comparePressMobile .= "<div id=\"results\" style=\"clear: both;\">
            <table width=\"100%\" border=\"0\" cellspacing=\"0\" class=\"" . $table_class . "\">
                        <thead>
            <tr class=\"CP_mobiles_results_TH\">
                " . $headings . "
            </tr>
                        </thead>
            <tbody>
            " . $output . "
                         </tbody>
            </table>
        </div>"
        ;
    } else {
        $comparePressMobile.= "<p style=\"clear: both; padding: 0; margin: 0;\">&nbsp;</p>";
        $comparePressMobile.= "<h4>" . $results["error"] . "</h4>";
    }
} catch (SoapFault $exception) {
    $comparePressMobile .= $exception;
}

$comparePressMobile .= "</div>";