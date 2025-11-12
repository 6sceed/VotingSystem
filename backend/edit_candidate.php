<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$name = $data['name'];
$position = $data['position'];
$bio = $data['bio'];
$photo = $data['photo'];

try {
    $sql = "UPDATE candidates SET name = ?, position = ?, bio = ?, photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $position, $bio, $photo, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Candidate updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating candidate']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>