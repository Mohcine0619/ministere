<?php
session_start(); // Start the session
require_once '../backend/db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch poles from the database
$poles = [];
$result = $conn->query("SELECT id, nom FROM poles");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $poles[] = $row;
    }
    $result->free();
}

// Fetch employee data if id is provided
$employee = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM employes WHERE id = $id");
    if ($result) {
        $employee = $result->fetch_assoc();
        $result->free();
    }
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Collect and sanitize input data
    $fullName = htmlspecialchars($_POST['fullName']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    $departement = isset($_POST['departement']) ? htmlspecialchars($_POST['departement']) : null;
    $service = isset($_POST['service']) ? htmlspecialchars($_POST['service']) : null;
    $nb_post = htmlspecialchars($_POST['nb_post']);
    $occupation = htmlspecialchars($_POST['occupation']);
    $nb_bureau = intval($_POST['nb_bureau']);
    $corps = htmlspecialchars($_POST['corps']);
    $pole = isset($_POST['pole']) ? htmlspecialchars($_POST['pole']) : null;

    // Handle file upload
    $photo = $employee['photo'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploads_dir = '../uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true); // Create the directory if it doesn't exist
        }
        $photo_basename = basename($_FILES['photo']['name']);
        $photo_extension = pathinfo($photo_basename, PATHINFO_EXTENSION);
        $safe_basename = pathinfo($photo_basename, PATHINFO_FILENAME);
        $safe_basename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $safe_basename); // Sanitize the basename

        $photo = $uploads_dir . '/' . $safe_basename . '.' . $photo_extension;
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
            $message = "Failed to upload file.";
        }
    }

    // Prepare SQL statement to update data in the database
    $stmt = $conn->prepare("UPDATE employes SET fullName=?, email=?, id_pole=?, id_departement=?, id_service=?, role=?, photo=?, username=?, nb_post=?, occupation=?, nb_bureau=?, corps=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("ssiiissssissi", $fullName, $email, $pole, $departement, $service, $role, $photo, $username, $nb_post, $occupation, $nb_bureau, $corps, $id);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            // Set session variable for success message
            $_SESSION['success_message'] = "Record updated successfully!";
            // Redirect to employee list page after successful update
            header('Location: liste_employes.php');
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Employee</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <?php include '../pages/nav.php'; ?>
    <link rel="stylesheet" href="../style/signup.css">
</head>
<body>
<?php include '../pages/side.php'; ?>
<?php include '../pages/navbar.php'; ?>
<div class="container main-container">
    <h2>Modifier Employee</h2>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])): ?>
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger" role="alert"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']); // Clear the success message
                ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <form action="modifier_emp.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($employee['fullName']); ?>" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($employee['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select class="form-control" id="role" name="role">
                <option value="rh" <?php if ($employee['role'] == 'rh') echo 'selected'; ?>>RH</option>
                <option value="directeur" <?php if ($employee['role'] == 'directeur') echo 'selected'; ?>>Directeur</option>
                <option value="chef de service" <?php if ($employee['role'] == 'chef de service') echo 'selected'; ?>>Chef de Service</option>
                <option value="employe" <?php if ($employee['role'] == 'employe') echo 'selected'; ?>>Employé</option>
            </select>
        </div>
        <div class="form-group" id="poleSelection" style="display:none;">
            <label for="pole">Pole:</label>
            <select class="form-control" id="pole" name="pole">
                <?php
                foreach ($poles as $pole) {
                    echo '<option value="' . $pole['id'] . '"' . ($employee['id_pole'] == $pole['id'] ? ' selected' : '') . '>' . $pole['nom'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group" id="departement" style="display:none;">
            <label for="departement">Département:</label>
            <select class="form-control" name="departement">
                <!-- Populate with departments from your database -->
                <?php
                $query = "SELECT id, nom FROM departements";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '"' . ($employee['id_departement'] == $row['id'] ? ' selected' : '') . '>' . $row['nom'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group" id="serviceSelection" style="display:none;">
            <label for="service">Service:</label>
            <select class="form-control" id="service" name="service">
                <?php
                $query = "SELECT id, nom FROM services";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '"' . ($employee['id_service'] == $row['id'] ? ' selected' : '') . '>' . $row['nom'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" class="form-control" id="photo" name="photo">
            <?php if ($employee['photo']): ?>
                <img src="<?php echo $employee['photo']; ?>" alt="Employee Photo" style="max-width: 100px; margin-top: 10px;">
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="nb_post">Nombre de post:</label>
            <input type="number" class="form-control" id="nb_post" name="nb_post" value="<?php echo htmlspecialchars($employee['nb_post']); ?>" required>
        </div>
        <div class="form-group">
            <label for="occupation">Occupation:</label>
            <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo htmlspecialchars($employee['occupation']); ?>" required>
        </div>
        <div class="form-group">
            <label for="nb_bureau">Nombre de bureau:</label>
            <input type="number" class="form-control" id="nb_bureau" name="nb_bureau" value="<?php echo htmlspecialchars($employee['nb_bureau']); ?>" required>
        </div>
        <div class="form-group">
            <label for="corps">Corps:</label>
            <input type="text" class="form-control" id="corps" name="corps" value="<?php echo htmlspecialchars($employee['corps']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Apply</button>
        </form>
</div>

<script>
function showSectionsBasedOnRole() {
    var role = document.getElementById('role').value;
    var serviceSelection = document.getElementById('serviceSelection');
    var departement = document.getElementById('departement');
    var poleSelection = document.getElementById('poleSelection');

    if (role === 'chef de service' || role === 'employe') {
        serviceSelection.style.display = 'block';
        departement.style.display = 'block';
        poleSelection.style.display = 'block';
    } else if (role === 'directeur') {
        serviceSelection.style.display = 'none';
        departement.style.display = 'block';
        poleSelection.style.display = 'block';
    } else {
        serviceSelection.style.display = 'none';
        departement.style.display = 'none';
        poleSelection.style.display = 'none';
    }
}

showSectionsBasedOnRole(); // Call this function on page load
document.getElementById('role').addEventListener('change', showSectionsBasedOnRole);
</script>
</body>
</html>
