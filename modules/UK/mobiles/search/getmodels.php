<?php
// this simply gets the models based on the suplied manufacturer
// and is used for the search widget
// error_reporting(E_ALL ^ E_NOTICE);
/* 'Fix' for PHP time related warnings on SOME hosts with php 5.3 */
if(!ini_get('date.timezone'))
{
    date_default_timezone_set('Europe/London');
}
$string = date("H:i:s") . " Manufacturer=" . $_GET['manufacturer'] . ", Type=" . $_GET['type'];
$output = "";
$send = array(array("manufacturer" => $_GET['manufacturer'], "type" => $_GET['type'], "number" => "99"));

require_once('../../../../lib/nusoap.php');
$client = new soapclientNusoap("http://comparepress.com/soap/mobiles/uk-standard-mobiles.php");

try {
    $results = $client->call("getHandsetsByManufacturer", $send);
    #$results = $client->getHandsetsByManufacturer($send);
    foreach ($results["results"] as $key => $val) {
        $output .= "<option value=\"" . str_replace(" ", "-", $val["handset_name"]) . "/\">" . ucwords(str_replace(str_replace("-", " ", $_GET['manufacturer']), "", strtolower($val["handset_name"]))) . "</option>";
    }
} catch (SoapFault $exception) {
    echo $exception;
}
echo "document.getElementById('CP_mobiles_select_model').innerHTML = '<select name=\"phone\"><option value=\"-\">Select</option>" . $output . "</select>';";
?>