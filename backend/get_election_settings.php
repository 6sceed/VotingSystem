<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

try {
    $sql = "SELECT * FROM election_settings ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $settings = $result->fetch_assoc();


        $now = date('Y-m-d H:i:s');


        $start = $settings['start_date'];
        $end = $settings['end_date'];

        // default time
        if (strlen($start) == 10)
            $start .= ' 00:00:00';
        if (strlen($end) == 10)
            $end .= ' 23:59:59';


        $isActiveFlag = $settings['is_active'] == '1' || $settings['is_active'] === 1;


        error_log("Voting Check - Now: $now, Start: $start, End: $end, is_active: " . $settings['is_active']);


        $isVotingActive = $isActiveFlag && (strtotime($now) >= strtotime($start) && strtotime($now) <= strtotime($end));

        error_log("Voting Active Result: " . ($isVotingActive ? 'true' : 'false'));


        $settings['is_voting_active'] = $isVotingActive;

        echo json_encode(['success' => true, 'settings' => $settings]);
        exit;
    } else {

        echo json_encode([
            'success' => true,
            'settings' => [
                'election_title' => 'National Elections 2025',
                'election_description' => 'Annual national elections for various positions',
                'start_date' => date('Y-m-d H:i:s'),
                'end_date' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'is_active' => 0,
                'is_voting_active' => false
            ]
        ]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    exit;
}
?>