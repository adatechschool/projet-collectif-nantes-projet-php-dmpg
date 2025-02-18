<?php
require 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Récupérer la liste des bénévoles
$stmt_benevoles = $pdo->query("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"];
    $lieu = $_POST["lieu"];
    $benevole_id = $_POST["benevole"];  // ID du bénévole choisi, modifié ici pour correspondre au formulaire
    $type_dechet = $_POST['type_dechet'];
    $quantite_kg = $_POST['quantite_kg'];

    // Insérer la collecte avec le bénévole sélectionné
    $stmt = $pdo->prepare("INSERT INTO collectes (date_collecte, lieu, id_benevole) VALUES (?, ?, ?)");
   
    if (!$stmt->execute([$date, $lieu, $benevole_id])) {
        die('Erreur lors de l\'insertion dans la base de données.');
    }

    // Récupérer le dernier id entré dans la table collectes
    $stmt_collectes = $pdo->query("SELECT id FROM collectes WHERE id = LAST_INSERT_ID()");
    $result = $stmt_collectes->fetch(PDO::FETCH_ASSOC);
    $id_collecte = $result['id'];
    

    // Ajout des infos dans la table dechets_collectes + l'id de la dernière collecte pour les faire correspondre
    $stmt_dechets_collectes = $pdo->prepare("INSERT INTO dechets_collectes (id_collecte, type_dechet, quantite_kg) VALUES ($id_collecte, ?, ?)");
    // $stmt_dechets_collectes->execute([$type_dechet, $quantite_kg]);

    if (!$stmt_dechets_collectes->execute([$type_dechet, $quantite_kg])) {
        die('Erreur lors de l\'insertion dans la base de données.');
    }

    header("Location: collection_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation --> 
    <?php include 'header.php'; ?>
    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Ajouter une collecte</h1>
        <!-- Formulaire -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date :</label>
                    <input type="date" name="date" required="" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" id="date-input" aria-describedby="date-description" placeholder="Enter the date " title="Date input field" aria-label="Date">
                </div>
                <!-- Lieu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="lieu-input-uho9kt4">Lieu :</label>
                    <input type="text" name="lieu" required="" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" id="lieu-input" aria-describedby="lieu-description" placeholder="Choisir le lieu de la collecte" title="Location input field" aria-label="Location">
                    <div id="lieu-description" style="" class="ai-style-change-2"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type de déchet :</label>
                    <select name="type_dechet" id="type_dechet_container" class="w-full p-2 border border-gray-300 rounded-lg" aria-describedby="type-dechet-description" title="Type of waste select field" aria-label="Type of waste">
                        <option value="">
                        Sélectionnez un type de déchet :
                        </option>
                        <option value="organique">organique</option>
                        <option value="plastique">plastique</option>
                        <option value="métal">métal</option>
                        <option value="verre">verre</option>
                        <option value="papier">papier</option>
                        <option value="carton">carton</option>
                        <option value="électronique">électronique</option>
                    </select>    
                </div>
                <div>
                   <label class="block text-sm font-medium text-gray-700">Quantité(en kg) :</label>
                   <input class="w-full p-2 border border-gray-300 rounded-lg" name="quantite_kg" type="number" step="0.001" id="quantite-kg-input-tgf4cg5" aria-describedby="quantite-kg-description" placeholder="Enter quantity in kg" title="Quantity in kilograms input field" aria-label="Quantity in kilograms">
               </div>
                <!-- Bénévole responsable -->
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="benevole-select">Bénévole Responsable :</label>
                    <select name="benevole" required="" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" id="benevole-select" aria-describedby="benevole-description" title="Responsible volunteer select field" aria-label="Responsible volunteer">
                        <option value="">Sélectionner un bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] ==  'selected' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                        <div id="benevole-description" style="" class="ai-style-change-1">Please select the responsible volunteer.</div>
                    </select>
                </div>
                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">Annuler</a>
                    <button type="submit" class="border border-solid border-cyan-500 border-blue-500 hover:bg-cyan-600 text-black px-4 py-2 rounded-lg shadow ai-style-change-1" aria-label="Ajouter un nouvel élément">
                        ➕ Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>