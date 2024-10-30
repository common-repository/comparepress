<?php
// this is sent back to the ComparePressFakePage class and place within a users
// normal blog template as a fake page
$validate = get_option("widget_comparePressAdmin");
//manufacturer = game, phone = platform, sort = sort
$page = $_GET['page'];
// echo $page;
$title = $_GET['game'];
$send = array(array("token" => $validate['m4e_token'], "title" => $title, "platform" => addslashes($_GET['platform']), "number" => "10", "sort" => ($_GET['sort'] != 0 ? addslashes($_GET['sort']) : ""), "full" => "y", "page" => addslashes($page)));
//echo ($_GET['sort']!=0?addslashes($_GET['sort']):"");
require_once(CP_BASE_DIR . '/lib/nusoap.php');
$client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");

try {
    $results = $client->call("getGamesByQuery", $send);
    $comparePressMobile = "<div id=\"CP_games_results\">";
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

    if ($_GET['platform'] == "all") {
        $formatFriendly = "all formats";
    } else {
        if (count($results) > 1) {
            $formatFriendly = $results[games][game][0][platformName];
            if ($formatFriendly == "") {
                $formatFriendly = $_GET['platform'];
            }
        } else {
            $formatFriendly = $results[games][game][platformName];
            if ($formatFriendly == "") {
                $formatFriendly = $_GET['platform'];
            }
        }
    }

    $ComparePressPageTitle = 'Your search results:';

    $comparePressMobile .= "<div id=\"handset_detail\">
				<table width=\"100%\" >
				<tr>
				<td>
                                <h2 class='title'>Results for \"" . $title . "\" on " . $formatFriendly . "</h2>
				</td>
				</tr>
                             	<tr>
                                <td>&nbsp;</td>
                                </tr>
				</table>
				</div>";
    // }

    if (count($results[games]) > 1) {
        $options = get_option("widget_ComparePress_order_results_games");
        $column_headings = get_option("widget_ComparePress_order_headings_games");

        if ($options[1] == "") {
            $options = array("1" => "platform", "2" => "category", "3" => "title", "4" => "released", "5" => "minprice", "6" => "buy");
            update_option("widget_ComparePress_order_results_games", $options);
        }
        if ($column_headings["network"] == "") {
            $column_headings = array("platform" => "Platform", "category" => "Category", "title" => "Title", "released" => "Release Date", "minprice" => "Best Price", "buy" => "Buy");
            update_option("widget_ComparePress_order_headings_games", $column_headings);
        }

        if (is_array($results[games][game][0])) {
            foreach ($results[games][game] as $key => $val) {
                if ($val[thumbnailURL]) {
                    $list = "<td><a href=\"" . get_option('siteurl') . "/" . $CP_fake_Category . "/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . $val[id] . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"Compare " . $val[title] . " Games Prices\"><img alt=\"" . $val[title] . "\" src=\"" . $val[thumbnailURL] . "\" alt=\"" . $val[title] . "\"</a></td>";
                } else {
                    $list = "<td></td>";
                }
                //print_r($results);
                foreach ($options as $optionskey => $optionsval) {
                    switch ($optionsval) {
                        case "platform":
                            $list .= "<td class=\"\"><p>" . $val[platformName] . "</p></td>";
                            break;

                        case "category":
                            $list .= "<td><p><span class=\"\">" . $val[category] . "</span></p></td>";
                            break;

                        case "title":
                            $list .= "<td><p><span class=\"\">" . $val[title] . "</span></p></td>";
                            break;

                        case "released":
                            $list .= "<td><p><span class=\"\">" . $val[released] . "</p></td>";
                            break;

                        case "minprice":
                            $list .= "<td class=\"\"><p><strong>" . ($val[minimumPrice] != "N/A" ? "&pound;" . $val[minimumPrice] : $val[minimumPrice]) . "</strong></p></td>";
                            break;

                        case "buy":
                            $list .= "<td class=\"\"><a href=\"" . get_option('siteurl') . "/" . $CP_fake_Category . "/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . $val[id] . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"Compare " . $val[title] . " Games Prices\"><br/><img src=\"http://mobile-phonedeals.com/images/moreinfogreen.jpg\"  alt=\"Compare Prices\" /></a></td>";
                    }
                    //echo $list;
                }

                $output .= "<tr>" . $list . "</tr>";
            }
        } else {
            if ($results[games][game][thumbnailURL]) {
                $list = "<td><a href=\"" . get_option('siteurl') . "/" . $CP_fake_Category . "/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . $results[games][game][id] . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"Compare " . $results[games][game][title] . " Games Prices\"><img alt=\"" . $results[games][game][title] . "\" src=\"" . $results[games][game][thumbnailURL] . "\" alt=\"" . $results[games][game][title] . "\"</a></td>";
            } else {
                $list = "<td></td>";
            }
            //print_r($results);
            foreach ($options as $optionskey => $optionsval) {
                switch ($optionsval) {
                    case "platform":
                        $list .= "<td class=\"\"><p>" . $results[games][game][platformName] . "</p></td>";
                        break;

                    case "category":
                        $list .= "<td><p><span class=\"\">" . $results[games][game][category] . "</span></p></td>";
                        break;

                    case "title":
                        $list .= "<td><p><span class=\"\">" . $results[games][game][title] . "</span></p></td>";
                        break;

                    case "released":
                        $list .= "<td><p><span class=\"\">" . $results[games][game][released] . "</p></td>";
                        break;

                    case "minprice":
                        $list .= "<td class=\"\"><p><strong>" . ($results[games][game][minimumPrice] != "N/A" ? "&pound;" . $results[games][game][minimumPrice] : $results[games][game][minimumPrice]) . "</strong></p></td>";
                        break;

                    case "buy":
                        $list .= "<td class=\"\"><a href=\"" . get_option('siteurl') . "/" . $CP_fake_Category . "/" . ($url['m4e-htaccessurl-games'] != "" ? $url[''] : "?id=") . $results[games][game][id] . ($url['m4e-htaccessurl-games'] != "" ? "/" : "") . "\" title=\"Compare " . $results[games][game][title] . " Games Prices\"><br/><img src=\"http://mobile-phonedeals.com/images/moreinfogreen.jpg\"  alt=\"Compare Prices\" /></a></td>";
                }
                //echo $list;
            }

            $output .= "<tr>" . $list . "</tr>";
        }

        $headings .= "<th></th>";

        foreach ($options as $key => $val) {
            switch ($val) {
                case "platform":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "category":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "title":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "released":
                    $headings .= "<th>" . $column_headings[$val] . "</th>";
                    break;

                case "minprice":
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
        $nav = 0;
        $navCode = "";
        if ($results[games][ResultsInfo][MoreResultsAvailable] == 1) {
            $nextPage = $page + 1;
            $nav = 1;
            if ($url['m4e-htaccessurl-games'] != "") {
                $nextURL = get_option('siteurl') . "/" . $CP_fake_Category . "/" . $_GET['platform'] . "/" . $_GET['game'] . "/" . $_GET['sort'] . "/" . $nextPage . "/";
            } else {
                $nextURL = get_option('siteurl') . "/" . $CP_fake_Category . "/?page=" . $nextPage . "&platform=" . $_GET['platform'] . "&game=" . $_GET['game'] . "&sort=" . $_GET['sort'];
            }

            $navCode .= "<a href=\"$nextURL\">Next Page</a>";
        }
        if ($page > 1) {
            $previousPage = $page - 1;
            $nav = 1;
            if ($url['m4e-htaccessurl-games'] != "") {
                $previousURL = get_option('siteurl') . "/" . $CP_fake_Category . "/" . $_GET['platform'] . "/" . $_GET['game'] . "/" . $_GET['sort'] . "/" . $previousPage . "/";
            } else {
                $previousURL = get_option('siteurl') . "/" . $CP_fake_Category . "/?page=" . $previousPage . "&platform=" . $_GET['platform'] . "&game=" . $_GET['game'] . "&sort=" . $_GET['sort'];
            }

            $navCode .= "<a href=\"$previousURL\">Previous Page</a>";
        }

        $comparePressMobile .= "</div>" . ($nav = 1 ? "<div id='searchnav'>" : "") . $navCode . ($nav = 1 ? "</div>" : "");
        #echo "<p class=\"credit\">Powered by <a href=\"http://comparepress.com/\" title=\"Compare Press\">ComparePress</a></p>";
    } else {
        $comparePressMobile.= "<h4>" . $results["error"] . "</h4>";
    }
} catch (SoapFault $exception) {
    $comparePressMobile .= $exception;
}

$comparePressMobile .= "</div>";