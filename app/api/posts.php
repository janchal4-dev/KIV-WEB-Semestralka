<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/UserModel.php";

header("Content-Type: application/json");
session_start();

// Musí být přihlášen
if (empty($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(["error" => "Nepřihlášený uživatel"]);
    exit;
}

$user = $_SESSION["user"];

// Jen Admin (2) a SuperAdmin (1)
if ($user["roles_id"] > 2) {
    http_response_code(403);
    echo json_encode(["error" => "Nemáte oprávnění"]);
    exit;
}

$postModel = new PostModel();
$userModel = new UserModel();

// URL parsing
$uri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
// ... /api/posts/{id}
$id = $uri[ count($uri) - 1 ];


// -------------------------------
// GET /api/posts
// -------------------------------
if ($_SERVER["REQUEST_METHOD"] === "GET" && $id === "posts") {
    echo json_encode($postModel->getAllPostsWithStatus());
    exit;
}


// -------------------------------
// GET /api/posts/{id}
// -------------------------------
if ($_SERVER["REQUEST_METHOD"] === "GET" && is_numeric($id)) {
    $post = $postModel->getPostDetails($id);
    echo json_encode($post);
    exit;
}


// -------------------------------
// PATCH /api/posts/{id}  → změna statusu
// -------------------------------
if ($_SERVER["REQUEST_METHOD"] === "PATCH" && is_numeric($id)) {

    $input = json_decode(file_get_contents("php://input"), true);
    $newStatus = (int) ($input["status_id"] ?? 0);

    if (!$newStatus) {
        http_response_code(400);
        echo json_encode(["error" => "Neplatný status"]);
        exit;
    }

    $ok = $postModel->updateStatus($id, $newStatus);
    echo json_encode(["success" => $ok]);
    exit;
}


// -------------------------------
// POST /api/posts/{id}/assign
// -------------------------------
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && count($uri) >= 4
    && $uri[count($uri) - 2] === "assign"
) {
    $postId = $uri[count($uri) - 3];
    $input = json_decode(file_get_contents("php://input"), true);
    $reviewerId = (int)$input["reviewer_id"];

    if ($reviewerId <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Neplatné ID recenzenta"]);
        exit;
    }

    $ok = $postModel->assignReviewer($postId, $reviewerId);
    echo json_encode(["success" => $ok]);
    exit;
}


// -------------------------------
// DELETE /api/posts/{id}/assign/{uid}
// -------------------------------
if (
    $_SERVER["REQUEST_METHOD"] === "DELETE"
    && count($uri) >= 5
    && $uri[count($uri)-3] === "assign"
) {
    $postId = $uri[count($uri)-4];
    $uid = $uri[count($uri)-1];

    $ok = $postModel->removeReviewer($postId, $uid);
    echo json_encode(["success" => $ok]);
    exit;
}


// -------------------------------
// Nepodporovaná metoda
// -------------------------------
http_response_code(405);
echo json_encode(["error" => "Nepodporovaná metoda"]);
