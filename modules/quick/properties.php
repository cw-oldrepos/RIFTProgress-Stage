<?php
    $ROOT           = dirname(dirname(dirname(__FILE__)));
    include_once    "{$ROOT}/configuration.php";

    $module = "quick";
    if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; }
?>