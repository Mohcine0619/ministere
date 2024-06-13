<?php
session_start();
require_once '../backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $employeeId = intval($_POST['id']);

    // Prevent deletion if it's the logged-in user's own account
    if ($employeeId == $_SESSION['user_id']) {
        echo "Error: You cannot delete your own account.";
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM employe WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $employeeId);
        if ($stmt->execute()) {
            // Check if any rows were actually deleted
            if ($stmt->affected_rows > 0) {
                echo "Employee deleted successfully!";
            } else {
                echo "Error: No such employee found.";
            }
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