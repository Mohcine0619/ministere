<?php
require_once '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $nom = $_POST['nom'];
    $nom_directeur = $_POST['nom_directeur'];
    $nom_pole = $_POST['nom_pole'] ?? null;
    $id_departement = $_POST['id_departement'] ?? null;
    $nom_chef = $_POST['nom_chef'] ?? null;

    if ($type == 'pole') {
        $stmt = $conn->prepare("UPDATE poles SET nom = ?, nom_directeur = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nom, $nom_directeur, $id);
    } elseif ($type == 'department') {
        $stmt = $conn->prepare("UPDATE departements SET nom = ?, nom_directeur = ?, nom_pole = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nom, $nom_directeur, $nom_pole, $id);
    } elseif ($type == 'service') {
        $stmt = $conn->prepare("UPDATE services SET nom = ?, nom_chef = ?, id_departement = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nom, $nom_chef, $id_departement, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>