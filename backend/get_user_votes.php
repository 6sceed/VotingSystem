<?php
header('Content-Type: application/json');
require_once 'db.php';

$user_id = $_GET['user_id'];

try {
    $sql = "SELECT v.position, c.name 
            FROM votes v 
            JOIN candidates c ON v.candidate_id = c.id 
            WHERE v.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $votes = [];
    while ($row = $result->fetch_assoc()) {
        $votes[$row['position']] = $row['name'];
    }

    echo json_encode([
        'success' => true,
        'votes' => $votes
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>