<?php

// absolutní cesta ke složce /app
define("APP_PATH", realpath(__DIR__ . "/.."));

// složky v rámci /app
define("CONTROLLER_PATH", APP_PATH . "/controllers");
define("VIEW_PATH", APP_PATH . "/views");
define("MODEL_PATH", APP_PATH . "/models");

define("HEADER_FILE", VIEW_PATH . "/layout/header.php");
define("FOOTER_FILE", VIEW_PATH . "/layout/footer.php");

define("TWIG_TEMPLATE_PATH", APP_PATH . "/views/twig");
