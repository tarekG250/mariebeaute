<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
require '../config/db.php';

// Vérifier si les données nécessaires sont présentes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];

    try {
        // Vérifier si la réservation appartient bien au client connecté
        $query = "SELECT id FROM reservations WHERE id = ? AND client_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$reservation_id, $_SESSION['client_id']]);
        if ($stmt->rowCount() == 0) {
            die("Accès non autorisé ou réservation introuvable.");
        }

        // Supprimer la réservation
        $deleteQuery = "DELETE FROM reservations WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute([$reservation_id]);

        // Rediriger avec un message de succès
        header("Location: ../content/rendez_vous.php?message=annulation_reussie");
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'annulation : " . $e->getMessage());
    }
} else {
    die("Requête invalide.");
}
?>
