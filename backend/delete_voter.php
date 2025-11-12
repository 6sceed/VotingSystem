<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

try {
    // First delete user's votes
    $deleteVotes = "DELETE FROM votes WHERE user_id = ?";
    $stmt1 = $conn->prepare($deleteVotes);
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    // Then delete the voter from your voters table
    $sql = "DELETE FROM voters WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Voter deleted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting voter']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>