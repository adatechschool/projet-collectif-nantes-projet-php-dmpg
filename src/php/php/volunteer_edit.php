<?php
require 'config.php';

// Vérifier si un ID de bénévole est fourni, si non, on retroune à la liste des bénévoles
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: volunteer_list.php");
    exit;
}

$id = $_GET['id'];

// Récupérer les informations du bénévole
$stmt = $pdo->prepare("SELECT * FROM benevoles WHERE id = ?");
$stmt->execute([$id]);
$benevole = $stmt->fetch();

// Si aucune donnée n'a été récupérée, on retourne à la liste des bénévoles
if (!$benevole) {
    header("Location: volunteer_list.php");
    exit;
}

// Mettre à jour les infos du bénévole
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];

    $stmt = $pdo->prepare("UPDATE benevoles SET nom = ?, email = ?, mot_de_passe = ? WHERE id = ?");
    $stmt->execute([$nom, $email, $mot_de_passe, $id]);

    header("Location: volunteer_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier vos informations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation --> 
    <?php include 'header.php'; ?>
    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Modifier les informations</h1>
        <!-- Formulaire -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom :</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($benevole['nom']) ?>" required
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email :</label>
                    <input type="text" name="email" value="<?= htmlspecialchars($benevole['email']) ?>" required
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mot de passe :</label>
                    <input type="text" name="mot_de_passe" value="<?= htmlspecialchars($benevole['mot_de_passe']) ?>" required
                    class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="volunteer_list.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Annuler</a>
                    <button type="submit" class="bg-cyan-200 text-white px-4 py-2 rounded-lg">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>