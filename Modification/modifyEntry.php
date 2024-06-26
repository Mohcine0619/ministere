<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : null;
    $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : null;
    $nom_directeur = isset($_POST['nom_directeur']) ? htmlspecialchars($_POST['nom_directeur']) : null;
    $nom_pole = isset($_POST['nom_pole']) ? htmlspecialchars($_POST['nom_pole']) : null;
    $id_departement = isset($_POST['id_departement']) ? (int)$_POST['id_departement'] : null;
    $nom_chef = isset($_POST['nom_chef']) ? htmlspecialchars($_POST['nom_chef']) : null;

    // Debugging output
    error_log("ID: $id, Type: $type, Nom: $nom, Nom Directeur: $nom_directeur, Nom Pole: $nom_pole, ID Departement: $id_departement, Nom Chef: $nom_chef");

    if ($id && $type && $nom) {
        // Determine the table and columns based on the type
        switch ($type) {
            case 'pole':
                $table = 'poles';
                $columns = 'nom = ?, nom_directeur = ?';
                $params = [$nom, $nom_directeur, $id];
                $types = 'ssi';
                break;
            case 'department':
                $table = 'departement';
                $columns = 'nom = ?, nom_directeur = ?, nom_pole = ?';
                $params = [$nom, $nom_directeur, $nom_pole, $id];
                $types = 'sssi';
                break;
            case 'service':
                $table = 'services';
                $columns = 'nom = ?, nom_chef = ?, id_departement = ?';
                $params = [$nom, $nom_chef, $id_departement, $id];
                $types = 'ssii';
                break;
            default:
                $_SESSION['message'] = 'Invalid type specified.';
                $_SESSION['message_type'] = 'error';
                header("Location: ../pages/home.php");
                exit();
        }

        // Prepare SQL statement to update the entry in the database
        $stmt = $conn->prepare("UPDATE $table SET $columns WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Entry modified successfully';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error: ' . $stmt->error;
                $_SESSION['message_type'] = 'error';
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = 'Error preparing statement: ' . $conn->error;
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Invalid ID, type, or name.';
        $_SESSION['message_type'] = 'error';
    }
    $conn->close();
    header("Location: ../pages/home.php");
    exit();
} else {
    $_SESSION['message'] = 'Invalid request method.';
    $_SESSION['message_type'] = 'error';
    header("Location: ../pages/home.php");
    exit();
}
?>
