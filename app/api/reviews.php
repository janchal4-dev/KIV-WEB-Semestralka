<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../models/ReviewModel.php";
require_once __DIR__ . "/../../vendor/autoload.php"; // HTMLPurifier

header("Content-Type: application/json");
session_start();

if (empty($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(["error" => "Musíte být přihlášen."]);
    exit;
}

$user = $_SESSION["user"];
$method = $_SERVER["REQUEST_METHOD"];

// zjištení akce z urlka
$uri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
$action = $uri[array_key_last($uri)];

$model = new ReviewModel();


// recenzi píše recenzent
if ($action === "reviews.php" && $method === "POST") {

    if ($user["roles_id"] != 3) {
        http_response_code(403);
        echo json_encode(["error" => "Nemáte oprávnění psát recenze."]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $postId = $data["post_id"] ?? null;

    $q = (int)($data["rev_quality"] ?? 0);
    $l = (int)($data["rev_language"] ?? 0);
    $o = (int)($data["rev_originality"] ?? 0);

    // komentář – prošel přes Purifier
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $commentClean = $purifier->purify($data["comment"] ?? "");

    // uloží nebo odmítne
    $ok = $model->createOrUpdateReview(
        $postId,
        $user["id_user"],
        $q,
        $l,
        $o,
        $commentClean
    );

    // pokud funkce v modelu vrátila false → důvod: recenze je SCHVÁLENÁ
    if (!$ok) {
        echo json_encode([
            "success" => false,
            "error"   => "Tato recenze už byla schválena a nelze ji upravit."
        ]);
        exit;
    }

    echo json_encode(["success" => true]);
    exit;
}



// recenze schvalována / zamítána adminem
if ($action === "status" && $method === "POST") {

    if ($user["roles_id"] > 2) {
        http_response_code(403);
        echo json_encode(["error" => "Pouze admin může měnit stav recenze."]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $reviewId = intval($data["review_id"] ?? 0);
    $published = intval($data["published"] ?? 0); // boolean 0 nebo 1

    if (!$reviewId) {
        http_response_code(400);
        echo json_encode(["error" => "Missing review_id"]);
        exit;
    }

    $ok = $model->updateReviewStatus($reviewId, $published);
    echo json_encode(["success" => $ok]);
    exit;
}


//  jinak nic nespustí a vyvolá error
http_response_code(404);
echo json_encode(["error" => "Neznámá akce."]);
