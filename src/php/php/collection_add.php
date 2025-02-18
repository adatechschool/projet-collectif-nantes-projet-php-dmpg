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
    $stmt_dechets_collectes->execute([$type_dechet, $quantite_kg]);

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
                    <input type="date" name="date" required
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <!-- Lieu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu :</label>
                    <input type="text" name="lieu" required
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type de déchet :</label>
                    <select name="type_dechet" id="type_dechet_container" class="w-full p-2 border border-gray-300 rounded-lg">>
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
                     <input class="w-full p-2 border border-gray-300 rounded-lg" name="quantite_kg" type="number" step="0.001">
               </div>
                <!-- Bénévole responsable -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bénévole Responsable :</label>
                    <select name="benevole" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] ==  'selected' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">Annuler</a>
                    <button type="submit" class="bg-cyan-200 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow">
                        ➕ Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>