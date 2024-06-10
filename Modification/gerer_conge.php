<?php
session_start();
require_once '../backend/db.php';

// Check if the user is logged in and has the RH role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'rh') {
    header('Location: login.php');
    exit();
}

// Handle approval or rejection of leave requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $conge_id = intval($_POST['conge_id']);
    $action = $_POST['action'];

    if ($action === 'approve' || $action === 'reject') {
        $stmt = $conn->prepare("UPDATE conges SET statut = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $action, $conge_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch leave requests
$query = "
    SELECT c.id, c.date_debut, c.date_fin, c.motif, c.statut, e.fullName, e.role, e.nb_post, e.nb_bureau
    FROM conges c
    JOIN employes e ON c.id_employe = e.id
    WHERE c.statut = 'pending'
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests</title>
    <?php include '../pages/boot.php'; ?>
    <?php include '../pages/nav.php'; ?>
    <style>
    .container {
    padding: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin: 0 auto; /* Center the container */
    max-width: calc(100% - 200px); /* Adjust this value to match the width of your sidebar */
    transform: translateX(90px); 
    }/* Shift content to the left */
    .back-button {
    background-color: transparent;
    color: #6c757d;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: color 0.3s, transform 0.2s;
    font-size: 20px;
    margin-bottom: 20px;
    transform: translateX(-390px); /* Shift content to the left */

}
</style>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
<div class="container main-content">
    <button class="back-button" onclick="window.history.back();"><i class="fas fa-arrow-left"></i></button>
    <h2>Gérer les demandes de congé</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de l'employé</th>
                    <th>Rôle</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Motif</th>
                    <th>Nb de postes</th>
                    <th>Nb de bureaux</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_debut']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_fin']); ?></td>
                        <td><?php echo htmlspecialchars($row['motif']); ?></td>
                        <td><?php echo htmlspecialchars($row['nb_post']); ?></td>
                        <td><?php echo htmlspecialchars($row['nb_bureau']); ?></td>
                        <td>
                            <form action="gerer_conge.php" method="POST" style="display:inline;">
                                <input type="hidden" name="conge_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info" role="alert">Aucune demande de congé en attente.</div>
    <?php endif; ?>
</div>
<?php include '../pages/footer.php'; ?>
</body>
</html>