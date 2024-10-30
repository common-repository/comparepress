<?php
/*
  Description: Gets WordPress config path for AJAX
 * @todo : Work on tidying this up using the following:
 * http://codex.wordpress.org/AJAX_in_Plugins
 */

function fs_get_wp_config_path() {
    $base = dirname(__FILE__);
    $path = false;

    if (@file_exists(dirname(dirname($base)) . "/wp-config.php")) {
        $path = dirname(dirname($base)) . "/wp-config.php";
    } else
    if (@file_exists(dirname(dirname(dirname($base))) . "/wp-config.php")) {
        $path = dirname(dirname(dirname($base))) . "/wp-config.php";
    } else
    if (@file_exists(dirname(dirname(dirname(dirname($base)))) . "/wp-config.php")) {
        $path = dirname(dirname(dirname(dirname($base)))) . "/wp-config.php";
    } else
    if (@file_exists(dirname(dirname(dirname(dirname(dirname($base))))) . "/wp-config.php")) {
        $path = dirname(dirname(dirname(dirname(dirname($base))))) . "/wp-config.php";
    } else
    if (@file_exists(dirname(dirname(dirname(dirname(dirname(dirname($base)))))) . "/wp-config.php")) {
        $path = dirname(dirname(dirname(dirname(dirname(dirname($base)))))) . "/wp-config.php";
    } else
    if (@file_exists(dirname(dirname(dirname(dirname(dirname(dirname(dirname($base))))))) . "/wp-config.php")) {
        $path = dirname(dirname(dirname(dirname(dirname(dirname(dirname($base))))))) . "/wp-config.php";
    }
    else
        $path = false;

    if ($path != false) {
        $path = str_replace("\\", "/", $path);
    }
    return $path;
}
?>