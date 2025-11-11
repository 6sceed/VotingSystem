<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $sql = "SELECT COUNT(*) as total_votes FROM votes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'total_votes' => $row['total_votes']
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>