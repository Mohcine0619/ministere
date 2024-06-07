<?php
session_start(); // Start the session
require_once '../backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $employeeId = intval($_POST['id']); // Get the employee ID from the POST data

    // Prepare SQL statement to delete the employee from the database
    $stmt = $conn->prepare("DELETE FROM employes WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $employeeId);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Employee deleted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}
?>