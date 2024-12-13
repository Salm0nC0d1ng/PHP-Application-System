<?php
include 'applicantFunctions.php';
include 'sessionHandler.php';
checkAuth();
include 'activityLogger.php';

// Fetch logged-in user's username
$query = "SELECT username FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$username = $user ? $user['username'] : 'Guest';

// Handle registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (registerUser($username, $password)) {
        echo "Registration successful! You can now log in.";
    } else {
        echo "Registration failed. Username might already be taken.";
    }
}

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        echo "Login successful! Welcome, $username.";
    } else {
        echo "Login failed. Please check your credentials.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    logoutUser();
    echo "You have been logged out.";
}

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

if (isset($_POST['create'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $yearsOfService = $_POST['yearsOfService'];
    $rank = $_POST['rank'];
    $specialization = $_POST['specialization'];
    $preferredAssignment = $_POST['preferredAssignment'];

    $createResult = createApplicant($firstName, $lastName, $yearsOfService, $rank, $specialization, $preferredAssignment);
    logActivity($_SESSION['user_id'], 'INSERTION', "Added applicant: $firstName $lastName");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteResult = deleteApplicant($id);
    logActivity($_SESSION['user_id'], 'DELETION', "Deleted applicant with ID: $applicantId");
}

$applicants = getAllApplicants();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Law Enforcement Applicant System</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <a href="logout.php">Logout</a>
    <br><br>
    <form action="index.php" method="GET">
        <input type="text" name="firstName" placeholder="First Name" required>
        <input type="text" name="lastName" placeholder="Last Name" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
            // Retrieve the inputs
            $firstName = isset($_GET['firstName']) ? $_GET['firstName'] : '';
            $lastName = isset($_GET['lastName']) ? $_GET['lastName'] : '';
            
            // Validate the inputs
            if (!empty($firstName) && !empty($lastName)) {
                // Call the function with both arguments
                $searchResult = searchApplicants($firstName, $lastName);
                logActivity($_SESSION['user_id'], 'SEARCH', "Searched for: First Name - $firstName, Last Name - $lastName");
                if ($searchResult['statusCode'] === 200 && !empty($searchResult['querySet'])) {
                    foreach ($searchResult['querySet'] as $applicant) {
                        echo "Name: " . $applicant['firstName'] . " " . $applicant['lastName'] . "<br>";
                        echo "Years of Service: " . $applicant['yearsOfService'] . " years<br>";
                        echo "Rank: " . $applicant['rank'] . "<br>";
                        echo "Specialization: " . $applicant['specialization'] . "<br>";
                        echo "Preferred Assignment: " . $applicant['preferredAssignment'] . "<br><br>";
                    }
                } else {
                    echo "No results found for: $firstName $lastName.";
                }
            } else {
                echo "Error: Both First Name and Last Name are required.";
            }
        }
        ?>

    <h2>Quick Resume Application</h2>
    <form action="index.php" method="POST">
        <input type="text" name="firstName" placeholder="First Name" required><br>
        <input type="text" name="lastName" placeholder="Last Name" required><br>
        <input type="number" name="yearsOfService" placeholder="Years of Service" required><br>
        <input type="text" name="rank" placeholder="Rank" required><br>
        <input type="text" name="specialization" placeholder="Specialization" required><br>
        <input type="text" name="preferredAssignment" placeholder="Preferred Assignment" required><br>
        <button type="submit" name="create">Create</button>
    </form>

    <h2>Law Enforcement Applicants</h2>
    <?php
    if ($applicants['statusCode'] == 200) {
        foreach ($applicants['querySet'] as $applicant) {
            echo "Name: " . $applicant['firstName'] . " " . $applicant['lastName'] . "<br>";
            echo "Years of Service: " . $applicant['yearsOfService'] . " years<br>";
            echo "Rank: " . $applicant['rank'] . "<br>";
            echo "Specialization: " . $applicant['specialization'] . "<br>";
            echo "Preferred Assignment: " . $applicant['preferredAssignment'] . "<br>";
            echo "<a href='index.php?delete=" . $applicant['id'] . "'>Delete</a><br><br>";
        }
    } else {
        echo "Error: " . $applicants['message'];
    }
    ?>
</body>
</html>
