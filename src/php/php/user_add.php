
<?php
require 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Récupérer la liste des bénévoles
$stmt_benevoles = $pdo->query("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $role = $_POST["role"];

    // Ajouter un bénévole sélectionné
    $stmt = $pdo->prepare("INSERT INTO benevoles (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");

    if (!$stmt->execute([$nom, $email, $mot_de_passe, $role])) {
        die('Erreur lors de l\'insertion dans la base de données.');
    }

    header("Location: volunteer_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bénévole</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation --> 
    <?php include 'header.php'; ?>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Ajouter un Bénévole</h1>

        <!-- Formulaire d'ajout -->
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
            <form action="user_add.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nom</label>
                    <input type="text" name="nom"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nom du bénévole" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Email du bénévole" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Mot de passe</label>
                    <input type="password" name="mot_de_passe"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Mot de passe" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Rôle</label>
                    <select name="role"
                            class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="participant">Participant</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-cyan-200 hover:bg-cyan-600 text-white py-3 rounded-lg shadow-md font-semibold">
                        Ajouter le bénévole
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

