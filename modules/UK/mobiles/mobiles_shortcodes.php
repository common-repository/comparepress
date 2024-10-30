<?php
/*
  ComparePress Module: Mobiles
  Version: 2.0.1
  Description: Shortcodes for actual / real WordPress Pages created by the user
  Example shortcode functionality on content pages using format:

 * [showphones feature='google android']
 * [showphones launchdate='latest']
 * [showphones make='Apple']
 * [showphones handsetid ='Sony Ericsson Satio Black']
 * [showphones handsetid ='Sony Ericsson Satio Black' network='orange']
 * [showphones handsetid ='Sony Ericsson Satio Black' network='orange' number ='5']

 * New Shortocodes added in version 2.0:
 * [showphones network_deals='payg'] - shows all handsets available as PAYG
 * [showphones network_deals='paygtmobile'] - shows all handsets available as PAYG with T-Mobile* 
 * [showphones network_deals='simfree'] - shows all handsets available as Sim Free
 * [showphones network_deals='tmobile'] - shows all handsets available on a network e.g. T-Mobile
 * [showphonedealsearch] - displays the new full AJAX deal search system

  @TODO: [showphones top='latest' number='10'] for those who can't display widgets within posts / pages
  @TODO: [showphones top='best selling' number='10'] for those who can't display widgets within posts / pages
  @TODO: [showphones custom='yes' phones='htc one x, Samsung Galaxy S III white, BlackBerry Bold 9900'] to show a custom list of handsets that user enters
 */

