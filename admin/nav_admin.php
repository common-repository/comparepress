<?php
//$subpage = ($_GET["subpage"]<>"" ? $_GET["subpage"]:"member");
if (isset($_GET["subpage"])) {
    $subpage = $_GET["subpage"];
} else {
    $subpage = 'member';
}
?>

<div class="cp-adminnav">
    <a style="background-image: url(<?php echo CP_BASE_URL; ?>/ComparePress-logo1.png); display: block; width: 252px; height: 60px;background-position: -45px -40px;" href="http://www.comparepress.com/"></a>
    <ul>
        <li <?php echo ($subpage == "member" ? "class='selected'>" : "><a href='options-general.php?page=comparepress/ComparePress.php&subpage=member'>") ?>Dashboard<?php echo ($subpage == "member" ? "" : "</a>") ?></li>
        <li <?php echo ($subpage == "modules" ? "class='selected'>" : "><a href='options-general.php?page=comparepress/ComparePress.php&subpage=modules'>") ?>Modules<?php echo ($subpage == "modules" ? "" : "</a>") ?></li>
        <li <?php echo ($subpage == "modules_settings" ? "class='selected'>" : "><a href='options-general.php?page=comparepress/ComparePress.php&subpage=modules_settings'>") ?>Module Options<?php echo ($subpage == "modules_settings" ? "" : "</a>") ?></li>
    </ul>
</div>

<div class="cp-adminpage">
    <?php
    switch ($subpage) {
        case "member":
            include "main_admin_page.php";
            break;
        case "modules":
            include "modules_admin_page.php";
            break;
        case "modules_settings":
            include "nav_modules_admin.php";
            break;
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
    ?>
</div>