<?php
require_once '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];

    if ($type == 'pole') {
        // Check for related records in the departements table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM departements WHERE nom_directeur = (SELECT nom_directeur FROM poles WHERE id = ?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "<script>alert('Cannot delete pole. There are related departments.'); window.location.href='liste_branche.php';</script>";
            $conn->close();
            exit();
        }

        // If no related records, delete the pole
        $stmt = $conn->prepare("DELETE FROM poles WHERE id = ?");
        $stmt->bind_param("i", $id);
    } elseif ($type == 'department') {
        // Check for related records in the services table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM services WHERE id_departement = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "<script>alert('Cannot delete department. There are related services.'); window.location.href='liste_branche.php';</script>";
            $conn->close();
            exit();
        }

        // If no related records, delete the department
        $stmt = $conn->prepare("DELETE FROM departements WHERE id = ?");
        $stmt->bind_param("i", $id);
    } elseif ($type == 'service') {
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Entry deleted successfully'); window.location.href='liste_branche.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='liste_branche.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>
