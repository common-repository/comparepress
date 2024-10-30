<?php
// ----------------------------------
// ENQUEUE SCRIPTS
// ----------------------------------
// Callback function for hook: 'init' (needs to be init for enqueue to work properly)
function admin_register_head() {
    if (is_admin()) { // only add the css to admin pages
        wp_enqueue_style('CP_admin_css', CP_BASE_URL . '/css/admin.css');
    }
}
// ----------------------------------------------------------------------------
//  END - Scripts to enqueue for admin pages
// ----------------------------------------------------------------------------

// ----------------------------------------------------------------------------
//  START - Scripts to enqueue for non-admin pages
// ----------------------------------------------------------------------------
// Function to add CSS, js etc to header (modularised)
// Callback function for hook: 'init' (needs to be init for enqueue to work properly)
function insert_into_head() {
//  Call the active module's function to add CSS, js etc to HTML header
    $client = new soapclientNusoap("http://comparepress.com/soap/moduleReg.php");
    try {
        $results = $client->call("getAllModules");
        foreach ($results["results"] as $key => $val) {
            $widget_options = get_option("widget_ComparePressModules");
            $option_name = "ComparePress_module_" . $val['module_id'];
            if ($widget_options[$option_name] == 'y') {
                $headerproc = "head_" . $val['module_id'];
                $headerproc();
            }
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }
}
// ----------------------------------------------------------------------------
//  END - Scripts to enqueue for non-admin pages
// ----------------------------------------------------------------------------
?>