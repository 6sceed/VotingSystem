<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'Candidate ID is required']);
    exit;
}

$candidateId = intval($input['id']);

try {
    // Delete candidate from database
    $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
    $stmt->bind_param("i", $candidateId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Candidate deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Candidate not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete candidate: ' . $stmt->error]);
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>