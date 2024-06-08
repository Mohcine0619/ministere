<?php
session_start(); // Start the session
require_once '../backend/db.php'; // Include your database connection

$message = "";
$message_class = "alert-info"; // Default message class

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
            $message_class = "alert-success"; // Change to success class
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
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Form</title>
    <?php include '../pages/boot.php'; ?>
    <?php include '../pages/nav.php'; ?>
    <style>
        /* General container styling */
        .container {
            font-family: "Arial", sans-serif; /* Sets the font family to Arial */
            font-size: 14px; /* Sets the base font size */
            max-width: 500px; /* Matches the max-width of the signup form */
            margin: 20px auto; /* Centered with smaller top margin */
            background-color: #f8f9fa;
            padding: 10px; /* Matches the padding of the signup form */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* Form styling */
        form {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        /* Input field styling */
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        /* Button styling */
        .btn-primary {
            width: 100%;
            background-color: #0097a7;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #333;
        }
        .btn-cancel {
            width: 100%;
            background-color: grey;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-cancel:hover {
            background-color: #333;
        }
        /* Link styling */
        a {
            display: block;
            margin-top: 10px;
            text-align: center;
        }
        /* Alert message styling */
        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #721c24; /* Default text color for error */
            background-color: #f8d7da; /* Default background color for error */
            border-color: #f5c6cb; /* Default border color for error */
        }
        /* Success message styling */
        .alert.alert-success {
            color: #155724; /* Green text color for success */
            background-color: #d4edda; /* Green background color for success */
            border-color: #c3e6cb; /* Green border color for success */
        }
    </style>
    <script>
        function cancelRequest() {
            window.location.href = '../pages/home.php';
        }
    </script>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
<div class="container main-content">
    <h2>Leave Request Form</h2>
    <?php
    if (!empty($message)) {
        echo '<div class="alert ' . $message_class . '" role="alert">' . $message . '</div>';
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
        <button type="button" class="btn btn-cancel" onclick="cancelRequest()">Cancel</button>
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
