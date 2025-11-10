<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['election_title']) || !isset($input['start_date']) || !isset($input['end_date'])) {
    echo json_encode(['success' => false, 'message' => 'Title, start date, and end date are required']);
    exit;
}

try {
    // Check if settings already exist
    $checkSql = "SELECT id FROM election_settings ORDER BY id DESC LIMIT 1";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Update existing settings
        $row = $checkResult->fetch_assoc();
        $stmt = $conn->prepare("UPDATE election_settings SET election_title=?, election_description=?, start_date=?, end_date=?, is_active=? WHERE id=?");
        $stmt->bind_param("ssssii", $input['election_title'], $input['election_description'], $input['start_date'], $input['end_date'], $input['is_active'], $row['id']);
    } else {
        // Insert new settings
        $stmt = $conn->prepare("INSERT INTO election_settings (election_title, election_description, start_date, end_date, is_active) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $input['election_title'], $input['election_description'], $input['start_date'], $input['end_date'], $input['is_active']);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Election settings saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save settings: ' . $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>