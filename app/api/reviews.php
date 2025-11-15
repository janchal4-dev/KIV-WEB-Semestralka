<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../models/ReviewModel.php";
//require_once __DIR__ . "/../vendor/autoload.php"; // pro HTMLPurifier
require_once __DIR__ . "/../../vendor/autoload.php";

header("Content-Type: application/json");

session_start();

// ✅ Kontrola přihlášení
if (empty($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(["error" => "Musíte být přihlášen."]);
    exit;
}

// ✅ Kontrola role – pouze recenzent (role_id = 3)
$user = $_SESSION["user"];
if ($user["roles_id"] != 3) {
    http_response_code(403);
    echo json_encode(["error" => "Nemáte oprávnění psát recenze."]);
    exit;
}

// ✅ Pouze POST metoda
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Nepodporovaná metoda."]);
    exit;
}

// ✅ Načti a zpracuj data
$data = json_decode(file_get_contents("php://input"), true);

$postId = $data["post_id"] ?? null;
$revQuality = (int)($data["rev_quality"] ?? 0);
$revLanguage = (int)($data["rev_language"] ?? 0);
$revOriginality = (int)($data["rev_originality"] ?? 0);
$commentRaw = $data["comment"] ?? "";

// ✅ Očisti HTML – ochrana před XSS
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$commentClean = $purifier->purify($commentRaw);

// ✅ Ulož recenzi
$model = new ReviewModel();
$ok = $model->createReview($postId, $user["id_user"], $revQuality, $revLanguage, $revOriginality, $commentClean);

if ($ok) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Nepodařilo se uložit recenzi."]);
}
