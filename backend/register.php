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
require_once 'email.php';


$input = file_get_contents('php://input');
$data = json_decode($input, true);


error_log("Received registration data: " . print_r($data, true));

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$address = trim($data['address'] ?? '');
$phone = trim($data['phone'] ?? '');
$age = intval($data['age'] ?? 0);

// convert name and adress to UPPERCASE
$name = strtoupper($name);

$address = strtoupper($address);

if (empty($name) || empty($email) || empty($password) || empty($address) || empty($phone) || $age === 0) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

if ($age < 18) {
    echo json_encode(["status" => "error", "message" => "You must be at least 18 years old to register."]);
    exit;
}


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


$stmt = $conn->prepare("INSERT INTO voters (name, email, password, address, phone, age, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssssi", $name, $email, $password, $address, $phone, $age);

if ($stmt->execute()) {

    try {
        $emailSender = new EmailSender();
        $emailSent = $emailSender->sendWelcomeEmail($email, $name);

        if ($emailSent) {
            echo json_encode([
                "status" => "success",
                "message" => "Registration successful! A welcome email has been sent to your email address."
            ]);
        } else {
            echo json_encode([
                "status" => "success",
                "message" => "Registration successful! (Welcome email will be sent shortly)"
            ]);
        }
    } catch (Exception $e) {

        echo json_encode([
            "status" => "success",
            "message" => "Registration successful!"
        ]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>