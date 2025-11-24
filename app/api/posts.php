<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../models/PostModel.php";

header("Content-Type: application/json");
session_start();

// kontrola přihlášení
if (!isset($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(["error" => "Nejste přihlášen."]);
    exit;
}
// uložení dat usera
$user = $_SESSION["user"];

// povoleno jen adminům a superadminům
if ($user["roles_id"] > 2) {
    http_response_code(403);
    echo json_encode(["error" => "Nemáte oprávnění."]);
    exit;
}

// zjištení akce z urlka
$method = $_SERVER["REQUEST_METHOD"];
$uri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
//$action = $uri[array_key_last($uri)];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode("/", trim($path, "/"));

// poslední kousek url dělá akci
$action = end($parts);

// komunikace s DB
$model = new PostModel();

// změna statusu
if ($action === "status" && $method === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $postId = intval($data["post_id"] ?? 0);
    $status = intval($data["status"] ?? 0);

    if (!$postId || !$status) {
        http_response_code(400);
        echo json_encode(["error" => "Chybí data."]);
        exit;
    }

    $ok = $model->updateStatus($postId, $status);

    echo json_encode(["success" => $ok]);
    exit;
}


// přidání recenzenta k článku
if ($action === "assign" && $method === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $postId = intval($data["post_id"] ?? 0);
    $reviewerId = intval($data["reviewer_id"] ?? 0);

    if (!$postId || !$reviewerId) {
        http_response_code(400);
        echo json_encode(["error" => "Chybí data."]);
        exit;
    }

    $db = getDB();
    $sql = $db->prepare("
        INSERT IGNORE INTO post_reviewer (post_id, reviewer_id)
        VALUES (?, ?)
    ");

    $sql->execute([$postId, $reviewerId]);

    echo json_encode(["success" => true]);
    exit;
}


// odebrání recenzenta
if ($action === "unassign" && $method === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $postId = intval($data["post_id"] ?? 0);
    $reviewerId = intval($data["reviewer_id"] ?? 0);

    if (!$postId || !$reviewerId) {
        http_response_code(400);
        echo json_encode(["error" => "Chybí data."]);
        exit;
    }

    $sql = getDB()->prepare("
        DELETE FROM post_reviewer
        WHERE post_id = ? AND reviewer_id = ?
    ");

    $ok = $sql->execute([$postId, $reviewerId]);

    echo json_encode(["success" => $ok]);
    exit;
}



//  jinak nic nespustí a vyvolá error
http_response_code(404);
echo json_encode(["error" => "Neznámá akce."]);
