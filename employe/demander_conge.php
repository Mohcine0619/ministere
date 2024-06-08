<?php
session_start(); // Start the session
require_once '../backend/db.php'; // Include your database connection

$message = "";

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    die("Error: User ID is not set in the session.");
}

$employee_id = $_SESSION['user_id']; // Use user_id from session as employee_id

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Collect and sanitize input data
    $start_date = htmlspecialchars($_POST['start_date']);
    $end_date = htmlspecialchars($_POST['end_date']);
    $reason = htmlspecialchars($_POST['reason']);

    // Prepare SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO conges (id_employe, date_debut, date_fin, motif, statut) VALUES (?, ?, ?, ?, 'pending')");
    if ($stmt) {
        $stmt->bind_param("isss", $employee_id, $start_date, $end_date, $reason);

        // Execute the statement
        if ($stmt->execute()) {
            $message = "Leave request submitted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }
}

// Fetch all leave requests for the logged-in user
$query = "SELECT date_debut, date_fin, motif, statut FROM conges WHERE id_employe = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$leave_requests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close(); // Close the connection after all operations are done
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Form</title>
    <?php include '../pages/boot.php'; ?>
    <?php include '../pages/nav.php'; ?>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
<div class="container main-content">
    <h2>Leave Request Form</h2>
    <?php
    if (!empty($message)) {
        echo '<div class="alert alert-info" role="alert">' . $message . '</div>';
    }
    ?>
    <form action="demander_conge.php" method="POST">
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <div class="form-group">
            <label for="reason">Reason:</label>
            <textarea class="form-control" id="reason" name="reason" required></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>

    <h2>Leave Requests</h2>
    <?php if (!empty($leave_requests)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $leave): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($leave['date_debut']); ?></td>
                        <td><?php echo htmlspecialchars($leave['date_fin']); ?></td>
                        <td><?php echo htmlspecialchars($leave['motif']); ?></td>
                        <td><?php echo htmlspecialchars($leave['statut']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info" role="alert">No leave requests found.</div>
    <?php endif; ?>
</div>
<?php include '../pages/footer.php'; ?>
</body>
</html>