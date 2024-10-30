<?php
/**
 * The main affiliate dashboard. Affiliates enter their ComparePress access details here.
 */
ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
$options = get_option("widget_comparePressAdmin");

if (isset($_POST['submit1']) != "") {
    $options['m4e_token'] = htmlspecialchars($_POST['m4e-Token']);
    update_option("widget_comparePressAdmin", $options);
    $options['m4e_affiliateid'] = htmlspecialchars($_POST['m4e-AffiliateID']);
    update_option("widget_comparePressAdmin", $options);
}

if (($options['m4e_token'] != "") && ($options['m4e_affiliateid'] != "")) {
    // validate
    require_once(CP_BASE_DIR . '/lib/nusoap.php');
    $client = new soapclientNusoap("http://comparepress.com/soap/mobiles/mobilesNewDB.php");

    try {
        $senddata = array(array('token' => $options['m4e_token'], "affiliateid" => $options['m4e_affiliateid']));
        $result = $client->call("validateSignup", $senddata);

        if ($result["result"]["output"] == "validated") {
            $validate = "<strong>Validated</strong>";
            $extrahtml = "<tr>
			 <td><h3>Step 4: You`re validated! <br />Get comparing...</h3></td>
			 <td>Click on Content in the ComparePress section of the dashboard (bottom left) and configure your home page.
                         You can now also start adding ComparePress widgets by going here to <A HREF='../../wp-admin/themes.php'>Appearance</A> >
                         <A HREF='../../wp-admin/widgets.php'>Widgets</A></td>
			 </tr>";
            $greetinghtml = "Welcome back to ComparePress. Please visit the <a href='http://comparepress.com/' target='_blank'>ComparePress website</a>
                             for further information.";
            $step1 = "Completed";
        } else {
            $validate = $result["result"]["output"];
            $greetinghtml = "To get started earning commission on your blog you must first <a href='http://comparepress.com/sign-up/' target='_blank'>become a member of the ComparePress affiliate system</a>.
                Once you are signed up we will manually review your site and then send you a token to enable your ComparePress Widgets Plugin.";
            $step1 = "<a href='http://comparepress.com' target='_BLANK'>Click Here</a> to sign up today";
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }
}

echo "<table width=100%>
	  <tr>
	  	<td colspan=2><h1>ComparePress Main Admin Area</h1></td>
	  </tr>
	  <tr>
	  	<td colspan=2>$greetinghtml</td>
	  </tr>
	   <tr>
	  	<td><h3>Current ComparePress Affiliate Status</h3></td>
		<td><i>" . $validate . "</i></td>
	  </tr>
	  <tr>
	  	<td width=30%><h3>Step 1: Become a ComparePress Partner</h3></td>
		<td>$step1</td>
	  </tr>
	  <form action=\"\" name=form1 method=post>
	  <tr>
	  	<td width=30%><h3>Step 2: Enter your ComparePress Token</h3></td>
		<td><input type=text name=m4e-Token value=\"" . $options['m4e_token'] . "\"></td>
	  </tr>
	  <tr>
	  	<td><h3>Step 3: Enter your ComparePress ID</h3></td>
		<td><input type=text name=m4e-AffiliateID value=\"" . $options['m4e_affiliateid'] . "\"></td>
	  </tr>
	  <tr>
	  	<td></td>
	  	<td><input type=submit name=submit1 value=submit></td>
	  </tr>
	  $extrahtml
	  </form>
	  </table>";

echo "<h2>ComparePress News</h2>
    <p>Get the latest ComparePress news here: <a href=\"http://comparepress.com/blog/\">ComparePress News</a></p>
";
echo "<h2>News from your modules:</h2><p>Check out the private area on the left hand sidebard of the
    <a href=\"http://comparepress.com/blog/\">ComparePress news area</a> where you will have access to module specific news. <br/><strong>NOTE: You will need to be logged into <a href=\"http://comparepress.com/wp-login.php\">ComparePress.com</a> to see these private links.</strong></p>";
echo "<h2>Neeed Help?</h2>
    <p>Check out our Knowledgebase and if that doesn't help submit a support ticket here: <a href=\"http://comparepress.com/support/\">ComparePress Help</a></p>
";
?>