<?php
header('Content-Type: application/json');
require_once 'db.php';

try {

    $sql = "
        SELECT 
            c.id,
            c.name,
            c.position,
            COUNT(v.id) as vote_count
        FROM candidates c
        LEFT JOIN votes v ON c.id = v.candidate_id
        GROUP BY c.id, c.name, c.position
        ORDER BY c.position, vote_count DESC
    ";

    $result = $conn->query($sql);
    $results = [];

    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    echo json_encode([
        'success' => true,
        'results' => $results
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>