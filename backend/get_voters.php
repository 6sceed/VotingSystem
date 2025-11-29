<?php
header('Content-Type: application/json');
require_once 'db.php';

try {

    $sql = "SELECT 
                v.id, 
                v.name, 
                v.email, 
                v.address, 
                v.phone, 
                v.status,
                CASE 
                    WHEN v.id IN (SELECT DISTINCT user_id FROM votes) THEN 1 
                    ELSE 0 
                END as has_voted
            FROM voters v
<<<<<<< HEAD
            WHERE v.is_archived = 0
=======
>>>>>>> 1a112624b7ee701f0c01f7dbf4b7a38d2f5fd443
            ORDER BY v.name";

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
        'debug_info' => [
            'total_voters' => count($voters),
            'voters_who_voted' => array_sum(array_column($voters, 'has_voted'))
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>