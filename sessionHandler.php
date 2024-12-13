<?php
// sessionHandler.php - Handles session initialization and user authentication
session_start();

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

function loginUser($userId) {
    $_SESSION['user_id'] = $userId;
}

function logoutUser() {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
