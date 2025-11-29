<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'Voter ID is required']);
    exit;
}

$voterId = intval($input['id']);

try {
    $stmt = $conn->prepare("UPDATE voters SET is_archived = 0, archived_at = NULL WHERE id = ?");
    $stmt->bind_param("i", $voterId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Voter restored successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Voter not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to restore voter: ' . $stmt->error]);
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>