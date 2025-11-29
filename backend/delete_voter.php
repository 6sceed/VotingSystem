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
$voterId = intval($data['id'] ?? 0);

if (!$voterId) {
    echo json_encode(["success" => false, "message" => "Voter ID is required"]);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE voters SET is_archived = 1, archived_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $voterId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Voter archived successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Voter not found"]);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Archive voter error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error archiving voter: " . $e->getMessage()]);
}

$conn->close();
?>