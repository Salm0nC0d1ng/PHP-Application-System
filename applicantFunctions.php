<?php
include 'core/config.php';

function createApplicant($firstName, $lastName, $yearsOfService, $rank, $specialization, $preferredAssignment) {
    global $pdo;
    $query = "INSERT INTO law_enforcement_applicants (firstName, lastName, yearsOfService, rank, specialization, preferredAssignment)
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$firstName, $lastName, $yearsOfService, $rank, $specialization, $preferredAssignment]);
        return [
            "message" => "Law Enforcement applicant added successfully.",
            "statusCode" => 200
        ];
    } catch (PDOException $e) {
        return [
            "message" => "Error: " . $e->getMessage(),
            "statusCode" => 400
        ];
    }
}

function searchApplicants($searchTerm) {
    global $pdo;
    $query = "SELECT * FROM law_enforcement_applicants WHERE firstName LIKE ? OR lastName LIKE ? OR yearsOfService LIKE ? OR rank LIKE ? OR specialization LIKE ? OR preferredAssignment LIKE ?";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            "%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            "message" => "Search completed successfully.",
            "statusCode" => 200,
            "querySet" => $result
        ];
    } catch (PDOException $e) {
        return [
            "message" => "Error: " . $e->getMessage(),
            "statusCode" => 400
        ];
    }
}

function getAllApplicants() {
    global $pdo;
    $query = "SELECT * FROM law_enforcement_applicants";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute();
        return [
            "message" => "Law enforcement applicants fetched successfully.",
            "statusCode" => 200,
            "querySet" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    } catch (PDOException $e) {
        return [
            "message" => "Error: " . $e->getMessage(),
            "statusCode" => 400
        ];
    }
}

function updateApplicant($id, $firstName, $lastName, $yearsOfService, $rank, $specialization, $preferredAssignment) {
    global $pdo;
    $query = "UPDATE law_enforcement_applicants SET firstName = ?, lastName = ?, yearsOfService = ?, rank = ?, specialization = ?, preferredAssignment = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$firstName, $lastName, $yearsOfService, $rank, $specialization, $preferredAssignment, $id]);
        return [
            "message" => "Law enforcement applicant updated successfully.",
            "statusCode" => 200
        ];
    } catch (PDOException $e) {
        return [
            "message" => "Error: " . $e->getMessage(),
            "statusCode" => 400
        ];
    }
}

function deleteApplicant($id) {
    global $pdo;
    $query = "DELETE FROM law_enforcement_applicants WHERE id = ?";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$id]);
        return [
            "message" => "Law enforcement applicant deleted successfully.",
            "statusCode" => 200
        ];
    } catch (PDOException $e) {
        return [
            "message" => "Error: " . $e->getMessage(),
            "statusCode" => 400
        ];
    }
}
?>
