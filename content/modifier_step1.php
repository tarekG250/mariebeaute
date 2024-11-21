<?php
session_start();
require 'header.php';
require '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: formulaire.php");
    exit();
}

// Vérifier si on a une réservation existante pour modifier
$reservation_id = $_GET['reservation_id'] ?? null;
if ($reservation_id) {
    // Récupérer la réservation existante pour la modifier
    $query = $conn->prepare("SELECT r.*, s.nom AS service_nom, c.nom AS coiffeur_nom 
                             FROM reservations r 
                             INNER JOIN services s ON r.service_id = s.id
                             INNER JOIN coiffeurs c ON r.coiffeur_id = c.id
                             WHERE r.id = ? AND r.client_id = ?");
    $query->execute([$reservation_id, $_SESSION['client_id']]);
    $reservation = $query->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        die("Réservation introuvable.");
    }

    // Enregistrer les données de la réservation dans la session
    $_SESSION['reservation_id'] = $reservation_id;
    $_SESSION['service_id'] = $reservation['service_id'];
    $_SESSION['coiffeur_id'] = $reservation['coiffeur_id'];
    $_SESSION['date'] = $reservation['date'];
    $_SESSION['heure'] = $reservation['heure'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Rendez-Vous - Étape 1</title>
    <link rel="stylesheet" href="../css/modifier_step1.css">
</head>
<body>
    <h1>Modifier votre Rendez-Vous - Étape 1</h1>
    
    <form action="modifier_step2.php" method="POST">
        <h3>Détails actuels de la réservation</h3>
        
        <!-- Détails actuels dans le formulaire -->
        <div class="details-section">
            <div class="details-item">
                <label>Service actuel :</label>
                <span><?= htmlspecialchars($reservation['service_nom'] ?? '') ?></span>
            </div>
            <div class="details-item">
                <label>Coiffeur actuel :</label>
                <span><?= htmlspecialchars($reservation['coiffeur_nom'] ?? '') ?></span>
            </div>
            <div class="details-item">
                <label>Date actuelle :</label>
                <span><?= htmlspecialchars($reservation['date'] ?? '') ?></span>
            </div>
            <div class="details-item">
                <label>Heure actuelle :</label>
                <span><?= htmlspecialchars($reservation['heure'] ?? '') ?></span>
            </div>
        </div>

        <!-- Sélection du service -->
        <label for="service_id">Service :</label>
        <select name="service_id" id="service_id" required>
            <?php
            $services = $conn->query("SELECT id, nom FROM services")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($services as $service) {
                echo "<option value='{$service['id']}' " . ($service['id'] == $_SESSION['service_id'] ? 'selected' : '') . ">{$service['nom']}</option>";
            }
            ?>
        </select><br><br>

        <!-- Sélection du coiffeur -->
        <label for="coiffeur_id">Coiffeur :</label>
        <select name="coiffeur_id" id="coiffeur_id" required>
            <?php
            $coiffeurs = $conn->query("SELECT id, nom FROM coiffeurs WHERE disponibilite = 1")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($coiffeurs as $coiffeur) {
                echo "<option value='{$coiffeur['id']}' " . ($coiffeur['id'] == $_SESSION['coiffeur_id'] ? 'selected' : '') . ">{$coiffeur['nom']}</option>";
            }
            ?>
        </select><br><br>

        <button type="submit">Continuer</button>
    </form>
</body>
</html>





