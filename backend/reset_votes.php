<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$password = $data['password'];

if ($password !== 'admin123') {
    echo json_encode(['success' => false, 'message' => 'Invalid password']);
    exit;
}

try {

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