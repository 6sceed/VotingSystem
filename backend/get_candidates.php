<?php
header('Content-Type: application/json');
require_once 'db.php';

// Debug: Check if database connection works
if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error,
        'candidates' => []
    ]);
    exit;
}

try {
    // First, let's check if the table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'candidates'");
    if ($tableCheck->num_rows == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Candidates table does not exist',
            'candidates' => []
        ]);
        exit;
    }

    // Fetch all candidates
    $sql = "SELECT id, name, position, photo, bio, created_at, is_active FROM candidates ORDER BY position, name";
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