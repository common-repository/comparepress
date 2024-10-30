<?php
// this is sent back to the ComparePressFakePage class and place within a users
// normal blog template as a fake page
$validate = get_option("widget_comparePressAdmin");
$options = get_option("widget_ComparePress_UK_Games_order_results_games_prices");
$returnPrices = ($options[m4e_pricingresults] > 0 ? $options[m4e_pricingresults] : 10);

//manufacturer = name, phone = platform, sort = sort
$send = array(array("token" => $validate['m4e_token'], "id" => urlencode($_GET['id'])));

require_once(CP_BASE_DIR . '/lib/nusoap.php');
$client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");

try {
    $results = $client->call("getGamePrices", $send);

    $custom = "SELECT mhd_description
	FROM " . CP_DB_BASE . "comparepress_games_game_description
	WHERE mhd_game_id = '" . $_GET['id'] . "'";
    $customresult = mysql_query($custom);
    if (@mysql_num_rows($customresult) == 1) {
        $row = mysql_fetch_array($customresult);
        $results["game"]["description"] = stripslashes($row['mhd_description']);
    }
    $comparePressMobile = "<div id=\"CP_games_prices\">";
    // Gets the user's URL preferrence
    $url = get_option("widget_ComparePress_UK_Gamesurls");
    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix-games'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix-games'];
        // used for base deal search url
        $CP_Deal_Search_URL = '';
    } else {
        if ($url['m4e_simpleurl'] != "")
            $CP_fake_Category = $url['m4e_simpleurl'];
        else
            $CP_fake_Category = 'compare-games-deals';
        $CP_Deal_Search_URL = "/" . $CP_fake_Category . "/";
    }

    $ComparePressPageTitle = $results[game][groupTitle] . " on " . $results[game][platformName];

    $comparePressMobile .= "<div id=\"game_detail\">
			<table width=\"100%\">
			<tr>
			<td>
				<div class=\"game_detail_row_solo\">
					<img src='" . $results[game][imageURL] . "'>
					<table width=100%>
						<tr>
							<td class=\"games_detail_row_solo_badge\"><b>Released:</b> " . $results[game][released] . "<br><b>RRP:</b> " . ($results[game][rrp] != "N/A" ? "&pound;" : "") . $results[game][rrp] . "</td>
						</tr>
					</table>
				</div>
				<p class=\"game_description\">" . html_entity_decode($results[game][description]) . "</p>
			</td>
			</tr>
			</table>
			</div>";
    // }

    if (!empty($results["results"])) {
        $options = get_option("widget_ComparePress_UK_Games_order_results_games_prices");
        $column_headings = get_option("widget_ComparePress_UK_Games_order_headings_games_prices");

        if ($options[1] == "") {
            $options = array("1" => "name", "2" => "price", "3" => "postage", "4" => "total", "5" => "availability", "6" => "buy");
            update_option("widget_ComparePress_UK_Games_order_results_games_prices", $options);
        }
        if ($column_headings["name"] == "") {
            $column_headings = array("name" => "Retailer", "price" => "Price", "postage" => "Postage", "total" => "Total", "availability" => "Availability", "buy" => "Buy");
            update_option("widget_ComparePress_UK_Games_order_headings_games_prices", $column_headings);
        }
        foreach ($results[game][retailers][retailer] as $key => $val) {
            if ($returnPrices > 0) {
                $list = "";

                // Skip any	blank records
                if ($val[total] > 0) {

                    foreach ($options as $optionskey => $optionsval) {
                        switch ($optionsval) {
                            case "name":
//							$list .= "<td class=\"\"><p><a href='" . $val[homePageURL] . "'>" . $val[name] . "</a></p></td>";							
                                $list .= "<td><p><a href='" . $val[homePageURL] . "' target=\"_blank\" rel=\"nofollow\"><img src=\"http://gamesprices.co.uk/Retailers/" . $val[name] . ".gif\"></a></p></td>";
                                break;

                            case "price":
                                $list .= "<td><p>" . ($val[price] > 0 ? "&pound;" . $val[price] : "") . "</p></td>";
                                break;

                            case "postage":
                                $list .= "<td><p>" . ($val[postage] > 0 ? "&pound;" . $val[postage] : "") . "</p></td>";
                                break;

                            case "total":
                                $list .= "<td class=\"CP_Games_TotalPrice\"><p><strong><span class=\"\">" . ($val[total] > 0 ? "&pound;" . $val[total] : "") . "</strong></p></td>";
                                break;

                            case "availability":
                                $list .= "<td class=\"\"><p>" . (is_array($val[availability]) ? "" : $val[availability]) . "</p></td>";
                                break;

                            case "buy":

                                $list .= "<td><p><a href=\"" . ($val[productURL] != "" ? $val[productURL] : $val[homePageURL]) . "\" target=\"_blank\" rel=\"nofollow\"><img src=\"http://gamesprices.co.uk/Retailers/Buy-Games-Now.png\"></a></p></td>";

//$list .= "<td><p><a href=\"http://sales.comparepress.com/tracking.php?site=".str_replace("www.", "", $_SERVER['HTTP_HOST'])."&url=" . ($val[productURL]!=""?$val[productURL]:$val[homePageURL])."\" target=\"_blank\" rel=\"nofollow\">Buy Now!</a></p></td>";
                        }
                    }
                }
                $returnPrices--;
                $output .= "<tr>" . $list . "</tr>";
            }
        }

        foreach ($options as $key => $val) {
            switch ($val) {
                case "name":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "price":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "postage":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "total":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "availability":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "buy":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
            }
        }

        $comparePressMobile .= "
		<h2>Cheapest Prices:</h2>
		<div id=\"results\">
			<table width=\"100%\" border=\"0\" cellspacing=\"0\">
			<tr class=\"CP_games_results_TH\">
				" . $headings . "
			</tr>
			" . $output . "
			</table>
		";

        if ($results[games][ResultsInfo][MoreResultsAvailable] == 1) {
            $comparePressMobile .= "<div><a href=\"\">Next Page</a></div>";
        }
        $comparePressMobile .= "</div>";
        // echo "<p class=\"credit\">Powered by <a href=\"http://comparepress.com/\" title=\"Wordpress Price Comparison Plugin\" rel=\"nofollow\">ComparePress</a></p>";
    } else {
        $comparePressMobile.= "<h4>" . $results["error"] . "</h4>";
    }
} catch (SoapFault $exception) {
    $comparePressMobile .= $exception;
}

$comparePressMobile .= "</div>";