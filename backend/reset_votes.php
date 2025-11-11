<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$password = $input['password'] ?? '';

// Simple password protection
if ($password !== 'admin123') {
    echo json_encode(['success' => false, 'message' => 'Invalid reset password']);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Delete all votes
    $conn->query("DELETE FROM votes");

    // Commit changes
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'All votes have been reset successfully!'
    ]);

} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Reset failed: ' . $e->getMessage()]);
}
?>