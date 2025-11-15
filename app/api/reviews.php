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

// ========================
// 🔵 ZJIŠTĚNÍ AKCE Z URL
// např. /api/reviews.php/status
// ========================
$uri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
$action = $uri[array_key_last($uri)];

$model = new ReviewModel();


// =======================================
// 1️⃣ RECENZENT PÍŠE RECENZI (POST)
// =======================================
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
    $commentRaw = $data["comment"] ?? "";

    // 💡 HTMLPurifier
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $commentClean = $purifier->purify($commentRaw);

    $ok = $model->createReview($postId, $user["id_user"], $q, $l, $o, $commentClean);

    echo json_encode(["success" => $ok]);
    exit;
}


// =======================================
// 2️⃣ ADMIN SCHVALUJE / ZAMÍTÁ RECENZI
// /api/reviews.php/status
// =======================================
if ($action === "status" && $method === "POST") {

    if ($user["roles_id"] > 2) {
        http_response_code(403);
        echo json_encode(["error" => "Pouze admin může měnit stav recenze."]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $reviewId = intval($data["review_id"] ?? 0);
    $published = intval($data["published"] ?? 0);

    if (!$reviewId) {
        http_response_code(400);
        echo json_encode(["error" => "Missing review_id"]);
        exit;
    }

    $ok = $model->updateReviewStatus($reviewId, $published);
    echo json_encode(["success" => $ok]);
    exit;
}


// =======================================
// ❌ NIC NESPOUŠTÍ → 404
// =======================================
http_response_code(404);
echo json_encode(["error" => "Neznámá akce."]);
