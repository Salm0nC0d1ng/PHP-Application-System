<?php
include 'applicantFunctions.php';

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchResult = searchApplicants($searchTerm);
}

if (isset($_POST['create'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $yearsOfService = $_POST['yearsOfService'];
    $rank = $_POST['rank'];
    $specialization = $_POST['specialization'];
    $preferredAssignment = $_POST['preferredAssignment'];

    $createResult = createApplicant($firstName, $lastName, $yearsOfService, $rank, $specialization, $preferredAssignment);
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $yearsOfService = $_POST['yearsOfService'];
    $rank = $_POST['rank'];
    $specialization = $_POST['specialization'];
    $preferredAssignment = $_POST['preferredAssignment'];

    $updateResult = updateApplicant($id, $firstName, $lastName, $yearsOfService, $rank, $specialization, $preferredAssignment);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteResult = deleteApplicant($id);
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
    <form action="index.php" method="GET">
        <input type="text" name="search" placeholder="Search Law Enforcement Applicants" />
        <button type="submit">Search</button>
    </form>

    <?php
    if (isset($searchResult)) {
        if ($searchResult['statusCode'] == 200) {
            foreach ($searchResult['querySet'] as $applicant) {
                echo "Name: " . $applicant['firstName'] . " " . $applicant['lastName'] . "<br>";
                echo "Years of Service: " . $applicant['yearsOfService'] . " years<br>";
                echo "Rank: " . $applicant['rank'] . "<br>";
                echo "Specialization: " . $applicant['specialization'] . "<br>";
                echo "Preferred Assignment: " . $applicant['preferredAssignment'] . "<br><br>";
            }
        } else {
            echo "Error: " . $searchResult['message'];
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
