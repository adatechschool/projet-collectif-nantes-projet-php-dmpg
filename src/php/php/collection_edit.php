<?php
require 'config.php';

// Vérifier si un ID de collecte est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: collection_list.php");
    exit;
}

$id = $_GET['id'];

// Récupérer les informations de la collecte
$stmt = $pdo->prepare("SELECT * FROM collectes WHERE id = ?");
$stmt->execute([$id]);
$collecte = $stmt->fetch();

// Récupérer les types de déchets 
$stmt_dechets_collectes = $pdo->prepare("SELECT type_dechet FROM dechets_collectes ORDER BY type_dechet");
$stmt_dechets_collectes->execute();
$dechets_collectes = $stmt_dechets_collectes->fetchAll();

if (!$collecte) {
    header("Location: collection_list.php");
    exit;
}

// Récupérer la liste des bénévoles
$stmt_benevoles = $pdo->prepare("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

// Mettre à jour la collecte
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"];
    $lieu = $_POST["lieu"];
    $benevole_id = $_POST["benevole"]; // Récupérer l'ID du bénévole sélectionné
    $type_dechet=$_POST['type_dechet'];
    $id_collecte=$id;
    $quantite_kg =$_POST['quantite_kg'];
   
    
    $stmt = $pdo->prepare("UPDATE collectes SET date_collecte = ?, lieu = ?, id_benevole = ? WHERE id = ?");
    $stmt->execute([$date, $lieu, $benevole_id, $id]);
    
    $stmt_dechets_collectes = $pdo->prepare("INSERT INTO dechets_collectes (type_dechet,id_collecte,quantite_kg) VALUES (?,?,?)");
    $stmt_dechets_collectes->execute([$type_dechet, $id_collecte, $quantite_kg]);
    header("Location: collection_list.php");
    
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation --> 
    <?php include 'header.php'; ?>
    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Modifier une collecte</h1>
        <!-- Formulaire -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date :</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($collecte['date_collecte']) ?>" required
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu :</label>
                    <input type="text" name="lieu" value="<?= htmlspecialchars($collecte['lieu']) ?>" required
                           class="w-full p-2 border border-gray-300 rounded-lg">
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
                   <label class="block text-sm  font-medium px-2 text-gray-700">Quantité(en kg) :</label>
                     <input class="w-full p-2 border border-gray-300 rounded-lg" name="quantite_kg" type="number" step="0.001">
               </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bénévole :</label>
                    <select name="benevole" required class="w-full p-2 border border-gray-600 rounded-lg">
                        <option value="" disabled selected>Sélectionnez un·e bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] == $collecte['id_benevole'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Annuler</a>
                    <button type="submit" class="bg-cyan-200 text-white px-4 py-2 rounded-lg">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>