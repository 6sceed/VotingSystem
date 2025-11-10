<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $sql = "SELECT * FROM election_settings ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $settings = $result->fetch_assoc();
        echo json_encode(['success' => true, 'settings' => $settings]);
    } else {
        // Return default settings if none exist
        echo json_encode([
            'success' => true,
            'settings' => [
                'election_title' => 'National Elections 2025',
                'election_description' => 'Annual national elections for various positions',
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+7 days')),
                'is_active' => 0
            ]
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>