<?php
require 'config.php';

// Récupération des informations en créant une table de jointure
try {
    $stmt = $pdo->query("
        SELECT c.id, c.date_collecte, c.lieu, b.nom
        FROM collectes c
        LEFT JOIN benevoles b ON c.id_benevole = b.id
        ORDER BY c.date_collecte DESC
    ");

    $collectes = $stmt->fetchAll();

    // Récupération des bénévoles admin pour l'afficher dans les cartes
    $query = $pdo->prepare("SELECT nom FROM benevoles WHERE role = 'admin' LIMIT 1");
    $query->execute();
    $admin = $query->fetch(PDO::FETCH_ASSOC);
    $adminNom = $admin ? htmlspecialchars($admin['nom']) : 'Aucun administrateur trouvé';

    // Requête sql qui récupère la totalité des déchets collectés
    $stmt_total = $pdo->query("SELECT SUM(quantite_kg) AS somme FROM dechets_collectes");
    $quantite_max = $stmt_total->fetch(PDO::FETCH_ASSOC);
    $result = $quantite_max['somme'];

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Collectes</title>
    <head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&family=Lora:wght@400;700&family=Montserrat:wght@300;400;700&family=Open+Sans:wght@300;400;700&family=Poppins:wght@300;400;700&family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;700&family=Nunito:wght@300;400;700&family=Merriweather:wght@300;400;700&family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
    </head>
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
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Liste des Collectes de Déchets</h1>
        <!-- Message de notification (ex: succès de suppression ou ajout) -->
        <?php if (isset($_GET['message'])): ?>
            <div class="bg-green-100 text-black-800 p-4 rounded-md mb-6">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>
        <!-- Cartes d'informations -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Nombre total de collectes -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-10">Total des Collectes</h3>
                <p class="text-2xl font-bold text-blue-600"><?= count($collectes) ?></p>
            </div>
              <!-- Total des déchets collectés en kg -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Total des déchets collectés en kg</h3>
                <p class="text-2xl font-bold text-blue-600"><?= round($result,2) ?></p>
            </div>
            <!-- Dernière collecte -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-black-800 mb-5">Dernière Collecte</h3>
                <p class="text-lg text-black-600"><?= htmlspecialchars($collectes[0]['lieu']) ?></p>
                <p class="text-lg text-black-600"><?= date('d/m/Y', strtotime($collectes[0]['date_collecte'])) ?></p>
            </div>
            <!-- Bénévole Responsable -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-black-800 mb-12">Bénévole Admin</h3>
                <p class="text-lg text-black-600"><?= $adminNom ?></p>
            </div>
        </div>
        <!-- Tableau des collectes -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Date</th>
                    <th class="py-3 px-4 text-left">Lieu</th>
                    <th class="py-3 px-4 text-left">Bénévole Responsable</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                    <th class="py-3 px-4 text-left">Déchets</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                <?php foreach ($collectes as $collecte) : ?>
                    <tr class="hover:bg-gray-100 transition duration-200">
                        <td class="py-3 px-4"><?= date('d/m/Y', strtotime($collecte['date_collecte'])) ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($collecte['lieu']) ?></td>
                        <td class="py-3 px-4">
                            <?= $collecte['nom'] ? htmlspecialchars($collecte['nom']) : 'Aucun bénévole' ?>
                        </td>
                        <td class="py-3 px-4 flex space-x-2">
                            <a href="collection_edit.php?id=<?= $collecte['id'] ?>" class="border border-solid border-orange-400 text-black px-5 py-2 hover:bg-pink-400 rounded-lg rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" role="bouton" aria-label="Modifier la collecte" >
                            ✏️ Modifier
                            </a>
                            <a href="collection_delete.php?id=<?= $collecte['id'] ?>" class="border border-solid border-purple-600 text-black px-4 py-2 hover:bg-blue-500 hover:text-white rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette collecte ?');">
                                🗑️ Supprimer
                            </a>
                        </td>
                        <td class="py-3 px-4"> 
                            <a href="waste_list.php?id=<?= $collecte['id'] ?>" class="bg-[#000000] hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"> 
                                &#43 Détails
                        </a>
                        </td>
                    </tr> 
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>