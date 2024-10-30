<?php
include('configpath.php');
if (!function_exists('add_action')) {
// Needed to grab wp-config.php file because AJAX file is run outside of context of Wordpress
require_once(fs_get_wp_config_path());
}
@include ('jquery.webservice.js');
$validate = get_option("widget_comparePressAdmin");
$table_class = ""; // Sets up class for handset-free search
$output = '';
$headings = '';
//$phone_name = $_GET["phone"];
//get phone
if (isset($_GET['phone'])){$phone_name = $_GET["phone"];}else{$phone_name = '';}
//post phone
if (isset($_POST['phone'])){$post_phone_name = $_POST["phone"];}else{$post_phone_name = '';}

if (isset($_POST['$manu'])){$manu = addslashes($_POST["$manu"]);}else{$manu = '';}
if (isset($_POST['contract_type'])){$contract_type = addslashes($_POST["contract_type"]);}else{$contract_type = '';}
if (isset($_GET['m4e_tarifflength'])){$tarifflength = addslashes($_GET["m4e_tarifflength"]);}else{$tarifflength = '';}
if (isset($_POST['network'])){$network = addslashes($_POST["network"]);}else{$network = '';}
if (isset($_POST['tabs_minutes'])){$minutes = addslashes($_POST["tabs_minutes"]);}else{$minutes = '';}
if (isset($_POST['tabs_texts'])){$texts = addslashes($_POST["tabs_texts"]);}else{$texts = '';}
if (isset($_POST['tabs_cost_per_month'])){$linerental = addslashes($_POST["tabs_cost_per_month"]);}else{$linerental = '';}
if (isset($_POST['tabs_data'])){$data = addslashes($_POST["tabs_data"]);}else{$data = '';}
if (isset($_POST['tabs_cashback'])){$cashback = addslashes($_POST["tabs_cashback"]);}else{$cashback = '';}
if (isset($_POST['tabs_sort'])){$sort = addslashes($_POST["tabs_sort"]);}else{$sort = '';}
if (isset($_POST['m4e_effective_rental'])){$effectiverental = addslashes($_POST["m4e_effective_rental"]);}else{$effectiverental = '';}
if (isset($_POST['gift'])){$gift = addslashes($_POST["gift"]);}else{$gift = '';}

if ($post_phone_name=="" | $post_phone_name=="/" | $post_phone_name==null) {
    $send = array(array(
            "token" => $validate['m4e_token'],
            "manufacturer" => $manu,
            "contract" => $contract_type,
            "tarifflength" => $tarifflength,
            "network" => $network,
            "minutes" => $minutes,
            "texts" => $texts,
            "linerental" => $linerental,
            "inclusive_data" => $data,
            "sort" => $sort,        
            "cashback" => $cashback,
            "effectiverental" => $effectiverental,
            "totalcost" => "",
            "number" => "10",
            "gift" => addslashes($_POST['gift']),
            "full" => ""));
} else {
    $send = array(array(    
            "token" => $validate['m4e_token'],
            "manufacturer" => addslashes($_POST['manu']),
            "contract" => $contract_type,
            "tarifflength" => $tarifflength,
            "handset" => addslashes($_POST['phone']),
            "network" => $network,
            "minutes" => $minutes,
            "texts" => $texts,
            "linerental" => $linerental,
            "inclusive_data" => $data,
            "sort" => $sort,        
            "cashback" => $cashback,
            "effectiverental" => $effectiverental,
            "totalcost" => "",
            "number" => "10",
            "gift" => $gift,         
            "full" => "y"));            
}

require_once(CP_BASE_DIR . '/lib/nusoap.php');
$client = new soapclientNusoap(CP_SOAP_URL_UK_MOBILES);
try {
    $results = $client->call("getHandsetsByQuery", $send);
    $comparePressMobile = "<div id=\"CP_mobiles_results\">";
    
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
                            // Also check for a custom handset name if set
                            $customTitle = "SELECT mhd_handset_title
                                    FROM " . CP_DB_BASE . "comparepress_mobiles_hs_description
                                    WHERE mhd_handset_id = '" . str_replace(" ", "-", $results["details"]["handset_name"]) . "'";
                            $customTitleresult = mysql_query($customTitle);

                            if (@mysql_num_rows($customTitleresult) == 1) {
                                $row = mysql_fetch_array($customTitleresult);
                                $customTitleresult = stripslashes($row['mhd_handset_title']);
                            }
                            else {                   
                                $customTitleresult = $results["details"]["handset_name"];          
                            }                            
                        } else {
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
                            $list .= "<td class=\"CP_mobiles_results_network\"><p><img src=\"" . $val['image_small'] . "\"  alt=\"" . $val["handset_name"] . "\" /><br/><a href=\"".get_option('siteurl')."/".$CP_fake_Category."/". str_replace(" ", "-", $val["handset_name"]) . "/' title=\"" . $val["handset_name"] . " Deals\" />" . $val["handset_name"]. "</a></p></td>";
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
                        $list .= "<td class=\"CP_mobiles_results_information\"><p><strong>" . $val["price"]. $customTitleresult . "</strong><br/>" . ($val["cashback"] != "" ? "<strong>" . $val["cashback"] . "</strong><br/>" : "") . ($val["gift"] != "" ? "<strong>FREE " . $val["gift"] . "</strong><br/>" : "") . str_replace(".", "<br/>", html_entity_decode($val["description"])) . "Total cost " . $val["totalcost"] . " " . $val["tarifflength"] . "</p></td>";
                        break;

                    case "costpermonth":
                        $list .= "<td class=\"CP_mobiles_results_permonth\"><p><strong>" . $val["effectiverental"] . "</strong><br /> a month<br />" . ($val["cashback"] != "" ? "<br />(" . $val["linerental"] . " before cashback)" : "") . "</p></td>";
                        break;

                    case "buy":
                        $list .= "<td class=\"CP_mobiles_results_buy\"><p><a href=\"" . $val["deeplink"] . "&site=" . str_replace("www.", "", $_SERVER['HTTP_HOST']) . "\" target=\"_blank\" rel=\"nofollow\" onclick=\"_gaq.push(['_trackEvent', 'Outbound Links', 'Click', '" . $val["handset_name"] . "-" . $val["retailer"] . "']);\"><img src=\"http://www.mobiles4everyone.com/retailers/" . $val["retailer"] . ".gif\"  alt=\"" . $val["retailer"] . "\" /><br/><img src=\"".CP_BASE_URL."/modules/UK/mobiles/images/See-Mobile-Deal.jpg\"  alt=\"Click Here\" /></a></p></td>";
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
        $comparePressMobile.= "<h4>" . ucwords($results["error"]) . " on " . $_POST['network'] . "</h4>";
    }
} catch (SoapFault $exception) {
    $comparePressMobile .= $exception;
}

$comparePressMobile .= "</div>";

echo $comparePressMobile;
?>