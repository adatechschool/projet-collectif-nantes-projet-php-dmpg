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
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
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
                    <label class="block text-sm font-medium text-gray-700" for ="nom-input">Nom :</label>
                    <input class="w-full p-2 border border-gray-300 rounded-lg" value="<?= htmlspecialchars($benevole['nom']) ?>"
                    required="" name="nom" type="text" id="nom-input-" aria-describedby="nom-" placeholder="Saisir le nom du benevole" 
                    title="NOM DU BENEVOLE" aria-label="NOM DU BENEVOLE">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for ="email-input">Email :</label>
                    <input class="w-full p-2 border border-gray-300 rounded-lg" name="email" value="<?= htmlspecialchars($benevole['email']) ?>" 
                    required="" type="text" id="email-input-" aria-describedby="email" placeholder="Saisir votre email" 
                    aria-label="email">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="mdp-input">Mot de passe :</label>
                    <input class="w-full p-2 border border-gray-300 rounded-lg" type="text" name="mot_de_passe" value="<?= htmlspecialchars($benevole['mot_de_passe']) ?>" 
                    required=""  id="mdp-input-" aria-describedby="mot depasse" placeholder="Saisir votre mot de passe" aria-label="mot de passe">
                    
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="volunteer_list.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">Annuler</a>
                    <button type="submit" class="border border-solid border-cyan-500 border-blue-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg shadow ai-style-change-1" 
                    aria-label="Ajouter un nouvel élément">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>