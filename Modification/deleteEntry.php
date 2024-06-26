<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : null;

    if ($id && $type) {
        // Determine the table and column based on the type
        switch ($type) {
            case 'pole':
                $table = 'poles';
                $column = 'id';
                break;
            case 'department':
                $table = 'departement';
                $column = 'id';
                break;
            case 'service':
                $table = 'services';
                $column = 'id';
                break;
            default:
                $_SESSION['message'] = 'Invalid type specified.';
                $_SESSION['message_type'] = 'error';
                header("Location: ../pages/home.php");
                exit();
        }

        // Debugging: Check if table and column are set correctly
        error_log("Table: $table, Column: $column, ID: $id");

        // Prepare SQL statement to delete the entry from the database
        $stmt = $conn->prepare("DELETE FROM $table WHERE $column = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Entry deleted successfully';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error: ' . $stmt->error;
                $_SESSION['message_type'] = 'error';
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = 'Error: ' . $conn->error;
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Invalid input data.';
        $_SESSION['message_type'] = 'error';
    }
}

header("Location: ../pages/home.php");
exit();
