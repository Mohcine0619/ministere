<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Fetch fullName values from employe table where role is 'directeur'
$nom_directeurs = [];
$result = $conn->query("SELECT fullName FROM employe WHERE role = 'directeur'");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $nom_directeurs[] = $row['fullName']; // Ensure the column name matches the one in your database
    }
    $result->free();
} else {
    error_log("SQL Error: " . $conn->error);  // Log error to PHP error log
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $nom_pole = htmlspecialchars($_POST['nom']);
    $nom_direc = isset($_POST['nom_responsable']) && $_POST['nom_responsable'] !== 'None' ? htmlspecialchars($_POST['nom_responsable']) : null;

    // Prepare SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO poles (nom, nom_directeur) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $nom_pole, $nom_direc);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['message'] = 'New pole added successfully';
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
    header("Location: addPole.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Pole</title>
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
    <script>
        function validateForm() {
            var nomResponsable = document.getElementById("nom_responsable").value;
            if (nomResponsable === "") {
                alert("S'il vous plaît sélectionner un directeur");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<?php include '../pages/side.php'; ?>
<?php include '../pages/navbar.php'; ?>
<div class="container main-content">
    <h2>Ajouter un Pole</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert <?php echo $_SESSION['message_type'] == 'success' ? 'alert-success' : ''; ?>">
            <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>
    <form action="addPole.php" method="POST" onsubmit="return validateForm()">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="nom_responsable">Nom Responsable:</label>
            <select id="nom_responsable" name="nom_responsable">
                <option value="">Sélectionner le nom du directeur</option>
                <option value="None">aucun</option> <!-- Added None option -->
                <?php foreach ($nom_directeurs as $nom_directeur): ?>
                    <option value="<?php echo htmlspecialchars($nom_directeur); ?>"><?php echo htmlspecialchars($nom_directeur); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <button type="button" style="width: 48%; background-color: dimgray; color: white;" onclick="window.location.href='../pages/home.php'">Annuler</button>
            <button type="submit" style="width: 48%;">Ajouter le Pole</button>
        </div>
    </form>
</div>
<?php include '../pages/scboot.php'; ?>
<?php include '../pages/footer.php'; ?>
</body>
</html>