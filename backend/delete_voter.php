<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$voterId = intval($data['id'] ?? 0);

if (!$voterId) {
    echo json_encode(["success" => false, "message" => "Voter ID is required"]);
    exit;
}

try {
<<<<<<< HEAD
    $stmt = $conn->prepare("UPDATE voters SET is_archived = 1, archived_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $voterId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Voter archived successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Voter not found"]);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Archive voter error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error archiving voter: " . $e->getMessage()]);
=======

    $conn->begin_transaction();


    $conn->query("SET FOREIGN_KEY_CHECKS=0");


    $tablesToClean = [
        'suspension_logs' => 'voter_id',
        'votes' => 'voter_id',
        'audit_logs' => 'user_id',
        'activity_logs' => 'voter_id',
        'login_attempts' => 'user_id',
        'user_sessions' => 'user_id'
    ];

    foreach ($tablesToClean as $table => $column) {
        try {
            $stmt = $conn->prepare("DELETE FROM $table WHERE $column = ?");
            $stmt->bind_param("i", $voterId);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {

            error_log("Note: Could not clean table $table: " . $e->getMessage());
        }
    }


    $deleteVoterStmt = $conn->prepare("DELETE FROM voters WHERE id = ?");
    $deleteVoterStmt->bind_param("i", $voterId);
    $deleteVoterStmt->execute();


    $conn->query("SET FOREIGN_KEY_CHECKS=1");

    if ($deleteVoterStmt->affected_rows > 0) {
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Voter deleted successfully"]);
    } else {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Voter not found"]);
    }

    $deleteVoterStmt->close();

} catch (Exception $e) {
    $conn->rollback();

    $conn->query("SET FOREIGN_KEY_CHECKS=1");

    error_log("Delete voter error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Error deleting voter: " . $e->getMessage()]);
>>>>>>> 1a112624b7ee701f0c01f7dbf4b7a38d2f5fd443
}

$conn->close();
?>