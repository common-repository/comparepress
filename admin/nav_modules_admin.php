<?php
/*
  ComparePress: Module Adminstration area - Module Options
  Version: 2.0.1
  Description: Allows users to change settings for the modules they have access to
 */
$options = get_option("widget_ComparePressModules");
$cp_options = get_option("widget_comparePressAdmin");
if (isset($_GET["subpage2"])) {
    $subpage2 = $_GET["subpage2"];
} else {
    $subpage2 = 'content';
}
if (isset($_GET["module"])) {
    $module = $_GET["module"];
} else {
    $module = 'none';
}
require_once(CP_BASE_DIR . '/lib/nusoap.php');
$client = new soapclientNusoap("http://comparepress.com/soap/moduleReg.php");
?>
<script type="text/javascript">
    function goThere(loc)
    {
        window.location.href=loc;
    }
</script>
<select style="font-weight: bold;background-color: #ddd; color: #21759B;font-size:12px;float:right; margin: 13px 25px 0px 0px; height: 29px;" onchange="goThere('options-general.php?page=comparepress/ComparePress.php&subpage=modules_settings&subpage2=<?php echo $subpage2 ?>&module=' + this.options[this.selectedIndex].value)">
    <option value="none" <?php ($module == 'none' ? 'selected' : '') ?>>Please select a module</option>
    <?php
    try {
        $results = $client->call("getAllModules");
        foreach ($results["results"] as $key => $val) {
            $option_name = "ComparePress_module_" . $val['module_id'];
            $checkdetails = array(array('affiliate_id' => $cp_options['m4e_affiliateid'], 'module_id' => $val['module_id']));
            $module_check = $client->call("checkModuleAllowed", $checkdetails);
            echo ($module_check != false ? "<option value='$val[module_id]' " . ($module == $val['module_id'] ? 'selected' : '') . ">Options for " . $val[module_location] . " " . $val[module_name] . " Module</option>" : "");
        }
    } catch (SoapFault $exception) {
        echo $exception;
    }
    ?></select>
<div class="cp-adminnav">
    <ul>
        <li <?php echo ($subpage2 == "content" ? "class='selected'>" : "><a href='options-general.php?page=comparepress/ComparePress.php&subpage=modules_settings&subpage2=content" . ($module <> 'none' ? "&module=" . $module : "") . "'>") ?>Shortcodes<?php echo ($subpage2 == "content" ? "" : "</a>") ?></li>
        <li <?php echo ($subpage2 == "desc" ? "class='selected'>" : "><a href='options-general.php?page=comparepress/ComparePress.php&subpage=modules_settings&subpage2=desc" . ($module <> 'none' ? "&module=" . $module : "") . "'>") ?>Custom Descriptions<?php echo ($subpage2 == "desc" ? "" : "</a>") ?></li>
        <li <?php echo ($subpage2 == "results" ? "class='selected'>" : "><a href='options-general.php?page=comparepress/ComparePress.php&subpage=modules_settings&subpage2=results" . ($module <> 'none' ? "&module=" . $module : "") . "'>") ?>Comparison Results Editor<?php echo ($subpage2 == "results" ? "" : "</a>") ?></li>
        <li <?php echo ($subpage2 == "urls" ? "class='selected'>" : "><a href='options-general.php?page=comparepress/ComparePress.php&subpage=modules_settings&subpage2=urls" . ($module <> 'none' ? "&module=" . $module : "") . "'>") ?>Permalink / URL Settings<?php echo ($subpage2 == "urls" ? "" : "</a>") ?></li>
    </ul>
</div>
<div class="cp-adminpage">
    <?php
    if ($module <> 'none') {
        switch ($subpage2) {
            case "content":
                include "content_admin_page.php";
                break;
            case "desc":
                include "description_content_admin_page.php";
                break;
            case "results":
                include "results_admin_page.php";
                break;
            case "urls":
                include "url_admin_page.php";
                break;
        }
    } else {
        echo "<h1 style='padding-top: 10px;'>Please select a module from the drop down box above to set Module Options</h1>";
    }
    ?>
</div>