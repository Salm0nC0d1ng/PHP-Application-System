<?php
// logout.php - Logout script
include 'sessionHandler.php';
include 'activityLogger.php';

if (isset($_SESSION['user_id'])) {
    logActivity($_SESSION['user_id'], 'LOGOUT', 'User logged out');
}
logoutUser();
?>
