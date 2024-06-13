<?php
session_start();
require_once '../backend/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch poles, departments, and services
$poles = [];
$departments = [];
$services = [];

// Fetch poles
$result = $conn->query("SELECT id, nom FROM poles");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $poles[] = $row;
    }
    $result->free();
}

// Fetch departments
$result = $conn->query("SELECT id, nom FROM departement");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
    $result->free();
}

// Fetch services
// Fetch services
$result = $conn->query("SELECT id, nom, id_departement FROM services");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    $result->free();
}

$employee = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM employe WHERE id = $id");
    if ($result) {
        $employee = $result->fetch_assoc();
        $result->free();
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Process form data
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    $departement_id = isset($_POST['departement']) ? intval($_POST['departement']) : null;
    $service_id = isset($_POST['service']) ? intval($_POST['service']) : null;
    $pole_id = isset($_POST['pole']) ? intval($_POST['pole']) : null;

    // Update database
    $stmt = $conn->prepare("UPDATE employe SET fullName=?, email=?, role=?, departement_id=?, service_id=?, pole_id=? WHERE id=?");
    $stmt->bind_param("sssiiii", $fullName, $email, $role, $departement_id, $service_id, $pole_id, $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Record updated successfully!";
        header('Location: liste_employes.php');
        exit();
    } else {
        $message = "Error updating record: " . $stmt->error;
    }
    $stmt->close();
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
    <div class="form-group">
        <label for="nb_post">Nombre de post:</label>
        <input type="number" class="form-control" id="nb_post" name="nb_post" value="<?php echo htmlspecialchars($employee['nb_post']); ?>" required>
    </div>
    <div class="form-group">
        <label for="tel">Téléphone:</label>
        <input type="text" class="form-control" id="tel" name="tel" value="<?php echo htmlspecialchars($employee['tel']); ?>" required>
    </div>
    <div class="form-group">
        <label for="grade">Grade:</label>
        <input type="text" class="form-control" id="grade" name="grade" value="<?php echo htmlspecialchars($employee['grade']); ?>" required>
    </div>

    <div class="form-group">
        <label for="fonction">Fonction:</label>
        <input type="text" class="form-control" id="fonction" name="fonction" value="<?php echo htmlspecialchars($employee['fonction']); ?>" required>
    </div>

    <div class="form-group">
        <label for="nb_bureau">Nombre de bureau:</label>
        <input type="number" class="form-control" id="nb_bureau" name="nb_bureau" value="<?php echo htmlspecialchars($employee['nb_bureau']); ?>" required>
    </div>
    <div class="form-group">
        <label for="corps">Corps:</label>
        <input type="text" class="form-control" id="corps" name="corps" value="<?php echo htmlspecialchars($employee['corps']); ?>" required>
    </div>
    <div class="form-group">
        <label for="photo">Photo:</label>
        <input type="file" class="form-control" id="photo" name="photo">
        <?php if ($employee['photo']): ?>
            <img src="<?php echo $employee['photo']; ?>" alt="Employee Photo" style="max-width: 100px; margin-top: 10px;">
        <?php endif; ?>
    </div>
    <div class="form-group">
    <label for="pole">Pole:</label>
    <select class="form-control" id="pole" name="pole" onchange="filterDepartements()">
        <option value="">Select Pole</option>
        <?php foreach ($poles as $pole): ?>
            <option value="<?= $pole['id'] ?>" <?= $employee['pole_id'] == $pole['id'] ? 'selected' : '' ?>><?= $pole['nom'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="form-group" id="departementSelection" style="display: none;">
    <label for="departement">Département:</label>
    <select class="form-control" id="departement" name="departement" onchange="filterServices()">
        <option value="">Select Department</option>
        <?php foreach ($departments as $department): ?>
            <?php if ($department['nom_pole'] == $employee['pole_nom']): ?>
                <option value="<?= $department['id'] ?>" <?= $employee['departement_id'] == $department['id'] ? 'selected' : '' ?>>
                    <?= $department['nom'] ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
</div>
<script>
    function filterDepartements() {
        var pole = document.getElementById('pole').value;
        var departementSelection = document.getElementById('departementSelection');
        if (pole) {
            departementSelection.style.display = 'block';
        } else {
            departementSelection.style.display = 'none';
        }
    }
    filterDepartements(); // Call this function on page load
    document.getElementById('pole').addEventListener('change', filterDepartements);
</script>
<div class="form-group" id="serviceSelection" style="display: none;">
    <label for="service">Service:</label>
    <select class="form-control" id="service" name="service">
        <option value="">Select Service</option>
        <?php foreach ($services as $service): ?>
            <?php if ($service['id_departement'] == $employee['departement_id']): ?>
                <option value="<?= $service['id'] ?>" <?= $employee['service_id'] == $service['id'] ? 'selected' : '' ?>>
                    <?= $service['nom'] ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
</div>
<script>
    function filterServices() {
        var departement = document.getElementById('departement').value;
        var serviceSelection = document.getElementById('serviceSelection');
        if (departement) {
            serviceSelection.style.display = 'block';
        } else {
            serviceSelection.style.display = 'none';
        }
    }
    filterServices(); // Call this function on page load
    document.getElementById('departement').addEventListener('change', filterServices);
</script>
    <button type="submit" class="btn btn-primary" name="submit">Update</button>
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
