<?php

session_start();
require_once 'app/Config.php';
require_once 'app/ConfigRoutes.php';
require_once 'system/core/Autoloader.php';
if (defined('PRODUCTIONMODE')) {
    if (PRODUCTIONMODE) {
        ini_set('display_errors', 'Off');
        error_reporting(E_ALL);
    } elseif (!PRODUCTIONMODE) {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
    } else {
        exit('configuration error');
    }
}
require_once 'system/core/Errorhandler.php';
//bootstrap
$b = new System\Core\Bootstrap();
$b->init();
