<?php
// @todo : Create Affiliate network ID entry area for linking into Affiliate Window etc.
$options = get_option("widget_ComparePress_UK_Gamesurls");

if ($_POST['submit'] != "") {

	$options['m4e_simpleurl_games'] = htmlspecialchars($_POST['m4e-simpleurl-games']);

//  Did have a seperate search url stucture to get urls like this:
//	/mobile-search/Apple-iPhone-3G-8GB/Apple/contracts/-/-/-/
//  but now going to just use the same m4e_simpleurl_games urls
//	mobile-phone-deals/?phone=$1&manufacturer=$2&contract_type=$3&minutes=$4&texts=$5&line_rental=$6
//	$options['m4e_searchurl_games'] = htmlspecialchars($_POST['m4e-searchurl']);

		$options['m4e_searchurl_games'] = htmlspecialchars($_POST['m4e_simpleurl_games']);

        # New option to use htaccess rules
        $options['m4e-htaccessurl-games'] = htmlspecialchars($_POST['m4e-htaccessurl-games']);
        $options['ComparePress-htaccessPrefix-games'] = htmlspecialchars($_POST['ComparePress-htaccessPrefix-games']);

		update_option("widget_ComparePress_UK_Gamesurls", $options);

}

echo "<FORM ACTION=\"\" name=form1 METHOD=POST>
		<table width=90% BORDER=1>
	  <tr>
	  	<td colspan=2><h1>URL Settings</h1></td>
	  </tr>
	  <tr>
	  	<td colspan=2><strong>Warning:</strong> The Simple Games Deals url (slug) below needs to be unique on your blog</td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
	   <tr>
	  	<td colspan=2>The setting below changes the urls of your video game comparison search results. You can change this from it's default (www.mysite.com/<strong>compare-game-deals</strong>/?id=B000FQ9QVI) value in order to make your site search urls unique.</td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>".
//	  <tr>
//	  	<td>Handset Search URL Prefix</td>
//		<td><input type=text name=m4e-searchurl-games value=\"".$options['m4e_searchurl_games']."\"></td>
//	  </tr>
	  "<tr>
	  	<td>Unique deals URL Prefix</td>";


if ($options['ComparePress-htaccessPrefix-games'] == "") {
    $URLusing = $options['m4e_simpleurl_games'];
}
else
{  $URLusing = "";
}

echo     "<td><input type=text name=m4e-simpleurl-games value=\"".$URLusing."\"></td>
	  </tr>
	  <tr>
	  	<td><input type=submit name=submit value=submit></td>
	  </tr>
	    <tr>
	  	<td colspan=2><h2>Want even prettier URLS?</h2></td>
	  </tr>
	  <tr>
	  <td colspan=2>If you know what your <strong>.htaccess</strong> file is then you can customise the video game deals output even more. The details of how to do this are outlined below but please do not venture further unless you are 100% comfortable with your .htacess!
	  </td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
	  <tr>
	  	<td>Want to use .htacess rules instead?<br />Type YES</td>
		<td><input type=text name=m4e-htaccessurl-games value=\"".$options['m4e-htaccessurl-games']."\"></td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
		<tr>
	  	<td>Alternative .htaccess deals URL Prefix</td>
		<td><input type=text name=ComparePress-htaccessPrefix-games value=\"".$options['ComparePress-htaccessPrefix-games']."\"></td>
	  </tr>
	  <tr>
	  	<td><input type=submit name=submit value=submit></td>
	  </tr>
	  <tr>
	  <td colspan=2>Add the following on the line below RewriteBase / in your .htaccess file:
	  </td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
	  <tr>
	  	<td colspan=2>
				RewriteRule <strong>".($options['ComparePress-htaccessPrefix-games'] != "" ? $options['ComparePress-htaccessPrefix-games'] : "compare-game-deals")."</strong>/(.*)/(.*)/(.*)/(.*)/ ?platform=$1&name=$2&sort=$3&page=$4<br>
                RewriteRule <strong>".($options['ComparePress-htaccessPrefix-games'] != "" ? $options['ComparePress-htaccessPrefix-games'] : "compare-game-deals")."</strong>/(.*)/$ ?id=$1
<br /></td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
          <tr>
	  	<td colspan=2><strong>IMPORTANT!</strong> ALWAYS backup important files like your .htaccess file before editing them.</td>
	  </tr>
	  </TABLE>
	  </FORM>";
?>