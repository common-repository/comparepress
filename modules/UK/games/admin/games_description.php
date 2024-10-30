<?php
// create new DB Tables
require_once("../wp-config.php");

$connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
$db = mysql_select_db(DB_NAME);

// Need something like the following code for wordPress MU installs
global $wpdb;
$table_prefix = $wpdb->prefix;

// create tables if they dont already exist!
mysql_query("CREATE TABLE IF NOT EXISTS " . CP_DB_BASE . "comparepress_games_game_description (mhd_game_id varchar(30) PRIMARY KEY, mhd_description TEXT)");

if ($_POST['delete'] != "") {
    // enter into database
    $delete = "DELETE FROM " . CP_DB_BASE . "comparepress_games_game_description
			WHERE mhd_game_id = '" . $_POST['m4e_delete_game'] . "'";
    $result = mysql_query($delete);
    $message = "<div class=message>Game Deleted</div>";
}

if ($_POST['edit'] != "") {
    // enter into database
    $select = "SELECT * FROM " . CP_DB_BASE . "comparepress_games_game_description
			WHERE mhd_game_id = '" . $_POST['m4e_edit_game'] . "'";
    $result = mysql_query($select);
    $editrow = mysql_fetch_array($result);
    $message = "";
    $searchtype = "predefined";
}

$cleanGameID = str_replace("/", "", $_POST['game']);

if (($_POST['submit'] == "submit") && ($_POST['m4e_new_game_description'] != "")) {
    // enter into database
    $sql = "INSERT INTO " . CP_DB_BASE . "comparepress_games_game_description
			(mhd_game_id, mhd_description)
			VALUES
			('" . $cleanGameID . "',
			'" . addslashes($_POST['m4e_new_game_description']) . "')";
    $result = mysql_query($sql);
    $message = "<div class=message>Game Description Added</div>";
}
if ($_POST['submit'] == "update") {
    // enter into database
    $sql = "UPDATE " . CP_DB_BASE . "comparepress_games_game_description
			SET mhd_description = '" . addslashes($_POST['m4e_new_game_description']) . "'
			WHERE mhd_game_id = '" . $_POST['game'] . "'";
    $result = mysql_query($sql);
    $message = "<div class=message>Game Description Updated</div>";
    $searchtype = "standard";
}

// gather list of handsets
$select = "SELECT mhd_game_id
			FROM " . CP_DB_BASE . "comparepress_games_game_description
			ORDER BY mhd_game_id";
$selectresult = mysql_query($select);
$client = new soapclientNusoap("http://comparepress.com/soap/games/gamesNewDB.php");
while ($row = mysql_fetch_array($selectresult)) {
    $send = array(array("token" => $validate['m4e_token'], "id" => addslashes($row['mhd_game_id'])));
    try {
        $results = $client->call("getGamePrices", $send);
        $gameTitle = $results[game][title];
    } catch (SoapFault $exception) {
        $gameTitle.= $exception;
    }
    $output .= "<tr>
                <td width=400px>" . str_replace("-", " ", $row['mhd_game_id']) . " - " . $gameTitle . "</td>
                <td width=20px><FORM ACTION=\"\" name=form METHOD=POST><input type=hidden name=m4e_edit_game value='" . $row['mhd_game_id'] . "'><input type=submit name=edit value=edit></FORM></td>
                <td width=20px><FORM ACTION=\"\" name=form METHOD=POST><input type=hidden name=m4e_delete_game value='" . $row['mhd_game_id'] . "'><input type=submit name=delete value=delete></FORM></td>
                </tr>";
}

if ($searchtype == "predefined") {
    $search = "<tr>
            <td width=10%><strong>Game:</strong> " . str_replace("-", " ", $editrow['mhd_game_id']) . "<input type=hidden name=game value='" . $editrow['mhd_game_id'] . "'></td>
                </tr>";
} else {
    $search = "<tr>
            <td width=20%>Game ID</td>
            <td>  		
            <input name=\"game\">
            </td>
            </tr>";
}

echo "<style> .message{width:80%; background-color:#ffffe0; border:1px solid #e6db55; color:black; padding:5px; margin:5px;}></style>" . $message . "<FORM ACTION=\"\" name=form1 METHOD=POST>
	  table width=90% border=1>
	  <tr>
	  <td colspan=2><h1>Custom Game Descriptions</h1></td>
	  </tr>
	  <tr>
          <td colspan=2>This page allows you to enter your own custom video game descriptions! <br /><br />
          You might just want to copy and paste the standard game description in here and fine tune it for your own SEO purposes, <br />
          link to your review of a particular phone or maybe a forum you have to chat about this one handset. <br />
          You can also paste any HTML you want such as <a href='#'/>links</a>, <strong><i>formatting</i></strong> or something else we haven't thought of yet!<br /><br /></td>
	  </tr>
	  <tr>
	  <td>*NOTE: We plan to allow the uploading and downloading of a CSV file so you can quickly add as many custom descriptions as you want.</td>
	  </tr>
	  <tr>
	  <td>
	  <form action=\"\" id=\"search\">
            <table width=100%>
            $search
            <tr>
            <td colspan=2><textarea name=\"m4e_new_game_description\" cols=\"100\" rows=\"10\">" . stripslashes($editrow['mhd_description']) . "</textarea></td>
            </tr>
            <tr>
            <td><input type=submit name=submit value=" . ($searchtype == "predefined" ? "update" : "submit") . "></td>
            </tr>
            </table>
            </form>
	</td></tr>
	<tr>
	<td><h3>List of games with your custom descriptions</h3></td>
	</tr>
	<tr>
        <td>
        <table width=440px border=1>
        " . $output . "
        </table>
        </td>
	</tr>
	</table>
	</form>";
?>