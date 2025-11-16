<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$reason = $data['reason'];

try {

    $sql = "UPDATE voters SET status = 'suspended' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        $log_sql = "INSERT INTO suspension_logs (voter_id, reason, suspended_by) VALUES (?, ?, 1)"; // 1 = admin ID
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("is", $id, $reason);
        $log_stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Voter suspended successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error suspending voter']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>