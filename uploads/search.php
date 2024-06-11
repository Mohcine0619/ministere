<?php
require_once '../backend/db.php';
session_start();

// Get individual search queries
$search_fullName = $_GET['search_fullName'] ?? '';
$search_email = $_GET['search_email'] ?? '';
$search_division = $_GET['search_division'] ?? '';
$search_service = $_GET['search_service'] ?? '';
$search_pole = $_GET['search_pole'] ?? '';
$search_occupation = $_GET['search_occupation'] ?? '';
$search_nb_post = $_GET['search_nb_post'] ?? '';
$search_nb_bureau = $_GET['search_nb_bureau'] ?? '';

// SQL query to search across multiple fields
$sql = "SELECT employes.*, departements.nom as division, services.nom as service, poles.nom as pole 
        FROM employes 
        LEFT JOIN departements ON employes.id_departement = departements.id
        LEFT JOIN services ON employes.id_service = services.id
        LEFT JOIN poles ON employes.id_pole = poles.id
        WHERE (employes.fullName LIKE ? OR ? = '')
          AND (departements.nom LIKE ? OR ? = '')
          AND (services.nom LIKE ? OR ? = '')
          AND (poles.nom LIKE ? OR ? = '')
          AND (employes.email LIKE ? OR ? = '')
          AND (employes.occupation LIKE ? OR ? = '')
          AND (employes.nb_post LIKE ? OR ? = '')
          AND (employes.nb_bureau LIKE ? OR ? = '')";

$stmt = $conn->prepare($sql);
$searchTerm_fullName = "%$search_fullName%";
$searchTerm_email = "%$search_email%";
$searchTerm_division = "%$search_division%";
$searchTerm_service = "%$search_service%";
$searchTerm_pole = "%$search_pole%";
$searchTerm_occupation = "%$search_occupation%";
$searchTerm_nb_post = "%$search_nb_post%";
$searchTerm_nb_bureau = "%$search_nb_bureau%";

// Bind the variables
$stmt->bind_param("ssssssssssssssss", $searchTerm_fullName, $search_fullName, $searchTerm_division, $search_division, $searchTerm_service, $search_service, $searchTerm_pole, $search_pole, $searchTerm_email, $search_email, $searchTerm_occupation, $search_occupation, $searchTerm_nb_post, $search_nb_post, $searchTerm_nb_bureau, $search_nb_bureau);
$stmt->execute();
$result = $stmt->get_result();
$utilisateurs = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface de recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Additional custom styles can be added here */
        .dark-mode {
            background-color: #333;
            color: #ccc;
        }
        .dark-mode input, .dark-mode label {
            background-color: #555;
            color: #ddd;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto p-8">
        <button onclick="toggleDarkMode()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Toggle Dark Mode</button>
        <div class="flex flex-wrap justify-center">
            <div class="w-full lg:w-1/2 my-6 pr-0 lg:pr-2">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" class="p-8 mt-6 mb-4 bg-white rounded shadow-md transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                    <!-- Input fields with transition effects -->
                    <?php
                        $fields = [
                            'fullName' => 'Name',
                            'email' => 'Email',
                            'division' => 'Division',
                            'service' => 'Service',
                            'pole' => 'Pole',
                            'occupation' => 'Occupation',
                            'nb_post' => 'Nb Post',
                            'nb_bureau' => 'Nb Bureau'
                        ];
                        foreach ($fields as $key => $label): ?>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="search_<?= $key ?>">
                                    <?= $label ?>
                                </label>
                                <input type="text" name="search_<?= $key ?>" placeholder="Search by <?= $label ?>..." value="<?php echo htmlspecialchars($_GET['search_' . $key] ?? ''); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline transition duration-500 ease-in-out">
                            </div>
                    <?php endforeach; ?>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:-translate-y-1">Search</button>
                </form>
            </div>
        </div>
        
        <!-- Display search results with animation -->
        <?php foreach ($utilisateurs as $user): ?>
            <div class="bg-white rounded shadow-md p-4 mt-4 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                <?php foreach ($fields as $key => $label): ?>
                    <p class="mb-2"><?= $label ?>: <?php echo htmlspecialchars($user[$key]); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function toggleDarkMode() {
            var body = document.body;
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
        }

        // Check for saved user preference, if any, on page load.
        window.onload = function() {
            if (localStorage.getItem('darkMode') === 'true') {
                document.body.classList.add('dark-mode');
            }
        }
    </script>
</body>
</html>
