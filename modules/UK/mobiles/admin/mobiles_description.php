<?php
// create new DB Tables
require_once("../wp-config.php");

$connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
$db = mysql_select_db(DB_NAME);

// Need something like the following code for wordPress MU installs
global $wpdb;
$table_prefix = $wpdb->prefix;
$searchtype = '';
$message = '';
$output = '';

// create tables if they dont already exist!
mysql_query("CREATE TABLE IF NOT EXISTS " . CP_DB_BASE . "comparepress_mobiles_hs_description (mhd_handset_id varchar(30) PRIMARY KEY, mhd_description TEXT, mhd_handset_title TEXT)");

$delete = isset($_POST['delete']); 
if ($delete != "") {
    // enter into database
    $delete = "DELETE FROM " . CP_DB_BASE . "comparepress_mobiles_hs_description
                WHERE mhd_handset_id = '" . $_POST['m4e_delete_handset'] . "'";
    $result = mysql_query($delete);
    $message = "<div class=message>Handset Deleted</div>";
}

$edit = isset($_POST['edit']);
if ($edit != "") {
    // enter into database
    $select = "SELECT * FROM " . CP_DB_BASE . "comparepress_mobiles_hs_description
                WHERE mhd_handset_id = '" . $_POST['m4e_edit_handset'] . "'";
    $result = mysql_query($select);
    $editrow = mysql_fetch_array($result);
    $message = "";
    $searchtype = "predefined";
}

if (isset($_POST['phone'])){$postphone=$_POST["phone"];}else{$postphone = '';}
$cleanHandsetID = str_replace("/", "", $postphone);
$submit = isset($_POST['submit']);

if (($submit == "submit") && ($_POST['m4e_new_handset_description'] != "")) {
    // enter into database
    $sql = "INSERT INTO " . CP_DB_BASE . "comparepress_mobiles_hs_description
            (mhd_handset_id, mhd_description,mhd_handset_title )
            VALUES
            ('" . $cleanHandsetID . "',
            '" . addslashes($_POST['m4e_new_handset_description']) . "',
            '" . addslashes($_POST['m4e_new_handset_title']) . "')";
    $result = mysql_query($sql);
    $message = "<div class=message>Handset Description Added</div>";
}
if ($submit == "update") {
    // enter into database
    $sql = "UPDATE " . CP_DB_BASE . "comparepress_mobiles_hs_description
            SET mhd_description = '" . addslashes($_POST['m4e_new_handset_description']) . "',
            mhd_handset_title = '" . addslashes($_POST['m4e_new_handset_title']) . "'                
            WHERE mhd_handset_id = '" . $_POST['phone'] . "'";
    $result = mysql_query($sql);
    $message = "<div class=message>Handset Description Updated</div>";
    $searchtype = "standard";
}

// gather list of handsets
$select = "SELECT mhd_handset_id
            FROM " . CP_DB_BASE . "comparepress_mobiles_hs_description
            ORDER BY mhd_handset_id";
$selectresult = mysql_query($select);
while ($row = mysql_fetch_array($selectresult)) {
    $output .= "<tr>
            <td width=400px>" . str_replace("-", " ", $row['mhd_handset_id']) . "</td>
            <td width=20px><FORM ACTION=\"\" name=form METHOD=POST><input type=hidden name=m4e_edit_handset value='" . $row['mhd_handset_id'] . "'><input type=submit name=edit value=edit></FORM></td>
            <td width=20px><FORM ACTION=\"\" name=form METHOD=POST><input type=hidden name=m4e_delete_handset value='" . $row['mhd_handset_id'] . "'><input type=submit name=delete value=delete></FORM></td>
            </tr>";
}


if ($searchtype == "predefined") {
    $search = "<tr>
	  	<td><strong>Master Handset Name:</strong> " . str_replace("-", " ", $editrow['mhd_handset_id']) . "<input type=hidden name=phone value='" . $editrow['mhd_handset_id'] . "'></td>
				</tr>";
} else {
    $search = "<tr>
            <td width=20%>Manufacturer</td>
            <td>  		
            <select name=\"manufacturer\" onChange=\"getmodels(this.value, '" . get_option('siteurl') . "', 'contracts');\">
            <option value=\"\">Select</option>
            <option value=\"3\">Three</option>
            <option value=\"acer\">Acer</option>
            <option value=\"alcatel\">Alcatel</option>
            <option value=\"apple\">Apple</option>
            <option value=\"blackberry\">Blackberry</option>
            <option value=\"google\">Google</option>
            <option value=\"hp\">HP</option>
            <option value=\"htc\">HTC</option>
            <option value=\"land-rover\">Land Rover</option>
            <option value=\"lg\">LG</option>						
            <option value=\"motorola\">Motorola</option>
            <option value=\"nokia\">Nokia</option>
            <option value=\"o2\">O2</option>
            <option value=\"orange\">Orange</option>
            <option value=\"palm\">Palm</option>
            <option value=\"samsung\">Samsung</option>
            <option value=\"network\">Simcard</option>                                                
            <option value=\"sonim\">Sonim</option>
            <option value=\"sony\">Sony</option>
            <option value=\"sony-ericsson\">Sony Ericsson</option>
            <option value=\"swap\">Swap</option>
            <option value=\"tmobile\">T-Mobile</option>
            <option value=\"vodafone\">Vodafone</option>
            </select>
            </td>
            </tr>
            <tr>
            <td width=20%>Model</td>
            <td><div id=\"CP_mobiles_select_model\">
            <select name=\"phone\">
            <option value=\"-\"></option>
            </select>
            </div></td>
            </tr>";
}

echo "<STYLE> .message{width:80%; background-color:#ffffe0; border:1px solid #e6db55; color:black; padding:5px; margin:5px;}></STYLE>" . $message . "<FORM ACTION=\"\" name=form1 METHOD=POST>
		<table width=100%>
	  <tr>
	  	<td colspan=2><h1>Custom Handset Descriptions</h1></td>
	  </tr>
	  <tr>
	  	<td colspan=2>This page allows you to enter your own custom mobile phone handset descriptions as well as the name of the handset displayed!<br />
		You might just want to copy and paste the standard handset description in here and fine tune it for your own SEO purposes, <br />
		link to your review of a particular phone or maybe link to a forum you have to chat about this one handset.<br />
		You can also paste any HTML you want such as <a href='#'/>links</a>, <strong><i>formatting</i></strong> or something else we haven't thought of yet!<br /><br /></td>
	  </tr>
	   <tr>
	  	<td><strong>NOTE: If you want to change the Title of a handset only please find the handset and enter BOTH a Custom Handset Title and a Custom Description.</strong></td>
	  </tr>
	  <tr>
	  	<td>
  		<form action=\"\" id=\"search\">
		<table width=100%>
			$search
</td>
        </tr>
	  <tr>
	  	<td>Custom Handset Title: <input type=text name=\"m4e_new_handset_title\" size=\"35\" value=\"".stripslashes($editrow['mhd_handset_title']) ."\"></td>
	  </tr>
	  <tr>
	  	<td>Custom Handset Description:</td>   
    
			<tr>
				<td colspan=2><textarea name=\"m4e_new_handset_description\" cols=\"100\" rows=\"10\">" . stripslashes($editrow['mhd_description']) . "</textarea></td>
			</tr>
			<tr>
				<td><input type=submit name=submit value=" . ($searchtype == "predefined" ? "update" : "submit") . "></td>
			</tr>
			</table>

		</form>
	</td></tr>
	<tr>
		<td><h3>List of Handsets with your custom descriptions</h3></td>
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
