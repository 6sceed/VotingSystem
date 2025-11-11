<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['user_id']) || !isset($input['votes'])) {
    echo json_encode(['success' => false, 'message' => 'User ID and votes are required']);
    exit;
}

$userId = intval($input['user_id']);
$votes = $input['votes'];

try {
    // FIRST: Check if voting is active before anything else
    $settingsSql = "SELECT * FROM election_settings ORDER BY id DESC LIMIT 1";
    $settingsResult = $conn->query($settingsSql);

    if ($settingsResult->num_rows > 0) {
        $settings = $settingsResult->fetch_assoc();
        $now = date('Y-m-d H:i:s');
        $start = $settings['start_date'];
        $end = $settings['end_date'];

        $isActive = $settings['is_active'] == '1' || $settings['is_active'] === 1;

        if (!$isActive || $now < $start || $now > $end) {
            echo json_encode(['success' => false, 'message' => 'Voting is currently closed.']);
            exit;
        }
    }

    // Second, check if user already voted (any position)
    $checkStmt = $conn->prepare("SELECT id FROM votes WHERE user_id = ? LIMIT 1");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already voted and cannot vote again.']);
        exit;
    }

    // Insert votes for each position
    foreach ($votes as $position => $candidateId) {
        $stmt = $conn->prepare("INSERT INTO votes (user_id, candidate_id, position) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $userId, $candidateId, $position);
        $stmt->execute();
        $stmt->close();
    }

    echo json_encode([
        'success' => true,
        'message' => 'Vote submitted successfully!'
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>