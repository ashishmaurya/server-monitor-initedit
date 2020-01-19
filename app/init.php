<?php

/**
 * Created by PhpStorm.
 * User: home
 * Date: 2/5/2016
 * Time: 1:10 PM
 */
require_once "tools/default/CookieManagment.php";
require_once "tools/default/SessionManagement.php";
require_once "tools/default/Variable.php";
require_once "tools/default/Setting.php";
require_once "tools/default/function.php";
require_once "tools/default/db.function.php";
require_once "tools/default/meta.function.php";
require_once "tools/default/Ping.php";

require_once "tools/default/TimeHelper.php";
require_once "tools/default/Database.php";
require_once "tools/default/gfc_helper.php";
require_once "tools/default/monitoring.php";
require_once "core/App.php";
require_once "core/AdminApp.php";
require_once "core/ApiApp.php";
require_once "core/Controller.php";
require_once "core/AdminController.php";
require_once "core/ApiController.php";

if (DEBUG) {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}


global $dbObject;
$dbObject = new Database();
