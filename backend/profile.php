<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

// Start output buffering to catch any errors
ob_start();

try {
    include 'db.php';

    // Get JSON input
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!$data) {
        throw new Exception("Invalid JSON data received");
    }

    $id = intval($data['id'] ?? 0);
    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $address = trim($data['address'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $current_password = $data['current_password'] ?? '';
    $password = $data['password'] ?? '';
    $confirm_password = $data['confirm_password'] ?? '';

    // Validate required fields
    if (!$id || !$name || !$email || !$address || !$phone || !$current_password) {
        throw new Exception("All fields including current password are required.");
    }

    // First, verify the current password
    $checkStmt = $conn->prepare("SELECT password FROM voters WHERE id = ?");
    if (!$checkStmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkStmt->store_result();
    $checkStmt->bind_result($stored_password);
    $checkStmt->fetch();

    if ($checkStmt->num_rows === 0) {
        throw new Exception("User not found.");
    }

    // Verify current password - NO HASHING, direct comparison
    if ($current_password !== $stored_password) {
        throw new Exception("Incorrect current password");
    }
    $checkStmt->close();

    // Check if email is used by another user
    $emailStmt = $conn->prepare("SELECT id FROM voters WHERE email = ? AND id != ?");
    if (!$emailStmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $emailStmt->bind_param("si", $email, $id);
    $emailStmt->execute();
    $emailStmt->store_result();

    if ($emailStmt->num_rows > 0) {
        throw new Exception("Email is already in use by another account.");
    }
    $emailStmt->close();

    // Password change check
    $updatePassword = false;
    if (!empty($password) || !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match.");
        }
        if (!empty($password)) {
            $updatePassword = true;
        }
    }

    // Build UPDATE query - NO PASSWORD HASHING
    if ($updatePassword) {
        $stmt = $conn->prepare("UPDATE voters SET name = ?, email = ?, address = ?, phone = ?, password = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sssssi", $name, $email, $address, $phone, $password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE voters SET name = ?, email = ?, address = ?, phone = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssssi", $name, $email, $address, $phone, $id);
    }

    // Execute update
    if ($stmt->execute()) {
        // Fetch updated user data
        $userStmt = $conn->prepare("SELECT id, name, email, address, phone FROM voters WHERE id = ?");
        if (!$userStmt) {
            throw new Exception("Database prepare failed: " . $conn->error);
        }

        $userStmt->bind_param("i", $id);
        $userStmt->execute();
        $result = $userStmt->get_result();
        $userData = $result->fetch_assoc();

        echo json_encode([
            "status" => "success",
            "message" => "Profile updated successfully.",
            "user" => $userData
        ]);
        $userStmt->close();
    } else {
        throw new Exception("Database update failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Clear any previous output
    ob_clean();
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

// Flush the output buffer
ob_end_flush();
?>