<?php
// activityLogger.php - Handles logging user activities
include 'core/config.php';

function logActivity($userId, $actionType, $details = null) {
    global $pdo;
    $query = "INSERT INTO activity_logs (user_id, action_type, details, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId, $actionType, $details]);
}
?>