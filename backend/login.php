<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// check if voter exists and get their status
<<<<<<< HEAD
$stmt = $conn->prepare("SELECT id, name, email, password, status, is_archived FROM voters WHERE email = ?");
=======
$stmt = $conn->prepare("SELECT id, name, email, password, status FROM voters WHERE email = ?");
>>>>>>> 1a112624b7ee701f0c01f7dbf4b7a38d2f5fd443
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    exit;
}

$voter = $result->fetch_assoc();

<<<<<<< HEAD
// check if account is archived
if (isset($voter['is_archived']) && $voter['is_archived'] == 1) {
    echo json_encode([
        "status" => "error",
        "message" => "This account is unavailable."
    ]);
    exit;
}

=======
>>>>>>> 1a112624b7ee701f0c01f7dbf4b7a38d2f5fd443
// check if account is suspended 
if ($voter['status'] === 'suspended' || $voter['status'] === 'Suspended' || $voter['status'] === '0') {

    $reason_stmt = $conn->prepare("SELECT reason FROM suspension_logs WHERE voter_id = ? ORDER BY suspended_at DESC LIMIT 1");
    $reason_stmt->bind_param("i", $voter['id']);
    $reason_stmt->execute();
    $reason_result = $reason_stmt->get_result();

    $suspension_reason = "Your account has been suspended by the administrator.";
    if ($reason_result->num_rows > 0) {
        $reason_data = $reason_result->fetch_assoc();
        $suspension_reason = $reason_data['reason'];
    }

    echo json_encode([
        "status" => "suspended",
        "message" => "Account suspended",
        "reason" => $suspension_reason
    ]);
    exit;
}


if ($password === $voter['password']) {

    echo json_encode([
        "status" => "success",
        "message" => "Login successful",
        "user" => [
            "id" => $voter['id'],
            "name" => $voter['name'],
            "email" => $voter['email']
        ],
        "redirect" => "dashboard.html"
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
}

$stmt->close();
$conn->close();
?>