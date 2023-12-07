<?php
header('Access-Controll-Allow-Origin:*');
include("connection.php");
require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

$headers = getallheaders();
if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit();
}

$authorizationHeader = $headers['Authorization'];
$token = null;

$token = trim(str_replace("Bearer", '', $authorizationHeader));
if (!$token) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit();
}
try {
    $key = "your_secret";
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    if ($decoded->user_type == 'seller') {
        $query = $mysqli->prepare('DELETE FROM products WHERE product_id = ?');
        $query->bind_param('i', $_POST['product_id']);
        $query->execute();
        $response = [];
        $response["status"] = "true";
    } else {
        $response = [];
        $response["permissions"] = false;
    }
    echo json_encode($response);
} catch (ExpiredException $e) {
    http_response_code(401);
    echo json_encode(["error" => "expired"]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
}
