<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../models/UserModel.php";

header("Content-Type: application/json");

session_start();
if (empty($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(["error" => "Nejste přihlášen."]);
    exit;
}

$user = $_SESSION["user"];
$model = new UserModel();

// URL parsing (např. /api/users/5 → ["", "api", "users", "5"])
$uri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
$id = $uri[count($uri) - 1] ?? null;

switch ($_SERVER["REQUEST_METHOD"]) {

    // 🟢 GET /api/users
    case "GET":
        if ($user["roles_id"] > 2) {
            http_response_code(403);
            echo json_encode(["error" => "Nemáte oprávnění."]);
            exit;
        }

        echo json_encode($model->getAllUsers());
        break;

    // 🟡 PUT /api/users/{id}  → změna role
    case "PUT":
        if (!isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID uživatele chybí."]);
            exit;
        }

        // Načti data z JSONu (např. { "roles_id": 3 })
        $input = json_decode(file_get_contents("php://input"), true);
        $newRoleId = isset($input["roles_id"]) ? (int)$input["roles_id"] : 0;

        if ($newRoleId <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "Neplatná hodnota role."]);
            exit;
        }

        // Načti cílového uživatele
        $target = $model->getUserById($id);
        if (!$target) {
            http_response_code(404);
            echo json_encode(["error" => "Uživatel nenalezen."]);
            exit;
        }

        // ⚠️ Bezpečnostní logika:
        // Admin (role_id=2) nesmí měnit SuperAdmina ani Admina
        if ($user["roles_id"] == 2 && $target["roles_id"] <= 2) {
            http_response_code(403);
            echo json_encode(["error" => "Nemůžete měnit roli vyššího uživatele."]);
            exit;
        }

        // SuperAdmin nesmí udělat z někoho dalšího SuperAdmina
        if ($user["roles_id"] == 1 && $newRoleId == 1) {
            http_response_code(403);
            echo json_encode(["error" => "Nelze udělit roli SuperAdmina."]);
            exit;
        }

        // ✅ Ulož změnu role
        $ok = $model->updateRole($id, $newRoleId);
        echo json_encode(["success" => $ok]);
        break;


    // DELETE /api/users/{id}  → blokace
    case "DELETE":
        if (!isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID uživatele chybí."]);
            exit;
        }

        // admin nesmí blokovat admina/superadmina
        $target = $model->getUserById($id);
        if (!$target) {
            http_response_code(404);
            echo json_encode(["error" => "Uživatel nenalezen."]);
            exit;
        }

        if ($user["roles_id"] == 2 && $target["roles_id"] <= 2) {
            http_response_code(403);
            echo json_encode(["error" => "Nemůžete blokovat vyššího uživatele."]);
            exit;
        }

        $ok = $model->blockUser($id);
        echo json_encode(["success" => $ok]);
        break;


    // PATCH /api/users/{id} → odblokování
    case "PATCH":
        if (!isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID uživatele chybí."]);
            exit;
        }

        $target = $model->getUserById($id);
        if (!$target) {
            http_response_code(404);
            echo json_encode(["error" => "Uživatel nenalezen."]);
            exit;
        }

        // bezpečnost – admin nesmí odblokovat vyšší roli
        if ($user["roles_id"] == 2 && $target["roles_id"] <= 2) {
            http_response_code(403);
            echo json_encode(["error" => "Nemůžete měnit stav vyššího uživatele."]);
            exit;
        }

        $ok = $model->unblockUser($id);
        echo json_encode(["success" => $ok]);
        break;


    default:
        http_response_code(405);
        echo json_encode(["error" => "Nepodporovaná metoda."]);
}
