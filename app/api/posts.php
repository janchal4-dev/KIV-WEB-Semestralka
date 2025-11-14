<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/UserModel.php";

header("Content-Type: application/json");

session_start();

if (empty($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(["error" => "Nejste přihlášen."]);
    exit;
}

$user = $_SESSION["user"];

// jen admini / superadmini
if ($user["roles_id"] > 2) {
    http_response_code(403);
    echo json_encode(["error" => "Nemáte oprávnění."]);
    exit;
}

$model = new PostModel();

// zjistit poslední úsek URL
$uri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
$action = $uri[count($uri) - 1];

switch ($action) {

    // -----------------------------------------
    // 🔵 ZMĚNA STATUSU (schválit / zamítnout)
    // POST /api/posts/status
    // -----------------------------------------
    case "status":

        $data = json_decode(file_get_contents("php://input"), true);
        $postId = intval($data["post_id"]);
        $newStatus = intval($data["status"]);

        if (!$postId || !$newStatus) {
            http_response_code(400);
            echo json_encode(["error" => "Chybí data."]);
            exit;
        }

        $ok = $model->updateStatus($postId, $newStatus);

        echo json_encode([
            "success" => $ok,
            "message" => $ok ? "Status změněn." : "Nepodařilo se změnit status."
        ]);
        break;



    // -----------------------------------------
    // 🟢 PŘIŘAZENÍ RECENZENTA
    // POST /api/posts/assign
    // -----------------------------------------
    case "assign":

        $data = json_decode(file_get_contents("php://input"), true);
        $postId = intval($data["post_id"]);
        $reviewerId = intval($data["reviewer_id"]);

        if (!$postId || !$reviewerId) {
            http_response_code(400);
            echo json_encode(["error" => "Chybí data."]);
            exit;
        }

        $sql = getDB()->prepare("
            INSERT IGNORE INTO post_reviewer (post_id, reviewer_id)
            VALUES (?, ?)
        ");
        $ok = $sql->execute([$postId, $reviewerId]);

        echo json_encode([
            "success" => true,
            "message" => "Recenzent přiřazen."
        ]);
        break;


    default:
        http_response_code(404);
        echo json_encode(["error" => "Neznámá akce."]);
        break;
}
