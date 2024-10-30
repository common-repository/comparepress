<?php
/**
 * Main shortcode fucntionality on content pages using format
 * [showgames title='Mario' platform='wii' number='x'] - displays x most popular games on platform containing title
 * [showgames title='Mario' platform='wii' number='x' format='long'] - displays x most popular games on platform containing title in long format
 * [showgames platform='wii'] - displays 10 most popular games on platform
 * [showgames platform='wii' format='long'] - displays 10 most popular games on platform in a "long" format
 * [showgames platform='wii' category='adventure'] - displays 10 most popular games in category on platform
 * [showgames platform='wii' category='adventure' sort='releasedate'] - displays 10 most popular games in category on platform, sorted by sort
 * [showgames id='B0021AETOK' type='full'] - displays full pricing details for id
 * [showgames id='B0021AETOK' type='badge' float='left'] - displays picture, title, release date, best price for id, float determines postition
 *
 * These are actual / real WordPress Pages created by the user
 */
// Function to get Wordpress page number (from post/page/1/ etc)
function get_page_num() {
    global $wp;
    // Sanitize the current page, make sure it's an int and that it is at
    // least 1, return 1 by default
    return isset($wp->query_vars['paged']) ? max(1, intval($wp->query_vars['paged'])) : 1;
}

// Function to get URL of Wordpress post
function get_base() {
    global $wp;
    if (preg_match("|{$wp->matched_rule}|", $wp->request, $matches)) {
        return $matches[1];
    } else {
        return false;
    }
}

