<!-- <?php
// require_once '../backend/db.php';

// if (isset($_POST['pole_id'])) {
//     $pole_id = intval($_POST['pole_id']);
//     $query = "SELECT d.id, d.nom 
//               FROM departements d
//               JOIN poles p ON d.nom_pole = p.nom
//               WHERE p.id = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("i", $pole_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $departments = [];
//     while ($row = $result->fetch_assoc()) {
//         $departments[] = $row;
//     }
//     echo json_encode($departments);
// }
?> -->