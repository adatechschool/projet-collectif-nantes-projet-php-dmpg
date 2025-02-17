<?php
require 'config.php';

// Création de la table de jointure
try {
    $stmt = $pdo->query("
    SELECT b.nom, b.email, b.role, b.id,
    SUM(d.quantite_kg) as total_dechets 
    FROM benevoles b
    LEFT JOIN collectes c ON b.id = c.id_benevole 
    LEFT JOIN dechets_collectes d ON c.id = d.id_collecte 
    GROUP BY b.id, b.nom 
    ORDER BY total_dechets DESC;
    ");

  $benevoles = $stmt->fetchAll();



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
    <title>Liste des Bénévoles</title>
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
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Liste des Bénévoles</h1>

        <!-- Tableau des bénévoles -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Nom</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Rôle</th> 
                    <th class="py-3 px-4 text-left">Quantité de déchets ramassés en KG</th> 
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
                </thead>
                <?php foreach ($benevoles as $benevole) : ?>
                    <tr class="hover:bg-gray-100 transition duration-200">
                        <td class="py-3 px-4"><?= htmlspecialchars($benevole['nom']) ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($benevole['email']) ?></td> 
                        <td class="py-3 px-4"><?= htmlspecialchars($benevole['role'])  ?></td> 
                     
                        <td class="py-3 px-4"><?= round($benevole["total_dechets"],2) ?></td>
                        <td class="py-3 px-4 flex space-x-2">
                            <a href="volunteer_edit.php?id=<?= $benevole['id'] ?>" class="bg-cyan-200 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                                ✏️ Modifier
                            </a>
                            <a href="volunteer_delete.php?id=<?= $benevole['id'] ?>" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce benevole ?');">
                                🗑️ Supprimer
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