function sc_showgames($att) {
    // extract any variables that were passed in with the shortcode.
    extract(shortcode_atts(array(
                'title' => '', 'platform' => '', 'id' => '', 'number' => '5', 'type' => '', 'float' => 'right', 'category' => '', 'sort' => '4', 'format' => '', 'page' => '1', 'releasefrom' => ''
                    ), $att));
    if ($title) {
        $do = "title";
    } else if ($platform) {
        $do = "platform";
    } else if ($id) {
        $do = "id";
    }

    // Gets the user's URL preferrence
    $url = get_option("widget_ComparePress_UK_Gamesurls");

    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix-games'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix-games'];
    } else {
        if ($url['m4e_simpleurl_games'] != "")
            $CP_fake_Category = $url['m4e_simpleurl_games'];
        else
            $CP_fake_Category = 'compare-games-deals';
    }

    $validate = get_option("widget_ComparePress_UK_GamesAdmin");

    switch ($do) {
        case "title":
            if ($title != "") {
                $send = array(array("token" => $validate['m4e_token'], "title" => $title, "platform" => ($platform != "" ? $platform : "all"), "sort" => $sort, "releasefrom" => $releasefrom));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");
                $limitCount = $number;
                try {
                    $results = $client->call("getGamesByQuery", $send);
                    // make sure we didn't get an error
                    if ($results[games][ResultsInfo][RowCount] > 0) {

                        // Skip any	blank records
                        if ($val[minimumPrice] != 'N/A') {

                            $topoutput = "<ul class='search_results'>";
                            foreach ($results[games][game] as $key => $val) {
                                if ($limitCount > 0) {
                                    if ($format == 'long') {
                                        $topoutput .= "<li class='results_item'>";
                                        $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val[id]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val[title] . " deals\">";
                                        $topoutput .= "<img src=\"" . $val[thumbnailURL] . "\" alt=\"" . $val[title] . "\"></a>";
                                        $topoutput .= "<h3>" . $val[title] . "</h3></a>";
                                        $topoutput .= "<span>" . $val[platformName] . "</span> | ";
                                        $topoutput .= "<span>" . $val[category] . "</span> | <span>Release Date: " . $val[released] . "</span><br>";
                                        $topoutput .= "<span class='rrp'>RRP: <strike>£" . $val[rrp] . "</strike></span>";
                                        $topoutput .= "<span class='from_price'>from £" . $val[minimumPrice] . "</span>";
                                        $topoutput .= "<p>" . (strlen($val[description]) > 200 ? substr($val[description], 0, 200) . "..." : $val[description]) . "</p>";
                                        $topoutput .= "</li>";
                                    } else {
                                        $topoutput .= "<div class=CP_games_top_games_widget>";
                                        $topoutput .= "<div style='height: 70px;'><img src=\"" . $val[thumbnailURL] . "\" alt=\"" . $val[title] . "\"></div></a><br />";
                                        $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val[id]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val[id] . " deals\">";
                                        $topoutput .= "<p class='from_price'>" . ($val['minimumPrice'] > 0 ? "&pound;" . money_format('%.2n', $val['minimumPrice']) : "N/A") . "</p>" . $val[title] . "</a></div>";
                                    }
                                    $limitCount--;
                                }
                            }
                        }
                    }

                    if ($topoutput != "") {
                        $show_html = "<div class=\"CP_games_only\">
						<div class=\"CP_games_title\"><h2>" . ucwords($title) . " Video Games" . (($platform != "" && strtolower($platform) != 'all') ? " on " . ucfirst($val[platformName]) : "") . "</h2></div>
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

        case "platform":
            if ($platform != "") {
                $page = get_page_num();
                $category = str_replace(" ", "%20", $category);
                 $cpsort = ''; 
                if (isset($_GET['sort'])) {
                    $cpsort = $_GET['sort'];
                }                     
                $send = array(array("token" => $validate['m4e_token'], "platform" => $platform, "category" => $category, "number" => $number, "sort" => $sort, "title" => "", "page" => $page));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");                
                try {
                    $results = $client->call("getGamesByQuery", $send);

                    if ($results["games"]["ResultsInfo"]["RowCount"] > 0) {
                        $topoutput = "<ul class='search_results'>";
                        $topoutput .= "<li class='options'><ul class='filter'><li>Sort: <select class='sortselector' onchange='location = \"" . get_permalink(get_the_ID()) . "page/" . $page . "/?sort=" . "\" + this.options[this.selectedIndex].value;'>
                            <option value='1' " . ($cpsort == 1 ? "selected" : "") . ">Price</option>
                            <option value='2' " . ($cpsort == 2 ? "selected" : "") . ">Title</option>
                            <option value='3' " . ($cpsort == 3 ? "selected" : "") . ">Release Date</option>
                            <option value='4' " . ($cpsort == 4 ? "selected" : "") . ">Most Popular</option>
                            <option value='5' " . ($cpsort == 5 ? "selected" : "") . ">Best Deals</option>
                            </select></li></ul><span class='pager'>";
                        //if ($results[games][ResultsInfo][MoreResultsAvailable]) {
                        $url = get_option("widget_ComparePress_UK_Gamesurls");
                        $previousPages = "";
                        $nextPages = "";
                        for ($a = $page - 1; $a > ($page - 6); $a--) {
                            if ($a > 0) {
//                                if ($url['m4e-htaccessurl-games'] != "" ) {
//                                    $nextURL = get_option('siteurl')."/" . $platform . "/" . $category . "/" . $a . "/";
//                                } else {
//                                    $nextURL = get_option('siteurl')."/?page=".$a."&platform=".$platform."&category=".$category;
//                                }
                                $nextURL = get_permalink(get_the_ID()) . "page/" . $a . "/" . ($sort ? "?sort=" . $sort : "");
                                $previousPages = "<a href='" . $nextURL . "'>" . $a . "</a> " . $previousPages;
                            }
                        }
                        if ($previousPages) {
                            $nextURL = get_permalink(get_the_ID()) . "page/" . ($page - 1) . "/" . ($sort ? "?sort=" . $sort : "");
                            $firstURL = get_permalink(get_the_ID()) . "page/1/" . ($sort ? "?sort=" . $sort : "");
                            $previousPages = "<a href='" . $firstURL . "'><<</a> <a href='" . $nextURL . "'><</a> " . $previousPages;
                        }
                        for ($a = $page + 1; $a < ($page + 6); $a++) {
                            if (ceil($results["games"]["ResultsInfo"]["RowCount"] / 10) >= $a) {
//                                if ($url['m4e-htaccessurl-games'] != "" ) {
//                                    $nextURL = get_option('siteurl')."/" . $platform . "/" . $category . "/" . $a . "/";
//                                } else {
//                                    $nextURL = get_option('siteurl')."/?page=".$a."&platform=".$platform."&category=".$category;
//                                }
                                $nextURL = get_permalink(get_the_ID()) . "page/" . $a . "/" . ($sort ? "?sort=" . $sort : "");
                                $nextPages .= " <a href='" . $nextURL . "'>" . $a . "</a>";
                            }
                        }
                        if ($nextPages) {
                            $nextURL = get_permalink(get_the_ID()) . "page/" . ($page + 1) . "/" . ($sort ? "?sort=" . $sort : "");
                            $lastURL = get_permalink(get_the_ID()) . "page/" . ceil($results["games"]["ResultsInfo"]["RowCount"] / 10) . "/" . ($sort ? "?sort=" . $sort : "");
                            $nextPages .= " <a href='" . $nextURL . "'>></a>" . " <a href='" . $lastURL . "'>>></a>";
                        }
                        $currentpage = $page;
                        $topoutput .= $previousPages . $currentpage . $nextPages;
                        $bottomoutput = $topoutput;
                        //}
                        $topoutput .= "</span></li>";

                        if (is_array($results["games"]["game"][0])) {
                            foreach ($results["games"]["game"] as $key => $val) {
                                if ($format == "long") {
                                    $topoutput .= "<li class='results_item'>";
                                    $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val['id']) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val['title'] . " deals\">";
                                    $topoutput .= "<img src=\"" . $val['thumbnailURL'] . "\" alt=\"" . $val['title'] . "\"></a>";
                                    $topoutput .= "<h3>" . $val['title'] . "</h3></a>";
                                    $topoutput .= "<span>" . $val['platformName'] . "</span> | ";
                                    $topoutput .= "<span>" . $val['category'] . "</span> | <span>Release Date: " . $val['released'] . "</span><br>";
                                    $topoutput .= "<span class='rrp'>RRP: <strike>£" . $val['rrp'] . "</strike></span>";
                                    $topoutput .= "<span class='from_price'>from £" . $val['minimumPrice'] . "</span>";
                                    $topoutput .= "<p>" . (strlen($val['description']) > 200 ? substr($val['description'], 0, 200) . "..." : $val['description']) . "</p>";
                                    $topoutput .= "</li>";
                                } else {
                                    $topoutput .= "<div class=CP_games_box>";
                                    $topoutput .= "<img src=\"" . $val['thumbnailURL'] . "\" alt=\"" . $val['title'] . "\"></a><br />";
                                    $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val['id']) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val['id'] . " deals\">";
                                    $topoutput .= $val['title'] . "</a></div>";
                                }
                            }
                        } else {
                            if ($format == "long") {
                                $topoutput .= "<li class='results_item'>";
                                $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $results["games"]["game"]["id"]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $results["games"]["game"]["title"] . " deals\">";
                                $topoutput .= "<img src=\"" . $results["games"]["game"]["thumbnailURL"] . "\" alt=\"" . $results["games"]["game"]["title"] . "\"></a>";
                                $topoutput .= "<h3>" . $results["games"]["game"]["title"] . "</h3></a>";
                                $topoutput .= "<span>" . $results["games"]["game"]["platformName"] . "</span> | ";
                                $topoutput .= "<span>Release Date: " . $results["games"]["game"]["released"] . "</span><br>";
                                $topoutput .= "<span class='rrp'>RRP: <strike>£" . $results["games"]["game"]["rrp"] . "</strike></span>";
                                $topoutput .= "<span class='from_price'>from £" . $results["games"]["game"]["minimumPrice"] . "</span>";
                                $topoutput .= "<p>" . (strlen($results["games"]["game"]["description"]) > 200 ? substr($results["games"]["game"]["description"], 0, 200) . "..." : $results["games"]["game"]["description"]) . "</p>";
                                $topoutput .= "</li>";
                            } else {
                                $topoutput .= "<div class=CP_games_box>";
                                $topoutput .= "<img src=\"" . $results["games"]["game"]["thumbnailURL"] . "\" alt=\"" . $results["games"]["game"]["title"] . "\"></a><br />";
                                $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $results["games"]["game"]["id"]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $results["games"]["game"]["id"] . " deals\">";
                                $topoutput .= $results[games][game][title] . "</a></div>";
                            }
                            $val[platformName] = $results["games"]["game"]["platformName"];
                        }
                    } else {
                        echo "<h3>No games found</h3>";
                    }

                    switch ($sort) {
                        case 1:
                            $by = "Price";
                            break;
                        case 2:
                            $by = "Title";
                            break;
                        case 3:
                            $by = "Release Date";
                            break;
                        case 4:
                            $by = "Popularity";
                            break;
                        case 5:
                            $by = "Cheapest Price / Deal";
                            break;
                    }

                    if ($topoutput != "") {
                        $show_html = "<div class=\"CP_games_only\">
						<div class=\"CP_games_title\"><h2>" . ($category != "" ? ucwords(urldecode($category)) . " " : "") . "Video Games" . (($platform != "" && strtolower($platform) != 'all') ? " on " . ucfirst($val['platformName']) : "") . " sorted by " . $by . "</h2></div>
						" . $topoutput . $bottomoutput . "
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

        case "id":
            if ($id != "") {
                $send = array(array("token" => $validate['m4e_token'], "id" => addslashes($id)));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");
                $limitCount = $number;

                try {
                    $results = $client->call("getGamePrices", $send);
                    $custom = "SELECT mhd_description
						FROM " . CP_DB_BASE . "comparepress_games_game_description
						WHERE mhd_game_id = '" . $id . "'";
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
                        if ($url['m4e_simpleurl_games'] != "")
                            $CP_fake_Category = $url['m4e_simpleurl_games'];
                        else
                            $CP_fake_Category = 'compare-games-deals';
                        $CP_Deal_Search_URL = "/" . $CP_fake_Category . "/";
                    }

                    $ComparePressPageTitle = $results[game][groupTitle] . " on " . $results[game][platformName];

                    if (($type == "full") || ($type == "description"))
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
								<p>" . $results[game][description] . "</p>
							</td>
							</tr>
							</table>
							</div>";

                    if ($type == "badge")
                        return "<div class=\"game_badge_$float\">
							<img src='" . $results[game][imageURL] . "'>
								<center><table>
									<tr>
										<td class=\"game_badge_details\"><b>Released:</b> " . $results[game][released] . "<br><b>RRP:</b> " . ($results[game][rrp] != "N/A" ? "&pound;" : "") . $results[game][rrp] . "<br><b>Best Price:</b> " . ($results[game][minimumPrice] != "N/A" ? "&pound;" : "") . $results[game][minimumPrice] . "<br><a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $id) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"Compare " . $results[game][title] . " prices\">Buy Now</a></td>
									</tr>
								</table></center>
						</div>";

                    if ((count($results) > 0) && (($type == "full") || ($type == "prices"))) {

                        $options = get_option("widget_ComparePress_UK_Games_order_results_games_prices");
                        $column_headings = get_option("widget_ComparePress_UK_Games_order_headings_games_prices");
                        $returnPrices = ($options[m4e_pricingresults] > 0 ? $options[m4e_pricingresults] : 10);
                        if ($options[1] == "") {
                            $options = array("1" => "name", "2" => "price", "3" => "postage", "4" => "total", "5" => "availability", "6" => "buy");
                            update_option("widget_ComparePress_UK_Games_order_results_games_prices", $options);
                        }
                        if ($column_headings["name"] == "") {
                            $column_headings = array("name" => "Retailer", "price" => "Price", "postage" => "Postage", "total" => "Total", "availability" => "Availability", "buy" => "Buy");
                            update_option("widget_ComparePress_UK_Games_order_headings_games_prices", $column_headings);
                        }


                        foreach ($results[game][retailers][retailer] as $key => $val) {
                            if ($limitCount > 0) {
                                if ($returnPrices > 0) {
                                    $list = "";

                                    // Skip any	blank records
                                    if ($val[total] > 0) {

                                        foreach ($options as $optionskey => $optionsval) {
                                            switch ($optionsval) {
                                                case "name":
                                                    $list .= "<td><p><a href='" . $val[homePageURL] . "' target=\"_blank\" rel=\"nofollow\"><img src=\"http://gamesprices.co.uk/Retailers/" . $val[name] . ".gif\"></a></p></td>";
                                                    break;

                                                case "price":
                                                    $list .= "<td><p>" . ($val[price] > 0 ? "&pound;" . $val[price] : "") . "</p></td>";
                                                    break;

                                                case "postage":
                                                    $list .= "<td><p>" . ($val[postage] > 0 ? "&pound;" . $val[postage] : "") . "</p></td>";
                                                    break;

                                                case "total":
                                                    $list .= "<td ><p><strong><span class=\"\">" . ($val[total] > 0 ? "&pound;" . $val[total] : "") . "</strong></p></td>";
                                                    break;

                                                case "availability":
                                                    $list .= "<td><p>" . (is_array($val[availability]) ? "" : $val[availability]) . "</p></td>";
                                                    break;

                                                case "buy":

                                                    $list .= "<td><p><a href=\"" . ($val[productURL] != "" ? $val[productURL] : $val[homePageURL]) . "\" target=\"_blank\" rel=\"nofollow\"><img src=\"http://gamesprices.co.uk/Retailers/Buy-Games-Now.png\"></a></p></td>";
                                            }
                                        }
                                    }
                                    $returnPrices--;
                                    $output .= "<tr>" . $list . "</tr>";
                                }
                                $limitCount--;
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

                        $comparePressMobile .= "<div id=\"results\">
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
                        #echo "<p class=\"credit\">Powered by <a href=\"http://comparepress.com/\" title=\"Compare Press\">ComparePress</a></p>";
                    } else {
                        $comparePressMobile.= "<h4>" . $results["error"] . "</h4>";
                    }
                } catch (SoapFault $exception) {
                    $comparePressMobile .= $exception;
                }

                $comparePressMobile .= "</div>";
                // return our results
                return $comparePressMobile;
            }
            break;
    }
}

function sc_showhardware($att) {
    // extract any variables that were passed in with the shortcode.
    extract(shortcode_atts(array(
                'title' => '', 'platform' => '', 'id' => '', 'number' => '5', 'type' => '', 'float' => 'right', 'category' => '', 'sort' => '4', 'format' => '', 'page' => '1', 'prodtype' => '1'
                    ), $att));
    if ($title) {
        $do = "title";
    } else if ($platform) {
        $do = "platform";
    } else if ($id) {
        $do = "id";
    }

    // Gets the user's URL preferrence
    $url = get_option("widget_ComparePress_UK_Gamesurls");

    // logic to see what sort of URLS we are using...
    if ($url['ComparePress-htaccessPrefix-games'] != "") {
        $CP_fake_Category = $url['ComparePress-htaccessPrefix-games'];
    } else {
        if ($url['m4e_simpleurl_games'] != "")
            $CP_fake_Category = $url['m4e_simpleurl_games'];
        else
            $CP_fake_Category = 'compare-games-deals';
    }

    $validate = get_option("widget_ComparePress_UK_GamesAdmin");

    switch ($do) {
        case "title":
            if ($title != "") {
                $send = array(array("token" => $validate['m4e_token'], "title" => $title, "platform" => ($platform != "" ? $platform : "all"), "sort" => $sort, "prodtype" => $prodtype));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");
                $limitCount = $number;
                try {
                    $results = $client->call("getGamesByQuery", $send);
                    // make sure we didn't get an error
                    if ($results[games][ResultsInfo][RowCount] > 0) {

                        // Skip any	blank records
                        if ($val[minimumPrice] != 'N/A') {

                            $topoutput = "<ul class='search_results'>";
                            foreach ($results[games][game] as $key => $val) {
                                if ($limitCount > 0) {
                                    if ($format == 'long') {
                                        $topoutput .= "<li class='results_item'>";
                                        $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val[id]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val[title] . " deals\">";
                                        $topoutput .= "<img src=\"" . $val[thumbnailURL] . "\" alt=\"" . $val[title] . "\"></a>";
                                        $topoutput .= "<h3>" . $val[title] . "</h3></a>";
                                        $topoutput .= "<span>" . $val[platformName] . "</span> | ";
                                        $topoutput .= "<span>" . $val[category] . "</span> | <span>Release Date: " . $val[released] . "</span><br>";
                                        $topoutput .= "<span class='rrp'>RRP: <strike>£" . $val[rrp] . "</strike></span>";
                                        $topoutput .= "<span class='from_price'>from £" . $val[minimumPrice] . "</span>";
                                        $topoutput .= "<p>" . (strlen($val[description]) > 200 ? substr($val[description], 0, 200) . "..." : $val[description]) . "</p>";
                                        $topoutput .= "</li>";
                                    } else {
                                        $topoutput .= "<div class=CP_games_box>";
                                        $topoutput .= "<img src=\"" . $val[thumbnailURL] . "\" alt=\"" . $val[title] . "\"></a><br />";
                                        $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val[id]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val[id] . " deals\">";
                                        $topoutput .= $val[title] . "</a></div>";
                                    }
                                    $limitCount--;
                                }
                            }
                        }
                    }

                    if ($topoutput != "") {
                        $show_html = "<div class=\"CP_games_only\">
						<div class=\"CP_games_title\"><h2>" . ucfirst($title) . " Video Games" . (($platform != "" && strtolower($platform) != 'all') ? " on " . ucfirst($val[platformName]) : "") . "</h2></div>
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

        case "platform":
            if ($platform != "") {
                $page = get_page_num();
                if ($_GET["sort"]) {
                    $sort = $_GET["sort"];
                }

                $category = str_replace(" ", "%20", $category);

                $send = array(array("token" => $validate['m4e_token'], "platform" => $platform, "category" => $category, "number" => $number, "sort" => $sort, "title" => "", "page" => $page, "prodtype" => $prodtype));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");

                try {
                    $results = $client->call("getGamesByQuery", $send);

                    if ($results[games][ResultsInfo][RowCount] > 0) {
                        $topoutput = "<ul class='search_results'>";
                        $topoutput .= "<li class='options'><ul class='filter'><li>Sort: <select class='sortselector' onchange='location = \"" . get_permalink($post->ID) . "page/" . $page . "/?sort=" . "\" + this.options[this.selectedIndex].value;'>
                            <option value='1' " . ($sort == 1 ? "selected" : "") . ">Price</option>
                            <option value='2' " . ($sort == 2 ? "selected" : "") . ">Title</option>
                            <option value='3' " . ($sort == 3 ? "selected" : "") . ">Release Date</option>
                            <option value='4' " . ($sort == 4 ? "selected" : "") . ">Most Popular</option>
                            <option value='5' " . ($sort == 5 ? "selected" : "") . ">Best Deals</option>
                            </select></li></ul><span class='pager'>";
                        //if ($results[games][ResultsInfo][MoreResultsAvailable]) {
                        $url = get_option("widget_ComparePress_UK_Gamesurls");
                        $previousPages = "";
                        $nextPages = "";
                        for ($a = $page - 1; $a > ($page - 6); $a--) {
                            if ($a > 0) {
//                                if ($url['m4e-htaccessurl-games'] != "" ) {
//                                    $nextURL = get_option('siteurl')."/" . $platform . "/" . $category . "/" . $a . "/";
//                                } else {
//                                    $nextURL = get_option('siteurl')."/?page=".$a."&platform=".$platform."&category=".$category;
//                                }
                                $nextURL = get_permalink($post->ID) . "page/" . $a . "/" . ($sort ? "?sort=" . $sort : "");
                                $previousPages = "<a href='" . $nextURL . "'>" . $a . "</a> " . $previousPages;
                            }
                        }
                        if ($previousPages) {
                            $nextURL = get_permalink($post->ID) . "page/" . ($page - 1) . "/" . ($sort ? "?sort=" . $sort : "");
                            $firstURL = get_permalink($post->ID) . "page/1/" . ($sort ? "?sort=" . $sort : "");
                            $previousPages = "<a href='" . $firstURL . "'><<</a> <a href='" . $nextURL . "'><</a> " . $previousPages;
                        }
                        for ($a = $page + 1; $a < ($page + 6); $a++) {
                            if (ceil($results[games][ResultsInfo][RowCount] / 10) >= $a) {
//                                if ($url['m4e-htaccessurl-games'] != "" ) {
//                                    $nextURL = get_option('siteurl')."/" . $platform . "/" . $category . "/" . $a . "/";
//                                } else {
//                                    $nextURL = get_option('siteurl')."/?page=".$a."&platform=".$platform."&category=".$category;
//                                }
                                $nextURL = get_permalink($post->ID) . "page/" . $a . "/" . ($sort ? "?sort=" . $sort : "");
                                $nextPages .= " <a href='" . $nextURL . "'>" . $a . "</a>";
                            }
                        }
                        if ($nextPages) {
                            $nextURL = get_permalink($post->ID) . "page/" . ($page + 1) . "/" . ($sort ? "?sort=" . $sort : "");
                            $lastURL = get_permalink($post->ID) . "page/" . ceil($results[games][ResultsInfo][RowCount] / 10) . "/" . ($sort ? "?sort=" . $sort : "");
                            $nextPages .= " <a href='" . $nextURL . "'>></a>" . " <a href='" . $lastURL . "'>>></a>";
                        }
                        $currentpage = $page;
                        $topoutput .= $previousPages . $currentpage . $nextPages;
                        $bottomoutput = $topoutput;
                        //}
                        $topoutput .= "</span></li>";

                        if (is_array($results[games][game][0])) {
                            foreach ($results[games][game] as $key => $val) {
                                if ($format == "long") {
                                    $topoutput .= "<li class='results_item'>";
                                    $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val[id]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val[title] . " deals\">";
                                    $topoutput .= "<img src=\"" . $val[thumbnailURL] . "\" alt=\"" . $val[title] . "\"></a>";
                                    $topoutput .= "<h3>" . $val[title] . "</h3></a>";
                                    $topoutput .= "<span>" . $val[platformName] . "</span> | ";
                                    $topoutput .= "<span>" . $val[category] . "</span> | <span>Release Date: " . $val[released] . "</span><br>";
                                    $topoutput .= "<span class='rrp'>RRP: <strike>£" . $val[rrp] . "</strike></span>";
                                    $topoutput .= "<span class='from_price'>from £" . $val[minimumPrice] . "</span>";
                                    $topoutput .= "<p>" . (strlen($val[description]) > 200 ? substr($val[description], 0, 200) . "..." : $val[description]) . "</p>";
                                    $topoutput .= "</li>";
                                } else {
                                    $topoutput .= "<div class=CP_games_box>";
                                    $topoutput .= "<img src=\"" . $val[thumbnailURL] . "\" alt=\"" . $val[title] . "\"></a><br />";
                                    $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $val[id]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $val[id] . " deals\">";
                                    $topoutput .= $val[title] . "</a></div>";
                                }
                            }
                        } else {
                            if ($format == "long") {
                                $topoutput .= "<li class='results_item'>";
                                $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $results[games][game][id]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $results[games][game][title] . " deals\">";
                                $topoutput .= "<img src=\"" . $results[games][game][thumbnailURL] . "\" alt=\"" . $results[games][game][title] . "\"></a>";
                                $topoutput .= "<h3>" . $results[games][game][title] . "</h3></a>";
                                $topoutput .= "<span>" . $results[games][game][platformName] . "</span> | ";
                                $topoutput .= "<span>Release Date: " . $results[games][game][released] . "</span><br>";
                                $topoutput .= "<span class='rrp'>RRP: <strike>£" . $results[games][game][rrp] . "</strike></span>";
                                $topoutput .= "<span class='from_price'>from £" . $results[games][game][minimumPrice] . "</span>";
                                $topoutput .= "<p>" . (strlen($results[games][game][description]) > 200 ? substr($results[games][game][description], 0, 200) . "..." : $results[games][game][description]) . "</p>";
                                $topoutput .= "</li>";
                            } else {
                                $topoutput .= "<div class=CP_games_box>";
                                $topoutput .= "<img src=\"" . $results[games][game][thumbnailURL] . "\" alt=\"" . $results[games][game][title] . "\"></a><br />";
                                $topoutput .= "<a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $results[games][game][id]) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $results[games][game][id] . " deals\">";
                                $topoutput .= $results[games][game][title] . "</a></div>";
                            }
                            $val[platformName] = $results[games][game][platformName];
                        }
                    } else {
                        echo "<h3>No games found</h3>";
                    }

                    switch ($sort) {
                        case 1:
                            $by = "Price";
                            break;
                        case 2:
                            $by = "Title";
                            break;
                        case 3:
                            $by = "Release Date";
                            break;
                        case 4:
                            $by = "Popularity";
                            break;
                        case 5:
                            $by = "Cheapest Price / Deal";
                            break;
                    }

                    if ($topoutput != "") {
                        $show_html = "<div class=\"CP_games_only\">
						<div class=\"CP_games_title\"><h2>" . ($category != "" ? ucwords(urldecode($category)) . " " : "") . "Video Games" . (($platform != "" && strtolower($platform) != 'all') ? " on " . ucfirst($val[platformName]) : "") . " sorted by " . $by . "</h2></div>
						" . $topoutput . $bottomoutput . "
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

        case "id":
            if ($id != "") {

                $send = array(array("token" => $validate['m4e_token'], "id" => addslashes($id), "prodtype" => $prodtype));
                require_once(CP_BASE_DIR . '/lib/nusoap.php');
                $client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");
                $limitCount = $number;

                try {
                    $results = $client->call("getGamePrices", $send);
                    $custom = "SELECT mhd_description
						FROM " . CP_DB_BASE . "comparepress_games_game_description
						WHERE mhd_game_id = '" . $id . "'";
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
                        if ($url['m4e_simpleurl_games'] != "")
                            $CP_fake_Category = $url['m4e_simpleurl_games'];
                        else
                            $CP_fake_Category = 'compare-games-deals';
                        $CP_Deal_Search_URL = "/" . $CP_fake_Category . "/";
                    }

                    $ComparePressPageTitle = $results[game][groupTitle] . " on " . $results[game][platformName];

                    if (($type == "full") || ($type == "description"))
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
								<p>" . $results[game][description] . "</p>
							</td>
							</tr>
							</table>
							</div>";

                    if ($type == "badge")
                        return "<div class=\"game_badge_$float\">
							<img src='" . $results[game][imageURL] . "'>
								<center><table>
									<tr>
										<td class=\"game_badge_details\"><b>Released:</b> " . $results[game][released] . "<br><b>RRP:</b> " . ($results[game][rrp] != "N/A" ? "&pound;" : "") . $results[game][rrp] . "<br><b>Best Price:</b> " . ($results[game][minimumPrice] != "N/A" ? "&pound;" : "") . $results[game][minimumPrice] . "<br><a href=\"" . get_option('siteurl') . "/$CP_fake_Category/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . str_replace(" ", "-", $id) . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"" . $id . " deals\">Buy Now</a></td>
									</tr>
								</table></center>
						</div>";

                    if ((count($results) > 0) && (($type == "full") || ($type == "prices"))) {

                        $options = get_option("widget_ComparePress_UK_Games_order_results_games_prices");
                        $column_headings = get_option("widget_ComparePress_UK_Games_order_headings_games_prices");
                        $returnPrices = ($options[m4e_pricingresults] > 0 ? $options[m4e_pricingresults] : 10);
                        if ($options[1] == "") {
                            $options = array("1" => "name", "2" => "price", "3" => "postage", "4" => "total", "5" => "availability", "6" => "buy");
                            update_option("widget_ComparePress_UK_Games_order_results_games_prices", $options);
                        }
                        if ($column_headings["name"] == "") {
                            $column_headings = array("name" => "Retailer", "price" => "Price", "postage" => "Postage", "total" => "Total", "availability" => "Availability", "buy" => "Buy");
                            update_option("widget_ComparePress_UK_Games_order_headings_games_prices", $column_headings);
                        }


                        foreach ($results[game][retailers][retailer] as $key => $val) {
                            if ($limitCount > 0) {
                                if ($returnPrices > 0) {
                                    $list = "";

                                    // Skip any	blank records
                                    if ($val[total] > 0) {

                                        foreach ($options as $optionskey => $optionsval) {
                                            switch ($optionsval) {
                                                case "name":
                                                    $list .= "<td><p><a href='" . $val[homePageURL] . "' target=\"_blank\" rel=\"nofollow\"><img src=\"http://gamesprices.co.uk/Retailers/" . $val[name] . ".gif\"></a></p></td>";
                                                    break;

                                                case "price":
                                                    $list .= "<td><p>" . ($val[price] > 0 ? "&pound;" . $val[price] : "") . "</p></td>";
                                                    break;

                                                case "postage":
                                                    $list .= "<td><p>" . ($val[postage] > 0 ? "&pound;" . $val[postage] : "") . "</p></td>";
                                                    break;

                                                case "total":
                                                    $list .= "<td ><p><strong><span class=\"\">" . ($val[total] > 0 ? "&pound;" . $val[total] : "") . "</strong></p></td>";
                                                    break;

                                                case "availability":
                                                    $list .= "<td><p>" . (is_array($val[availability]) ? "" : $val[availability]) . "</p></td>";
                                                    break;

                                                case "buy":

                                                    $list .= "<td><p><a href=\"" . ($val[productURL] != "" ? $val[productURL] : $val[homePageURL]) . "\" target=\"_blank\" rel=\"nofollow\"><img src=\"http://gamesprices.co.uk/Retailers/Buy-Games-Now.png\"></a></p></td>";
                                            }
                                        }
                                    }
                                    $returnPrices--;
                                    $output .= "<tr>" . $list . "</tr>";
                                }
                                $limitCount--;
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

                        $comparePressMobile .= "<div id=\"results\">
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
                    } else {
                        $comparePressMobile.= "<h4>" . $results["error"] . "</h4>";
                    }
                } catch (SoapFault $exception) {
                    $comparePressMobile .= $exception;
                }

                $comparePressMobile .= "</div>";
                // return our results
                return $comparePressMobile;
            }
            break;
    }
}
?>