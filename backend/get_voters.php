<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    // Get voters with their vote status using existing status column
    $sql = "SELECT v.id, v.name, v.email, v.address, v.phone, v.status,
                   CASE WHEN EXISTS (SELECT 1 FROM votes WHERE user_id = v.id) THEN 1 ELSE 0 END as has_voted
            FROM voters v";
    $result = $conn->query($sql);
    $voters = [];

    while ($row = $result->fetch_assoc()) {
        $voters[] = $row;
    }

    echo json_encode([
        'success' => true,
        'voters' => $voters
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>