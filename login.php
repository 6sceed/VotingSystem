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
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// --- 1️⃣ Hardcoded admin account ---
if ($email === 'admin' && $password === 'admin123') {
    echo json_encode([
        "status" => "admin",
        "redirect" => "admindashboard.html",
        "user" => [
            "username" => "Administrator"
        ]
    ]);
    exit;
}

// --- 2️⃣ Otherwise, check voters table (regular users) ---
$sql = "SELECT * FROM voters WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // If password is hashed, use password_verify(); if plain text, use simple match
    if (password_verify($password, $row['password']) || $password === $row['password']) {
        echo json_encode([
            "status" => "success",
            "redirect" => "dashboard.html",
            "user" => [
                "id" => $row["id"],
                "name" => $row["name"],
                "email" => $row["email"]
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No account found."]);
}

$stmt->close();
$conn->close();
?>