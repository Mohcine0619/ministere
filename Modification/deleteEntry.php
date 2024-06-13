<?php
require_once '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($type == 'pole') {
        // Check if the pole has a directeur assigned
        $directeurCheck = $conn->prepare("SELECT nom_directeur FROM poles WHERE id = ?");
        $directeurCheck->bind_param("i", $id);
        $directeurCheck->execute();
        $directeurResult = $directeurCheck->get_result();
        $directeurRow = $directeurResult->fetch_assoc();
        $directeurCheck->close();

        if ($directeurRow['nom_directeur'] != NULL) {
            echo "<script>alert('Cannot delete pole as it has a directeur assigned.'); window.location.href='liste_branche.php';</script>";
        } else {
            // Proceed to delete the pole
            $stmt = $conn->prepare("DELETE FROM poles WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>alert('Pole deleted successfully'); window.location.href='liste_branche.php';</script>";
            } else {
                echo "<script>alert('Error deleting pole: " . $stmt->error . "'); window.location.href='liste_branche.php';</script>";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>