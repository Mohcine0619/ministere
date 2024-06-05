<?php
require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $nom = htmlspecialchars($_POST['nom']);
    $nom_chef = isset($_POST['nom_chef']) ? htmlspecialchars($_POST['nom_chef']) : null;
    $id_departement = isset($_POST['id_departement']) ? (int)$_POST['id_departement'] : null;

    // Check if the department exists
    $deptCheckStmt = $conn->prepare("SELECT id FROM departements WHERE id = ?");
    $deptCheckStmt->bind_param("i", $id_departement);
    $deptCheckStmt->execute();
    $deptResult = $deptCheckStmt->get_result();
    if ($deptResult->num_rows == 0) {
        die("Department does not exist.");
    }

    // Prepare SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO services (nom, nom_chef, id_departement) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssi", $nom, $nom_chef, $id_departement);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('New service added successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
</head>
<body>
<div>
    <h2>Add Service</h2>
    <form action="addService.php" method="POST">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="nom_chef">Nom Chef:</label>
            <input type="text" id="nom_chef" name="nom_chef">
        </div>
        <button type="submit">Add Service</button>
    </form>

</div>
</body>
</html>
