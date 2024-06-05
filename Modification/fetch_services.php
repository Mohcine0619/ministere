<?php
// require_once '../backend/db.php';

// if (isset($_POST['departement_id'])) {
//     $departement_id = intval($_POST['departement_id']);
//     $query = "SELECT id, nom FROM services WHERE id_departement = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("i", $departement_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $services = [];
//     while ($row = $result->fetch_assoc()) {
//         $services[] = $row;
//     }
//     echo json_encode($services);
// }
?>