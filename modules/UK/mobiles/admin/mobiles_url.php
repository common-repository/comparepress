<?php

$options = get_option("widget_ComparePressurls");

$submit = isset($_POST['submit']);
if ($submit != "") {

    $options['m4e_simpleurl'] = htmlspecialchars($_POST['m4e-simpleurl']);
    $options['m4e_searchurl'] = htmlspecialchars($_POST['m4e_simpleurl']);

    # New option to use htaccess rules
    $options['m4e-htaccessurl'] = htmlspecialchars($_POST['m4e-htaccessurl']);
    $options['ComparePress-htaccessPrefix'] = htmlspecialchars($_POST['ComparePress-htaccessPrefix']);

    # New option to change deal page titles
    $options['m4e-deals-titles'] = htmlspecialchars($_POST['m4e-deals-titles']);

    update_option("widget_ComparePressurls", $options);
}

echo "<form action=\"\" name=form1 method=post>
		<table width=100%>
          <tr>
	  	<td colspan=2><h1>Deal Pages Titles</h1></td>
	  </tr>
	   <tr>
	  	<td colspan=2>The setting below allows you to add additional keywords to the end of the page titles on the handset results pages. 
                The default is Handset Name e.g. Samsung Galaxy S III white. If you add the word Pricss below your page title will have this added at the end e.g. Samsung Galaxy S III white Prices</td>
	  </tr>
	  <tr>
	  	<td>Deals Pages Titles</td>
		<td><input type=text name=m4e-deals-titles value=\"" . $options['m4e-deals-titles'] . "\"></td>
	  </tr>
	  <tr>
	  	<td><input type=submit name=submit value=submit></td>
	  </tr>          
          <tr>
	  	<td colspan=2><h1>URL Settings</h1></td>
	  </tr>
	  <tr>
	  	<td colspan=2><strong>Warning:</strong> The Simple Mobile Deaks url (slug) below needs to be unique on your blog</td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
	   <tr>
	  	<td colspan=2>The setting below changes the urls of your mobile phone comparison search results. You can change this from it's 
                default (www.mysite.com/<strong>compare-mobile-deals</strong>/?phone=Sony-Ericsson-Satio-black) value in order to make your site 
                search urls unique.</td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
          <tr>
	  	<td>Unique deals URL Prefix</td>";


if ($options['ComparePress-htaccessPrefix'] == "") {
    $URLusing = $options['m4e_simpleurl'];
} else {
    $URLusing = "";
}

echo "<td><input type=text name=m4e-simpleurl value=\"" . $URLusing . "\"></td>
	  </tr>
	  <tr>
	  	<td><input type=submit name=submit value=submit></td>
	  </tr>
	    <tr>
	  	<td colspan=2><h2>Want even prettier URLS?</h2></td>
	  </tr>
	  <tr>
	  <td colspan=2>If you know what your <strong>.htaccess</strong> file is then you can customise the mobile phone deals output even more. 
          The details of how to do this are outlined below but please do not venture further unless you are 100% comfortable with your .htacess!
	  </td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
	  <tr>
	  	<td>Want to use .htacess rules instead?<br />Type YES</td>
		<td><input type=text name=m4e-htaccessurl value=\"" . $options['m4e-htaccessurl'] . "\"></td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
		<tr>
	  	<td>Alternative .htaccess deals URL Prefix</td>
		<td><input type=text name=ComparePress-htaccessPrefix value=\"" . $options['ComparePress-htaccessPrefix'] . "\"></td>
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
                RewriteRule <strong>" . ($options['ComparePress-htaccessPrefix'] != "" ? $options['ComparePress-htaccessPrefix'] : "compare-mobile-deals") . "</strong>/free/(.*)/ ?gift=$1<br/>                
                RewriteRule <strong>" . ($options['ComparePress-htaccessPrefix'] != "" ? $options['ComparePress-htaccessPrefix'] : "compare-mobile-deals") . "</strong>/search/(.*)/(.*)/(.*)/(.*)/(.*)/(.*)/(.*)/(.*)/(.*)/ ?phone=$1&manufacturer=$2&network=$3&contract_type=$4&minutes=$5&texts=$6&data=$7&line_rental=$8&sort=$9<br/>" .
 "RewriteRule <strong>" . ($options['ComparePress-htaccessPrefix'] != "" ? $options['ComparePress-htaccessPrefix'] : "compare-mobile-deals") . "</strong>/(.*)$ ?phone=$1
<br /></td>
	  </tr>
	  <tr>
	  	<td width=20%>&nbsp;</td>
	  </tr>
          <tr>
	  	<td colspan=2><strong>IMPORTANT!</strong> ALWAYS backup important files like your .htaccess file before editing them.</td>
	  </tr>
	  </table>
	  </form>";
?>