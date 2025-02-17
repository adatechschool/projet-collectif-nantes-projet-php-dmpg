<?php
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Démarrer une transaction pour s'assurer que tout s'exécute ou rien
        $pdo->beginTransaction();

        // D'abord supprimer les déchets collectés associés
        $stmt_dechets = $pdo->prepare("DELETE FROM dechets_collectes WHERE id_collecte = ?");
        $stmt_dechets->execute([$id]);

        // Ensuite supprimer la collecte
        $stmt_collecte = $pdo->prepare("DELETE FROM collectes WHERE id = ?");
        $stmt_collecte->execute([$id]);

        // Valider la transaction
        $pdo->commit();

        header("Location: collection_list.php?success=1");
        exit();

    } catch (PDOException $e) {
        // En cas d'erreur, annuler toutes les modifications
        $pdo->rollBack();
        die("Erreur: " . $e->getMessage());
    }
} else {
    echo "ID invalide.";
}
?>