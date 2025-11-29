<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Candidate ID is required']);
    exit;
}

$id = intval($data['id']);

$stmt = $conn->prepare("DELETE FROM candidates WHERE id = ? AND is_archived = 1");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Candidate permanently deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Candidate not found or not archived']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete candidate: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>