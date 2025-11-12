<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$password = $data['password'];

// Simple password check
if ($password !== 'admin123') {
    echo json_encode(['success' => false, 'message' => 'Invalid password']);
    exit;
}

try {
    // Delete all votes
    $sql = "DELETE FROM votes";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'All votes reset successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error resetting votes']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>