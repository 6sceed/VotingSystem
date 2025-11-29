<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['name']) || !isset($input['position'])) {
    echo json_encode(['success' => false, 'message' => 'Name and position are required']);
    exit;
}

$name = trim($input['name']);
$position = trim($input['position']);
$bio = isset($input['bio']) ? trim($input['bio']) : '';
$photo = isset($input['photo']) ? trim($input['photo']) : 'default_photo.jpg';

$stmt = $conn->prepare("INSERT INTO candidates (name, position, bio, photo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $position, $bio, $photo);

if ($stmt->execute()) {
    $candidateId = $stmt->insert_id;
    echo json_encode([
        'success' => true,
        'message' => 'Candidate added successfully',
        'candidate_id' => $candidateId
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add candidate: ' . $stmt->error]);
}

$stmt->close();
?>