<?php
session_start(); // Start the session
require_once '../backend/db.php';

// Fetch poles, departments, services, and corps from the database
$poles = [];
$departments = [];
$services = [];
$corps = [];

// Fetching poles
$poleResult = $conn->query("SELECT id, nom FROM poles");
if ($poleResult) {
    while ($poleRow = $poleResult->fetch_assoc()) {
        $poles[] = $poleRow;
    }
    $poleResult->free();
}

// Fetching departments with nom_pole
$deptResult = $conn->query("SELECT id, nom, nom_pole FROM departement");
if ($deptResult) {
    while ($deptRow = $deptResult->fetch_assoc()) {
        $departments[] = $deptRow;
    }
    $deptResult->free();
}

// Fetch services with id_departement
$services = [];
$serviceResult = $conn->query("SELECT id, nom, id_departement FROM services");
if ($serviceResult) {
    while ($serviceRow = $serviceResult->fetch_assoc()) {
        $services[] = $serviceRow;
    }
    $serviceResult->free();
}

// Fetch corps

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Collect and sanitize input data
    $fullName = htmlspecialchars($_POST['fullName']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $tel = htmlspecialchars($_POST['tel']);
    $matricule = htmlspecialchars($_POST['matricule']);
    $grade = htmlspecialchars($_POST['grade']);
    $fonction = htmlspecialchars($_POST['fonction']);
    $role = htmlspecialchars($_POST['role']); // Collect role
    $corps = htmlspecialchars($_POST['corps']); // Collect corps
    $nb_post = intval($_POST['nb_post']);
    $nb_bureau = intval($_POST['nb_bureau']);
    $pole = isset($_POST['pole']) && $_POST['pole'] !== '' ? intval($_POST['pole']) : null;
    $departement = isset($_POST['departement']) && $_POST['departement'] !== '' ? intval($_POST['departement']) : null;
    $service = isset($_POST['service']) && $_POST['service'] !== '' ? intval($_POST['service']) : null;
    $gender = htmlspecialchars($_POST['gender']); // Collect gender

    $photoPath = null; // Default value

    // Handling file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadDir = '../uploads/';
        $fileName = time() . basename($_FILES['photo']['name']); // Using time() to prefix filenames to make them unique
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $photoPath = $uploadFile;
            echo "File is uploaded successfully. Path: $photoPath";
        } else {
            $message = "Error uploading file.";
            echo $message;
        }
    }

    // Prepare SQL statement to insert data into the database, including photo path and gender
    $stmt = $conn->prepare("INSERT INTO employe (matricule, fullName, username, tel, email, role, corps, fonction, grade, nb_post, nb_bureau, pole_id, departement_id, service_id, photo, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("sssssssssiiiiiss", $matricule, $fullName, $username, $tel, $email, $role, $corps, $fonction, $grade, $nb_post, $nb_bureau, $pole, $departement, $service, $photoPath, $gender);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        $_SESSION['success_message'] = "New record created successfully!";
        header('Location: liste_employes.php'); // Redirect to the employee list page
        exit();
    } else {
        $message = "Error: " . $stmt->error;
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
    <title>Signup Form</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../style/signup.css">
</head>
<body>
<?php include '../pages/nav.php'; ?>
<?php include '../pages/side.php'; ?>
<?php include '../pages/navbar.php'; ?>
<div class="container main-container">
    <h2>Signup Form</h2>
    <?php
    if (!empty($message)) {
        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
    }
    ?>
    <form action="add_employee.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" class="form-control" id="fullName" name="fullName" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="tel">Tel:</label>
            <input type="tel" class="form-control" id="tel" name="tel" required>
        </div>
        <div class="form-group">
            <label for="matricule">Matricule:</label>
            <input type="text" class="form-control" id="matricule" name="matricule" required>
        </div>
        <div class="form-group">
            <label for="grade">Grade:</label>
            <input type="text" class="form-control" id="grade" name="grade" required>
        </div>
        <div class="form-group">
            <label for="corps">Corps:</label>
            <select class="form-control" id="corps" name="corps" onchange="updateFonctionOptions()">
                <option value="">Select Corps</option>
                <option value="Corps1">Corps1</option>
                <option value="Corps2">Corps2</option>
                <!-- Add more corps options as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="fonction">Fonction:</label>
            <select class="form-control" id="fonction" name="fonction" required>
                <option value="">Select Fonction</option>
                <option value="Fonction1A">Fonction1A</option>
                <option value="Fonction1B">Fonction1B</option>
                <option value="Fonction1C">Fonction1C</option>
                <option value="Fonction1D">Fonction1D</option>
                <option value="Fonction2A">Fonction2A</option>
                <option value="Fonction2B">Fonction2B</option>
                <option value="Fonction2C">Fonction2C</option>
                <option value="Fonction2D">Fonction2D</option>
            </select>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select class="form-control" id="role" name="role">
                <option value="rh">RH</option>
                <option value="directeur">Directeur</option>
                <option value="chef de service">Chef de Service</option>
                <option value="employe">Employé</option>
            </select>
        </div>
        <div class="form-group" id="poleSelection">
            <label for="pole">Pole:</label>
            <select class="form-control" id="pole" name="pole">
                <?php
                foreach ($poles as $pole) {
                    echo '<option value="' . $pole['id'] . '">' . $pole['nom'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group" id="departement">
            <label for="departement">Département:</label>
            <select class="form-control" id="departement" name="departement">
                <?php
                foreach ($departments as $department) {
                    echo '<option value="' . $department['id'] . '">' . $department['nom'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group" id="serviceSelection">
            <label for="service">Service:</label>
            <select class="form-control" id="service" name="service">
                <?php
                foreach ($services as $service) {
                    echo '<option value="' . $service['id'] . '">' . $service['nom'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" class="form-control" id="photo" name="photo" required>
        </div>
        <div class="form-group">
            <label for="nb_post">Nombre de post:</label>
            <input type="number" class="form-control" id="nb_post" name="nb_post" required>
        </div>
        <div class="form-group">
            <label for="nb_bureau">Nombre de bureau:</label>
            <input type="number" class="form-control" id="nb_bureau" name="nb_bureau" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" value="SignUp">SignUp</button>
    </form>
</div>
</body>
</html>

<script>
function updateFonctionOptions() {
    var corps = document.getElementById('corps').value;
    var fonctionSelect = document.getElementById('fonction');
    fonctionSelect.innerHTML = ''; // Clear existing options

    if (corps === 'Corps1') {
        fonctionSelect.innerHTML += '<option value="Fonction1A">Fonction1A</option><option value="Fonction1B">Fonction1B</option>';
        fonctionSelect.innerHTML += '<option value="Fonction1C">Fonction1C</option><option value="Fonction1D">Fonction1D</option>';
    } else if (corps === 'Corps2') {
        fonctionSelect.innerHTML += '<option value="Fonction2A">Fonction2A</option><option value="Fonction2B">Fonction2B</option>';
        fonctionSelect.innerHTML += '<option value="Fonction2C">Fonction2C</option><option value="Fonction2D">Fonction2D</option>';
    }
    // Add more conditions and options based on different corps
}
</script>