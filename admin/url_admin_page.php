<?php
/*
  ComparePress Module Admin
  Version: 2.0.1
 */
$options = get_option("widget_comparePressAdmin");

// Deprecated
// define("CP_BASE_DIR", WP_PLUGIN_DIR.'/'.plugin_basename(dirname(__FILE__)));
// 
//if ($_POST['submit'] != "") {
//	$options['m4e_hpactivate'] = htmlspecialchars($_POST['m4e-hpActivate']);
//	$options['m4e_bestsellers'] = htmlspecialchars($_POST['m4e-bestSellers']);
//	$options['m4e_latest'] = htmlspecialchars($_POST['m4e-latest']);
//	$options['m4e_gifts'] = htmlspecialchars($_POST['m4e-gifts']);	
//	update_option("widget_comparePressAdmin", $options);
//}

require_once(CP_BASE_DIR.'/lib/nusoap.php');
$client = new soapclientNusoap("http://comparepress.com/soap/moduleReg.php");
$options = array(array('module'=>($_GET["module"]<>"" ? $_GET["module"]:"none")));
$results = $client->call("getModuleInfo",$options);

include (CP_BASE_DIR."/modules/UK/".$_GET["module"]."/admin/".$_GET["module"]."_url.php");
?>