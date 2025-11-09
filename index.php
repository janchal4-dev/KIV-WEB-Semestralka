<?php
require_once __DIR__ . "/app/config/config.php";
require_once APP_PATH . "/MyApplication.php";

$app = new MyApplication();
$app->run();
