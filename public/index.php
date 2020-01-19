<?php
if(!file_exists("../app/tools/default/Variable.php")){
  header("Location: /install");
}
require_once "../app/init.php";

define('TIMEZONE', 'Asia/Kolkata');
date_default_timezone_set(TIMEZONE);

$now = new DateTime();
$mins = $now->getOffset() / 60;
$sgn = ($mins < 0 ? -1 : 1);
$mins = abs($mins);
$hrs = floor($mins / 60);
$mins -= $hrs * 60;
$offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);

$database = new Database();
$database->query("SET time_zone='$offset';");
$database->execute();

$app = new App;
