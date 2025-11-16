<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);

if (!$id) {
    echo json_encode(["status" => "error", "message" => "User ID is required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, email, address, phone, age, created_at, status FROM voters WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

$user = $result->fetch_assoc();


$user['address'] = $user['address'] ?? '';
$user['phone'] = $user['phone'] ?? '';

echo json_encode([
    "status" => "success",
    "user" => $user
]);

$stmt->close();
$conn->close();
?>