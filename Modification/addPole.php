<?php
require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $nom_pole = htmlspecialchars($_POST['nom']);
    $nom_direc = isset($_POST['nom_responsable']) ? htmlspecialchars($_POST['nom_responsable']) : '';

    // Prepare SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO pole (nom, nom_directeur) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $nom_pole, $nom_direc);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('New pole added successfully');</script>";
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
    <title>Add Pole</title>
</head>
<body>
<div>
    <h2>Add Pole</h2>
    <form action="addPole.php" method="POST">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="nom_responsable">Nom Responsable:</label>
            <input type="text" id="nom_responsable" name="nom_responsable">
        </div>
        <button type="submit">Add Pole</button>
    </form>
</div>
</body>
</html>
