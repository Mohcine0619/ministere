<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Fetch poles from the database
$poles = [];
$result = $conn->query("SELECT nom FROM poles");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $poles[] = $row;
    }
    $result->free();
}

// Fetch directors from the employes table where role is 'directeur'
$directeurs = [];
$result = $conn->query("SELECT fullName FROM employes WHERE role = 'directeur'");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $directeurs[] = $row;
    }
    $result->free();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $nom_departement = htmlspecialchars($_POST['nom']);
    $nom_directeur = isset($_POST['nom_directeur']) ? htmlspecialchars($_POST['nom_directeur']) : null;
    $nom_pole = htmlspecialchars($_POST['nom_pole']);

    // Prepare SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO departements (nom, nom_directeur, nom_pole) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $nom_departement, $nom_directeur, $nom_pole);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['message'] = 'New department added successfully';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Error: ' . $stmt->error;
            $_SESSION['message_type'] = 'error';
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = 'Error preparing statement: ' . $conn->error;
        $_SESSION['message_type'] = 'error';
    }
    $conn->close();
    header("Location: addDepartement.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department</title>
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
    <h2>Ajouter une Division</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert <?php echo $_SESSION['message_type'] == 'success' ? 'alert-success' : ''; ?>">
            <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>
    <form action="addDepartement.php" method="POST">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="nom_directeur">Nom Directeur:</label>
            <select id="nom_directeur" name="nom_directeur">
                <option value="">Sélectionner un directeur</option>
                <?php foreach ($directeurs as $directeur): ?>
                    <option value="<?php echo htmlspecialchars($directeur['fullName']); ?>">
                        <?php echo htmlspecialchars($directeur['fullName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="nom_pole">Nom de Pole:</label>
            <select id="nom_pole" name="nom_pole" required>
                <?php foreach ($poles as $pole): ?>
                    <option value="<?php echo htmlspecialchars($pole['nom']); ?>">
                        <?php echo htmlspecialchars($pole['nom']); ?>
                    </option>
                <?php endforeach; ?>
                </select>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <button type="button" style="width: 48%; background-color: dimgray; color: white;" onclick="window.location.href='../pages/home.php'">Cancel</button>
            <button type="submit" style="width: 48%;">Ajouter la Division</button>
        </div>
    </form>
</div>
<?php include '../pages/scboot.php'; ?>
<?php include '../pages/footer.php'; ?>
</body>
</html>