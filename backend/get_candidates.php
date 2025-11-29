<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error,
        'candidates' => []
    ]);
    exit;
}

try {
    //  check if the table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'candidates'");
    if ($tableCheck->num_rows == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Candidates table does not exist',
            'candidates' => []
        ]);
        exit;
    }

    // fetch all candidates
    $sql = "SELECT id, name, position, photo, bio, created_at, is_active FROM candidates WHERE is_archived = 0";
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

    $positionOrder = ['President' => 1, 'Vice President' => 2, 'Secretary' => 3, 'Treasurer' => 4, 'Auditor' => 5];
    usort($candidates, function ($a, $b) use ($positionOrder) {
        $orderA = $positionOrder[$a['position']] ?? 999;
        $orderB = $positionOrder[$b['position']] ?? 999;
        if ($orderA === $orderB) {
            return strcmp($a['name'], $b['name']);
        }
        return $orderA - $orderB;
    });

    echo json_encode([
        'success' => true,
        'candidates' => $candidates,
        'count' => count($candidates),
        'debug' => 'Table exists and query executed'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching candidates: ' . $e->getMessage(),
        'candidates' => []
    ]);
}

$conn->close();
?>