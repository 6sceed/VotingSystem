<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

try {
    $sql = "SELECT id, name, email, created_at FROM users WHERE role = 'voter' ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $voters = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $voters[] = $row;
        }
    }

    echo json_encode(['success' => true, 'voters' => $voters]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>