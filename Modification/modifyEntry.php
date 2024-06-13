<?php
require_once '../backend/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check required fields
    if (!isset($_POST['id'], $_POST['type'], $_POST['nom'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $id = $_POST['id'];
    $type = $_POST['type'];
    $nom = $_POST['nom'];
    $nom_directeur = $_POST['nom_directeur'] ?? null;
    $nom_pole = $_POST['nom_pole'] ?? null;
    $id_departement = $_POST['id_departement'] ?? null;
    $nom_chef = $_POST['nom_chef'] ?? null;

    if ($type == 'pole') {
        $stmt = $conn->prepare("UPDATE poles SET nom = ?, nom_directeur = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nom, $nom_directeur, $id);
    } elseif ($type == 'department') {
        $stmt = $conn->prepare("UPDATE departement SET nom = ?, nom_directeur = ?, nom_pole = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nom, $nom_directeur, $nom_pole, $id);
    } elseif ($type == 'service') {
        $stmt = $conn->prepare("UPDATE services SET nom = ?, nom_chef = ?, id_departement = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nom, $nom_chef, $id_departement, $id);
    }

    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'MySQL prepare error: ' . $conn->error]);
        exit;
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>