<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $sql = "SELECT id, name, email, archived_at FROM voters WHERE is_archived = 1 ORDER BY archived_at DESC";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception('Query failed: ' . $conn->error);
    }

    $voters = [];
    while ($row = $result->fetch_assoc()) {
        $voters[] = $row;
    }

    echo json_encode([
        'success' => true,
        'voters' => $voters,
        'count' => count($voters)
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>