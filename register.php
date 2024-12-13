<?php
// register.php - User registration page
include 'core/config.php';
include 'activityLogger.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $password]);

    $newUserId = $pdo->lastInsertId();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <form method="POST" action="">
        <h2>Register<h2>
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>