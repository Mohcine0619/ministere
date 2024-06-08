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
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
<div class="container main-content">
    <h2>Manage Leave Requests</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Role</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Number of Posts</th>
                    <th>Number of Offices</th>
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
        <div class="alert alert-info" role="alert">No pending leave requests.</div>
    <?php endif; ?>
</div>
<?php include '../pages/footer.php'; ?>
</body>
</html>