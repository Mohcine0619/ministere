<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Fetch departments from the database
$departments = [];
$result = $conn->query("SELECT id, nom FROM departements");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
    $result->free();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $nom = htmlspecialchars($_POST['nom']);
    $nom_chef = isset($_POST['nom_chef']) ? htmlspecialchars($_POST['nom_chef']) : null;
    $id_departement = isset($_POST['id_departement']) ? (int)$_POST['id_departement'] : null;

    // Check if the department exists
    $deptCheckStmt = $conn->prepare("SELECT id FROM departements WHERE id = ?");
    $deptCheckStmt->bind_param("i", $id_departement);
    $deptCheckStmt->execute();
    $deptResult = $deptCheckStmt->get_result();
    if ($deptResult->num_rows == 0) {
        die("Department does not exist.");
    }

    // Prepare SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO services (nom, nom_chef, id_departement) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssi", $nom, $nom_chef, $id_departement);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('New service added successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
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
        button {
            width: 100%;
            background-color: #0097a7;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
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
</head>
<body>
<?php include '../pages/side.php'; ?>
<?php include '../pages/navbar.php'; ?>
<div class="container main-content">
    <h2>Add Service</h2>
    <form action="addService.php" method="POST">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="nom_chef">Nom Chef:</label>
            <input type="text" id="nom_chef" name="nom_chef">
        </div>
        <div>
            <label for="id_departement">Department:</label>
            <select id="id_departement" name="id_departement">
                <?php foreach ($departments as $department) { ?>
                    <option value="<?php echo $department['id']; ?>"><?php echo $department['nom']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit">Add Service</button>
    </form>
</div>
<?php include '../pages/scboot.php'; ?>
<?php include '../pages/footer.php'; ?>
</body>
</html>
