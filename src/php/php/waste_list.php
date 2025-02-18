<?php
require 'config.php';

$id = $_GET['id'];

// Création d'une table de jointure pour faire le lien entre les déchets collectés et les collectes
try {
    $stmt = $pdo->query("
        SELECT d.type_dechet, d.quantite_kg, c.lieu, c.date_collecte, b.nom
        FROM collectes c
        INNER JOIN dechets_collectes d ON c.id = d.id_collecte
        INNER JOIN benevoles b ON c.id_benevole = b.id
        WHERE c.id = $id
        ORDER BY c.date_collecte DESC;
    ");

    $query = $pdo->prepare("SELECT id FROM collectes WHERE id = ?");
    $query->execute([$id]);

    $query = $pdo->prepare("SELECT nom FROM benevoles WHERE role = 'admin' LIMIT 1");
    $query->execute();

    $admin = $query->fetch(PDO::FETCH_ASSOC);
    $adminNom = $admin ? htmlspecialchars($admin['nom']) : 'Aucun administrateur trouvé';

    $stmt_total = $pdo->query("SELECT SUM(quantite_kg) AS somme FROM dechets_collectes WHERE id_collecte = $id");
    $quantite_max = $stmt_total->fetch(PDO::FETCH_ASSOC);
    $result = $quantite_max['somme'];

    $collectes = $stmt->fetchAll();
    $dechets_collectes = $stmt->fetchAll();


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
    <title>Liste des déchets de la collecte</title>
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
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Détail de la collecte</h1>
        <!-- Message de notification (ex: succès de suppression ou ajout) -->
        <?php if (isset($_GET['message'])): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>
        <!-- Cartes d'informations -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Nombre total de collectes -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Total des déchets en KG</h3>
                <p class="text-3xl font-bold text-blue-600"><?= round($result,2) ?></p>
            </div>
            <!-- Bénévole Responsable -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Bénévole Admin</h3>
                <p class="text-lg text-gray-600"><?= $adminNom ?></p>
            </div>
        </div>
        <!-- Tableau des collectes -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Date</th>
                    <th class="py-3 px-4 text-left">Lieu</th>
                    <th class="py-3 px-4 text-left">Bénévoles</th>
                    <th class="py-3 px-4 text-left">Quantité en kg</th>
                    <th class="py-3 px-4 text-left">Type de déchets</th>
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
                        <td class="py-3 px-4">
                            <?=$collecte['quantite_kg'] ? htmlspecialchars($collecte['quantite_kg']) : 'Aucune quantité' ?>
                        </td>
                        <td class="py-3 px-4">
                            <?= $collecte['type_dechet'] ? htmlspecialchars($collecte['type_dechet']) : 'Déchet non précisé' ?>
                        </td>
                    </tr>
                  <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>