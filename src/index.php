<?php
header("Content-Type: application/json");

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/config/Auth.php';
require_once __DIR__ . '/models/OptionModel.php';

$db = (new Database())->getConnection();
$auth = new Auth($db);
$model = new OptionModel($db);

$headers = getallheaders();
$apiKey = $headers['X-API-Key'] ?? '';

if (!$auth->checkApiKey($apiKey)) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$id = $requestUri[2] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            $data = $model->getOne($id);
            echo json_encode($data ?: ["error" => "Not found"]);
        } else {
            echo json_encode($model->getAll());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if ($model->create($data)) {
            http_response_code(201);
            echo json_encode(["message" => "Created"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Failed to create"]);
        }
        break;

    case 'PUT':
        if (!$id) { http_response_code(400); echo json_encode(["error" => "Missing ID"]); break; }
        $data = json_decode(file_get_contents("php://input"), true);
        if ($model->update($id, $data)) {
            echo json_encode(["message" => "Updated"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Update failed"]);
        }
        break;

    case 'DELETE':
        if (!$id) { http_response_code(400); echo json_encode(["error" => "Missing ID"]); break; }
        if ($model->delete($id)) {
            echo json_encode(["message" => "Deleted"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Delete failed"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}