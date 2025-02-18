<?php
require 'config.php';

    try {
        $stmt = $pdo->query('
        SELECT b.nom, ROUND(SUM(d.quantite_kg),2) as total_dechets FROM benevoles b
        LEFT JOIN collectes c ON b.id = c.id_benevole 
        LEFT JOIN dechets_collectes d ON c.id = d.id_collecte 
        GROUP BY b.id, b.nom 
        ORDER BY total_dechets DESC
        LIMIT 1;
        ');

        $benevole_info_max = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt_ville = $pdo->query('
        SELECT c.lieu, SUM(d.quantite_kg) as total_dechets FROM collectes c
        LEFT JOIN dechets_collectes d ON c.id = d.id_collecte 
        GROUP BY c.id, c.lieu
        ORDER BY total_dechets DESC
        LIMIT 1;
        ');

        $ville_info_max = $stmt_ville->fetch(PDO::FETCH_ASSOC);

        $stmt_type = $pdo->query('
        SELECT d.type_dechet, ROUND(SUM(d.quantite_kg),2) as total_dechets FROM dechets_collectes d
        GROUP BY d.type_dechet
        ORDER BY total_dechets DESC
        LIMIT 1;
        ');

        $type_info_max = $stmt_type->fetch(PDO::FETCH_ASSOC);


        // Récupération des bénévoles admin pour l'afficher dans les cartes
        $query = $pdo->prepare("SELECT nom, email FROM benevoles WHERE role = 'admin' LIMIT 1");
        $query->execute();
        $admin = $query->fetch(PDO::FETCH_ASSOC);
        $adminNom = $admin ? htmlspecialchars($admin['nom']) : 'Aucun administrateur trouvé';


    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
        exit;
    }    

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
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
        <h1 class="text-5xl font-bold text-blue-800 mb-12">Bienvenue ! (nom de l'asso ?)</h1>
        <h2 class="text-4xl font-bold text-blue-800 mb-8">Nos records :</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Nombre total de collectes -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Bénévole ayant ramassé le plus de déchets :</h3>
                    <p class="text-3xl font-bold text-blue-600"><?=$benevole_info_max['nom']?></p>
                    <p class="text-3xl font-bold text-blue-600 mt-3"><?=$benevole_info_max['total_dechets']?> kg</p>
                </div>
                <!-- Total des déchets collectés en kg -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Ville que l'on a le plus nettoyée :</h3>
                    <p class="text-3xl font-bold text-blue-600"><?=$ville_info_max['lieu']?></p>
                </div>
                <!-- Dernière collecte -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Type de déchet le plus ramassé :</h3>
                    <p class="text-3xl font-bold text-blue-600"><?=$type_info_max['type_dechet']?></p>
                    <p class="text-3xl font-bold text-blue-600 mt-3"><?=$type_info_max['total_dechets']?> kg</p>
                </div>
        </div>
        <!-- Bénévole Responsable -->
        <div class="mt-20">
        <div class="bg-white p-6 rounded-lg shadow-lg ">
            <h3 class="text-xl font-semibold text-gray-800 mb-3"><i class="fa-regular fa-paper-plane mr-3"></i>Besoin d'aide ? Vous pouvez contacter</h3>
            <p class="text-lg text-gray-800"><?=$adminNom?> en lui écrivant à  <?=$admin['email']?></p>
        </div>
        </div>
    </div>
</div>
</body>
</html>