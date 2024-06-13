<?php
require_once '../backend/db.php';

$id = $_GET['id'];
$type = $_GET['type'];

if ($type === 'service') {
    $query = "SELECT services.id, services.nom, services.nom_chef, services.id_departement, departement.nom AS nom_departement FROM services JOIN departement ON services.id_departement = departement.id WHERE services.id = ?";
} else if ($type === 'department') {
    $query = "SELECT id, nom, nom_directeur, nom_pole FROM departement WHERE id = ?";
} else if ($type === 'pole') {
    $query = "SELECT id, nom, nom_directeur FROM poles WHERE id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$entry = $result->fetch_assoc();

echo json_encode($entry);

$stmt->close();
$conn->close();
?>