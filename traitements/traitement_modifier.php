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
    $service_id = $_POST['service_id'];
    $coiffeur_id = $_POST['coiffeur_id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    try {
        // Vérifier si la réservation appartient bien au client connecté
        $query = "SELECT id FROM reservations WHERE id = ? AND client_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$reservation_id, $_SESSION['client_id']]);
        if ($stmt->rowCount() == 0) {
            die("Accès non autorisé ou réservation introuvable.");
        }

        // Mettre à jour la réservation
        $updateQuery = "
            UPDATE reservations 
            SET service_id = ?, coiffeur_id = ?, date = ?, heure = ? 
            WHERE id = ?
        ";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$service_id, $coiffeur_id, $date, $heure, $reservation_id]);

        // Rediriger avec un message de succès
        header("Location: gestion.php?message=modification_reussie");
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
} else {
    die("Requête invalide.");
}
?>
