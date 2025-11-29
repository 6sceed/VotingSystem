<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}


$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'Candidate ID is required']);
    exit;
}

$candidateId = intval($input['id']);

try {
<<<<<<< HEAD
    $stmt = $conn->prepare("UPDATE candidates SET is_archived = 1, archived_at = NOW() WHERE id = ?");
=======
    // delete candidate from database
    $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
>>>>>>> 1a112624b7ee701f0c01f7dbf4b7a38d2f5fd443
    $stmt->bind_param("i", $candidateId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
<<<<<<< HEAD
            echo json_encode(['success' => true, 'message' => 'Candidate archived successfully']);
=======
            echo json_encode(['success' => true, 'message' => 'Candidate deleted successfully']);
>>>>>>> 1a112624b7ee701f0c01f7dbf4b7a38d2f5fd443
        } else {
            echo json_encode(['success' => false, 'message' => 'Candidate not found']);
        }
    } else {
<<<<<<< HEAD
        echo json_encode(['success' => false, 'message' => 'Failed to archive candidate: ' . $stmt->error]);
=======
        echo json_encode(['success' => false, 'message' => 'Failed to delete candidate: ' . $stmt->error]);
>>>>>>> 1a112624b7ee701f0c01f7dbf4b7a38d2f5fd443
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>