<?php
header('Content-Type: application/json');
require_once 'db.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID required']);
    exit;
}

$userId = intval($_GET['user_id']);

try {
    // Get user's votes with candidate names
    $sql = "SELECT v.position, c.name as candidate_name 
            FROM votes v 
            JOIN candidates c ON v.candidate_id = c.id 
            WHERE v.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $votes = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $votes[$row['position']] = $row['candidate_name'];
        }
    }

    echo json_encode([
        'success' => true,
        'votes' => $votes
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>