function sc_showphones($atts, $content = null) {
    $do = '';
    $geo_options = get_option("widget_geo_options");
    
// Country Specific IP Checking removed for now - this would enable site owners to only show plugin content for target Countries e.g. UK
//    if ($geo_options['chk_geoip_shortcodes_UK'] == "1") {
//        if (!check_user_country())
//            return "";
//    }
    
    // Pings ComparePress server to make sure there is some content to display.
    if (!GetServerStatus(CP_SOAP_SERVER, CP_SOAP_STATUS, 80, "Server ok"))
        return "";
    // extract any variables that were passed in with the shortcode.    
    extract(shortcode_atts(array(
    "feature" => '', "make" => '', "network_deals" => '', "launchdate" => '', "handsetid" => '', "network" => '', "layout" => '', "number" => '',
        ), $atts));

    if ($feature) {
        $do = "feature";
    }

    if ($launchdate) {
        $do = "launchdate";
    }

    if ($make) {
        $do = "make";
    }

    if ($handsetid) {
        $do = "handsetid";
    }

    if ($network_deals) {
        $do = "network_deals";
    }

    // Gets the user's URL preferrence
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

    $validate = get_option("widget_comparePressAdmin");
    $currentImage_man = '';
    $topoutput = '';
    switch ($do) {
        case "feature":
            if ($feature != "") {
                $send = array(array("token" => $validate['m4e_token'], "spec" => $feature, "network" => $network, "number" => $number));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
                try {
                    $results = $client->call("getHandsetsBySpec", $send);
                    // make sure we didn't get an error
                    if (!isset($results["error"])) {
                        foreach ($results["results"] as $key => $val) {
                            if ($val['image_Man'] != $currentImage_man)
                                $manHTML = "<p class='CP-clearfix'></p><img src=\"" . $val['image_Man'] . "\" alt=\"" . $val["man_Name"] . " logo\"><br /><hr />";
                            else
                                $manHTML = "";
                            $topoutput .= $manHTML;
                            $topoutput .= "<div class=CP_mobiles_handset_box>";
                            $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                            $topoutput .= "<img src=\"" . $val['image_small'] . "\" alt=\"" . $val["handset_name"] . "\"></a><br />";
                            $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                            $topoutput .= $val["handset_name"] . "</a></div>";
                            $currentImage_man = $val['image_Man'];
                        }
                    }
                    if ($topoutput != "") {
                        $show_html = "<div class=\"CP_mobiles_handsets_only\">
                                    " . $topoutput . "
                                    </div>
                                    <div class=\"CP-clearfix\"></div>
                                    ";
                    } else {
                        $show_html = "Error: No Results to display";
                    }
                } catch (SoapFault $exception) {
                    echo $exception;
                }
                // return our results
                return $show_html;
            }
            break;

        case "make":
            if ($make != "") {
                $send = array(array("token" => $validate['m4e_token'], "make" => $make, "network" => $network, "number" => $number));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
                try {
                    $results = $client->call("getHandsetsByMake", $send);
                    foreach ($results["results"] as $key => $val) {
                        if ($val['image_Man'] != $currentImage_man)
                            $manHTML = "<p class='CP-clearfix'></p><img src=\"" . $val['image_Man'] . "\" alt=\"" . $val["man_Name"] . " logo\"><br /><hr />";
                        else
                            $manHTML = "";

                        $topoutput .= $manHTML;
                        $topoutput .= "<div class=CP_mobiles_handset_box>";
                        $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                        $topoutput .= "<img src=\"" . $val['image_small'] . "\" alt=\"" . $val["handset_name"] . "\"></a><br />";
                        $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                        $topoutput .= $val["handset_name"] . "</a></div>";
                        $currentImage_man = $val['image_Man'];
                    }

                    if ($topoutput != "") {
                        $show_html = "<div class=\"CP_mobiles_handsets_only\">						
						" . $topoutput . "
						</div>
						<div class=\"CP-clearfix\"></div>
						";
                    }
                } catch (SoapFault $exception) {
                    echo $exception;
                }
                // return our results
                return $show_html;
            }
            break;

        case "network_deals":
            if ($network_deals != "") {
                $send = array(array("token" => $validate['m4e_token'], "network_deals" => $network_deals, "layout" => $layout, "number" => $number));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
                try {
                    $results = $client->call("getHandsetsByNetwork", $send);

                    // For PAYG / SIM Free alternative layout
                    if ($layout == "prices") {
                        foreach ($results["results"] as $key => $val) {
                            $topoutput .= "<div class=\"payg_container\">";
                            $topoutput .= "<p><a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                            $topoutput .= $val["handset_name"] . "</a></p>";

                            $topoutput .= "<div class=\"payg_phone_container\">";
                            $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                            $topoutput .= "<img src=\"" . $val['image_small'] . "\" alt=\"" . $val["handset_name"] . "\"></a><br />";
                            $topoutput .= "</div>";

                            $topoutput .= "<img src=\"".CP_BASE_URL."/modules/UK/mobiles/images/" . $val["network"] . ".gif\" alt=\"" . $val["network"] . "\" /><br />";

                            $topoutput .= "<a href=\"" . $val["deeplink"] . "&site=" . str_replace("www.", "", $_SERVER['HTTP_HOST']) . "\" target=\"_blank\" rel=\"nofollow\"><img src=\"".CP_BASE_URL."/modules/UK/mobiles/images/See-Mobile-Deal.jpg\"  alt=\"Click Here\" /></a>";
                            $topoutput .= "<div class=\"CP-prices\">" . $val["price"] . "</div>";
                            $topoutput .= "</div>";
                        }
                    } else {
                        foreach ($results["results"] as $key => $val) {
                            if ($val['image_Man'] != $currentImage_man)
                                $manHTML = "<p class='CP-clearfix'></p><img src=\"" . $val['image_Man'] . "\" alt=\"" . $val["man_Name"] . " logo\"><br /><hr />";
                            else
                                $manHTML = "";
                            $topoutput .= $manHTML;
                            $topoutput .= "<div class=CP_mobiles_handset_box>";
                            $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                            $topoutput .= "<img src=\"" . $val['image_small'] . "\" alt=\"" . $val["handset_name"] . "\"></a><br />";
                            $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                            $topoutput .= $val["handset_name"] . "</a></div>";
                            $currentImage_man = $val['image_Man'];
                        }
                    }

                    if ($topoutput != "") {
                        $show_html = "<div class=\"CP_mobiles_handsets_only\">
						<div class=\"CP_mobiles_handsets_title\"></div>
						" . $topoutput . "
						</div>
						<div class=\"CP-clearfix\"></div>
						";
                    }
                } catch (SoapFault $exception) {
                    echo $exception;
                }
                // return our results
                return $show_html;
            }
            break;

        case "launchdate":
            if ($launchdate != "") {
                $send = array(array("token" => $validate['m4e_token'], "launchdate" => $launchdate, "number" => $number));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
                try {
                    $results = $client->call("getHandsetsByLaunchDate", $send);

                    foreach ($results["results"] as $key => $val) {
                        if ($val['image_Man'] != $currentImage_man)
                            $manHTML = "<p class='CP-clearfix'></p><img src=\"" . $val['image_Man'] . "\" alt=\"" . $val["man_Name"] . " logo\"><br /><hr />";
                        else
                            $manHTML = "";

                        $topoutput .= $manHTML;
                        $topoutput .= "<div class=CP_mobiles_handset_box>";
                        $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                        $topoutput .= "<img src=\"" . $val['image_small'] . "\" alt=\"" . $val["handset_name"] . "\"></a><br />";
                        $topoutput .= "<a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/\" title=\"" . $val["handset_name"] . " deals\">";
                        $topoutput .= $val["handset_name"] . "</a></div>";
                        $currentImage_man = $val['image_Man'];
                    }

                    if ($topoutput != "") {
                        $show_html = "<div class=\"CP_mobiles_handsets_only\">							
							" . $topoutput . "
							</div>
						<div class=\"CP-clearfix\"></div>
							";
                    }
                } catch (SoapFault $exception) {
                    echo $exception;
                }
                //return our results
                return $show_html;
            }

            break;

        case "handsetid":
            if ($handsetid != "") {                                
            $list = '';
            $headings = '';
            $output = '';    
            $show_html = '';    

                $send = array(array("token" => $validate['m4e_token'], "manufacturer" => addslashes(isset($_GET['manufacturer'])), "contract" => addslashes(isset($_GET['contract_type'])), "handset" => addslashes($handsetid), "minutes" => addslashes(isset($_GET['minutes'])), "texts" => addslashes(isset($_GET['texts'])), "linerental" => addslashes(isset($_GET['line_rental'])), "network" => $network, "full" => "y", "number" => $number));

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
                        } else {
                            $customresult = "<ul id=\"handset_features\">" . html_entity_decode($results["details"]["features"]) . "</ul>";
                            $customTitleresult = $results["details"]["handset_name"];
                        }

                        //Sets the title preferences
                        $CP_fake_Category_title = '';
                        $ComparePressPageTitle = $customTitleresult . " " . $CP_fake_Category_title;

                        $comparePressMobile .= "<div id=\"handset_detail\">
		<div class=\"CP_mobiles_results_Imageright\"><img src=\"" . $results["details"]["handset_image"] . "\" alt=\"" . $results["details"]["handset_name"] . "\" /></div>
                <p>" . $customresult . "</p>
				</div>";
                    } else {
                        $ComparePressPageTitle = 'Your search results:';
                        $comparePressMobile .= "<div id=\"handset_detail\">
        <table width=\"100%\">
        <tr>
        <td>
        <h2>" . ucwords($manu . " Mobile Phones " . ($gift == "" ? "" : "with a free " . str_replace("-", " ", $gift)) . $network . " ") . "</h2>
        </td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        </tr>
        </table>
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
                                            $list .= "<td class=\"CP_mobiles_results_network\"><p><a href='" . "/" . $CP_fake_Category . "/" . str_replace(" ", "-", $val["handset_name"]) . "/' title=\"" . $val["handset_name"] . " Deals\" /> <img src=\"" . $val['image_small'] . "\"  alt=\"" . str_replace(" ", "-", $val["handset_name"]) . "\" /><br/>" . $val["handset_name"] . "</a></p></td>";
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
                                        $list .= "<td class=\"CP_mobiles_results_information\"><p><strong>" . $val["price"] . $customTitleresult . "</strong><br/>" . ($val["cashback"] != "" ? "<strong>" . $val["cashback"] . "</strong><br/>" : "") . ($val["gift"] != "" ? "<strong>FREE " . $val["gift"] . "</strong><br/>" : "") . str_replace(".", "<br/>", html_entity_decode($val["description"])) . "Total cost " . $val["totalcost"] . " " . $val["tarifflength"] . "</p></td>";
                                        break;

                                    case "costpermonth":
                                        $list .= "<td class=\"CP_mobiles_results_permonth\"><p><strong>" . $val["effectiverental"] . "</strong><br /> a month<br />" . ($val["cashback"] != "" ? "<br />(" . $val["linerental"] . " before cashback)" : "") . "</p></td>";
                                        break;

                                    case "buy":
                                        $list .= "<td class=\"CP_mobiles_results_buy\"><p><a href=\"" . $val["deeplink"] . "&site=" . str_replace("www.", "", $_SERVER['HTTP_HOST']) . "\" target=\"_blank\" rel=\"nofollow\" onclick=\"_gaq.push(['_trackEvent', 'Outbound Links', 'Click', '" . $val["handset_name"] . "-" . $val["retailer"] ."-viaShortCode" ."']);\"><img src=\"http://www.mobiles4everyone.com/retailers/" . $val["retailer"] . ".gif\"  alt=\"" . $val["retailer"] . "\" /><br/><img src=\"".CP_BASE_URL."/modules/UK/mobiles/images/See-Mobile-Deal.jpg\"  alt=\"Buy Mobile Deal\" /></a></p></td>";
                                }
                            }
                            $output .= "<tr>" . $list . "</tr>";
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

                        $show_html .= "<div id=\"CP_mobiles_results\">
                        <div id=\"results\">
			<table width=\"100%\" border=\"0\" cellspacing=\"0\">
			<tr class=\"CP_mobiles_results_TH\">
				" . $headings . "
			</tr>
			" . $output . "
			</table>
                        </div>
                        </div>"
                        ;
                    } else {
                        return "<h4>" . $results["error"] . "</h4>";
                    }
                } catch (SoapFault $exception) {
                    echo $exception;
                }
                // return our results
                return $show_html;
            }
            break;
    }
}

?>