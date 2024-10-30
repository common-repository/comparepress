<?php
/*
  ComparePress: Available Modules - Modules
  Version: 2.0.1
  Description: Allows users to choose which of the available ComparePress modules they want to use
 */
$geo_options = get_option("widget_geo_options");
$options = get_option("widget_ComparePressModules");
$cp_options = get_option("widget_comparePressAdmin");
global $wpdb;
// Loop for retrieving ComparePress module details via SOAP
require_once(CP_BASE_DIR . '/lib/nusoap.php');
$client = new soapclientNusoap("http://comparepress.com/soap/moduleReg.php");

if (isset($_POST['submit']) != "") {
    try {
        $results = $client->call("getAllModules");
        foreach ($results["results"] as $key => $val) {
            $option_name = "ComparePress_module_" . $val['module_id'];
            // $options[$option_name] = htmlspecialchars($_POST[$val['module_id']]);
            if ($_POST['modulechoice'] == $val['module_id'])
                $options[$option_name] = "y";
            else
                $options[$option_name] = "";
        }
        // for user IP address filtering if affiliate wants this but removed currently
        $geo_options['chk_geoip_UK'] = isset($_POST['chk_geoip_UK']);
        $geo_options['chk_geoip_shortcodes_UK'] = isset($_POST['chk_geoip_shortcodes_UK']);
        update_option("widget_geo_options", $geo_options);
        update_option("widget_ComparePressModules", $options);
    } catch (SoapFault $exception) {
        echo $exception;
    }
}
?>
<h1>Available Modules</h1>
<form action="" name=form1 method="post">
    <fieldset class="ComparePressFieldSet">
        <legend>UK:</legend>
        <table width="100%">
            <?php
            try {
                $results = $client->call("getAllModules");
                foreach ($results["results"] as $key => $val) {
                    $option_name = "ComparePress_module_" . $val['module_id'];
                    $checkdetails = array(array('affiliate_id' => $cp_options['m4e_affiliateid'], 'module_id' => $val['module_id']));
                    $module_check = $client->call("checkModuleAllowed", $checkdetails);
                    echo "<tr><td width=1%><img src='" . CP_BASE_URL . '/modules/UK/' . $val['module_id'] . "/" . $val['module_id'] .
                    ".png'></td><td><input type='radio' name='modulechoice' value='" . $val['module_id'] . "' " .
                    ($options[$option_name] == "y" ? " checked=checked" : "") . ($module_check == false ? "DISABLED" : "") .
                    "></td><td width=29% nowrap><b>$val[module_name]</b></td><td>" . html_entity_decode($val['module_description']) . "</td><td>" .
                    ($module_check == false ? "<a href='http://comparepress.com/support/index.php?a=add'>Click here to add the " . $val['module_name'] . " module</a>" : "")
                    . "</td></tr>";
                }
            } catch (SoapFault $exception) {
                echo $exception;
            }
            ?>      
            <?php
            echo"
	</table>
	<input type=submit name=submit value='Update Module Selection'>
        </fieldset>

        <fieldset class=\"ComparePressFieldSet\">
        <legend>USA, Germany or?</legend>

	<table width=\"100%\"><tbody><tr><td width=\"1%\">
        <img src=\"" . CP_BASE_URL . "/modules/UK/mobiles/mobiles.png\"></td>
        <td><input value=\"y\" name=\"pcs\" disabled=\"disabled\" type=\"checkbox\"></td><td nowrap=\"nowrap\" width=\"29%\">
        <b>Your web site's products?</b></td>
        <td>Want to add YOUR Comparison service to ComparePress?</td>
        <td><a href=\"http://comparepress.com/contact/\">Click here to get in touch with us about creating a new module for ComparePress and getting ComparePress affiliates promoting your services</a></td></tr>
	</tbody></table>
        </fieldset>
        </form>";
            ?>