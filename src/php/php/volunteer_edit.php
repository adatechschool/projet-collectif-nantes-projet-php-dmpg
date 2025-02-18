<?php
// Inclusion du fichier de configuration contenant les 
// informations de connexion à la base de données  
require 'config.php';

// Vérifier si un ID de bénévole est fourni dand l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
  // Si l'ID n'est pas présent, rediriger vers la liste 
  // des bénévoles     
    header("Location: volunteer_list.php");
    exit; // Arrêter l'exécution du script après la redirection
}
// Récupération de l'ID du bénévole depuis l'URL 
$id = $_GET['id'];

// Préparer une requête pour récupérer les informations du bénévole
//  correspondant à l'ID  
$stmt = $pdo->prepare("SELECT * FROM benevoles WHERE id = ?");
$stmt->execute([$id]);
// Récupérer les informations du bénévole sous forme de tableau 
// associatif
$benevole = $stmt->fetch();

// Vérifier si un bénévole a été trouvé avec cet ID
if (!$benevole) {
  // Si aucun bénévole n'est trouvé, rediriger vers la liste
  //  des bénévoles    
    header("Location: volunteer_list.php");
    exit;
}

// Mettre à jour les infos du bénévole
// Vérifier si le formulaire a été soumis via la méthode POST 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"]; // Récupérer le nom
    $email = $_POST["email"];// Récupérer l'email 
    $mot_de_passe = $_POST["mot_de_passe"];

 // Préparer une requête pour mettre à jour les informations 
 // du bénévole
    $stmt = $pdo->prepare("UPDATE benevoles SET nom = ?, email = ?, mot_de_passe = ? WHERE id = ?");
   // Exécuter la requête de mise à jour avec les nouvelles
   //  informations    
    $stmt->execute([$nom, $email, $mot_de_passe, $id]);

    // Rediriger vers la liste des bénévoles après la mise à jour    
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
    <!-- Dashboard -->
    <div class="bg-cyan-200 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li>
                <a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                    <i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole
                </a>
            </li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>

        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

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
