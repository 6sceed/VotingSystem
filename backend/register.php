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

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$address = trim($data['address'] ?? '');
$phone = trim($data['phone'] ?? '');
$age = intval($data['age'] ?? 0);

if (!$name || !$email || !$password || !$address || !$phone || !$age) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

// Age check (backend validation)
if ($age < 18) {
    echo json_encode(["status" => "error", "message" => "You must be at least 18 years old to register."]);
    exit;
}

// Check if email already exists
$check = $conn->prepare("SELECT id FROM voters WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email is already registered."]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// ⚠️ Store password as plain text (for testing only)
$plainPassword = $password;

// Insert new voter
$stmt = $conn->prepare("INSERT INTO voters (name, email, password, address, phone, age) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $name, $email, $plainPassword, $address, $phone, $age);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registration successful!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>