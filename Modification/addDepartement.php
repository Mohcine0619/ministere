<?php
require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $id_departement = htmlspecialchars($_POST['id_departement']);
    $nom_departement = htmlspecialchars($_POST['nom']);
    $nom_directeur = isset($_POST['nom_directeur']) ? htmlspecialchars($_POST['nom_directeur']) : null;

    // Prepare SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO departements (? ,nom, nom_directeur) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $nom, $nom_directeur);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('New department added successfully');</script>";
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
    <title>Add Department</title>
</head>
<body>
<div>
    <h2>Add Department</h2>
    <form action="addDepartement.php" method="POST">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="nom_directeur">Nom Directeur:</label>
            <input type="text" id="nom_directeur" name="nom_directeur">
        </div>
        <button type="submit">Add Department</button>
    </form>
</div>
</body>
</html>
