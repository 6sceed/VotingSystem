<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];
$votes = $data['votes'];

try {
    // Check if user has already voted for any position
    $check_sql = "SELECT position FROM votes WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $existing_votes = $check_stmt->get_result();

    if ($existing_votes->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already voted.']);
        exit;
    }

    // Insert new votes
    $insert_sql = "INSERT INTO votes (user_id, candidate_id, position) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);

    foreach ($votes as $position => $candidate_id) {
        $insert_stmt->bind_param("iis", $user_id, $candidate_id, $position);
        $insert_stmt->execute();
    }

    echo json_encode(['success' => true, 'message' => 'Vote submitted successfully!']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>