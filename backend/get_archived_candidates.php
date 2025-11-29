<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $sql = "SELECT id, name, position, photo, bio, archived_at FROM candidates WHERE is_archived = 1 ORDER BY archived_at DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $candidates = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $candidates[] = $row;
        }
    }

    echo json_encode([
        'success' => true,
        'candidates' => $candidates,
        'count' => count($candidates)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching archived candidates: ' . $e->getMessage(),
        'candidates' => []
    ]);
}

$conn->close();
?>