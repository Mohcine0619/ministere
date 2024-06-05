<?php
session_start(); // Start the session
require_once '../backend/db.php';

// Fetch poles from the database
$poles = [];
$result = $conn->query("SELECT id, nom FROM poles");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $poles[] = $row;
    }
    $result->free();
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Collect and sanitize input data
    $fullName = htmlspecialchars($_POST['fullName']);
    $username = htmlspecialchars($_POST['username']); // Collecting the username
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = htmlspecialchars($_POST['role']);
    $departement = isset($_POST['departement']) ? htmlspecialchars($_POST['departement']) : null;
    $service = isset($_POST['service']) ? htmlspecialchars($_POST['service']) : null;
    $nb_post = htmlspecialchars($_POST['nb_post']);
    $occupation = htmlspecialchars($_POST['occupation']);
    $nb_bureau = intval($_POST['nb_bureau']); // Ensure age is treated as an integer
    $corps = htmlspecialchars($_POST['corps']);
    $pole = isset($_POST['pole']) ? htmlspecialchars($_POST['pole']) : null;

    // Check if passwords match and length requirement
    if ($password === $cpassword && strlen($password) >= 6) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle file upload
        $photo = null;
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

        // Prepare SQL statement to insert data into the database
        $stmt = $conn->prepare("INSERT INTO employes (fullName, email, id_pole, id_departement, id_service, role, photo, password, username, nb_post, occupation, nb_bureau, corps) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssiiissssisis", $fullName, $email, $pole, $departement, $service, $role, $photo, $hashed_password, $username, $nb_post, $occupation, $nb_bureau, $corps);

            // Execute the statement
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                // Set session variable for success message
                $_SESSION['success_message'] = "New record created successfully!";
                // Set session variables for username and role
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                // Redirect to login page after successful signup
                header('Location: login.php');
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error preparing statement: " . $conn->error;
        }
    } else {
        if ($password !== $cpassword) {
            $message = "Passwords do not match.";
        } else if (strlen($password) < 6) {
            $message = "Password must be at least 6 characters long.";
        }
    }
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
<div class="container mt-5">
    <h2>Signup Form</h2>
    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<div class="alert alert-success" role="alert">New record created successfully!</div>';
    } elseif (!empty($message)) {
        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
    }
    ?>
    <div id="error-message" class="alert alert-danger" style="display: none;"></div> <!-- Placeholder for error message -->
    <form action="signup.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
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
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="cpassword">Confirm Password:</label>
            <input type="password" class="form-control" id="cpassword" name="cpassword" required>
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
        <div class="form-group" id="poleSelection" style="display:none;">
            <label for="pole">Pole:</label>
            <select class="form-control" id="pole" name="pole">
                <?php
                foreach ($poles as $pole) {
                    echo '<option value="' . $pole['id'] . '">' . $pole['nom'] . '</option>';
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
                        echo '<option value="' . $row['id'] . '">' . $row['nom'] . '</option>';
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
                        echo '<option value="' . $row['id'] . '">' . $row['nom'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" class="form-control" id="photo" name="photo" accept=".jpg, .jpeg, .png">
        </div>
        <div class="form-group">
            <label for="nb_post">Nombre de post:</label>
            <input type="number" class="form-control" id="nb_post" name="nb_post" required>
        </div>
        <div class="form-group">
            <label for="occupation">Occupation:</label>
            <input type="text" class="form-control" id="occupation" name="occupation" required>
        </div>
        <div class="form-group">
            <label for="nb_bureau">Nombre de bureau:</label>
            <input type="number" class="form-control" id="nb_bureau" name="nb_bureau" required>
        </div>
        <div class="form-group">
            <label for="corps">Corps:</label>
            <textarea class="form-control" id="corps" name="corps" required></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" value="SignUp">SignUp</button>
        <a href="login.php">Déjà inscrit ?</a>
        <span id="message" name="message"><?php echo $message; ?></span>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    showSectionsBasedOnRole(); // Call this function on page load
    document.getElementById('role').addEventListener('change', showSectionsBasedOnRole);
});

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

function validateForm() {
    var password = document.getElementById('password').value;
    var cpassword = document.getElementById('cpassword').value;
    var errorMessage = document.getElementById('error-message');

    if (password !== cpassword) {
        errorMessage.textContent = 'Passwords do not match.';
        errorMessage.style.display = 'block';
        return false; // Prevent form submission
    } else if (password.length < 6) {
        errorMessage.textContent = 'Password must be at least 6 characters long.';
        errorMessage.style.display = 'block';
        return false; // Prevent form submission
    }

    errorMessage.style.display = 'none';
    return true; // Allow form submission
}
</script>
</body>
</html>
