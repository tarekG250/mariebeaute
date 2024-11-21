<?php
// Démarrer la session pour stocker temporairement les données
session_start();
require 'header.php';
require '../config/db.php';

// Récupérer la liste des services et des coiffeurs disponibles
$services = $conn->query("SELECT id, nom FROM services")->fetchAll(PDO::FETCH_ASSOC);
$coiffeurs = $conn->query("SELECT id, nom FROM coiffeurs WHERE disponibilite = 1")->fetchAll(PDO::FETCH_ASSOC);

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_SESSION['client_nom'] = $_POST['client_nom'];
    $_SESSION['client_email'] = $_POST['client_email'];
    $_SESSION['service_id'] = $_POST['service_id'];
    $_SESSION['coiffeur_id'] = $_POST['coiffeur_id'];

    // Redirection vers l'étape 2
    header("Location: reservation_step2.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étape 1 - Réservation</title>
    <link rel="stylesheet" href="../css/reservation_step1.css">
</head>
<body>
    <h1 class="couleur">Réserver un Rendez-Vous - Étape 1</h1>
    <form method="POST">
        <label for="client_nom">Nom :</label>
        <input type="text" name="client_nom" id="client_nom" required>
        <br>
        <label for="client_email">Email :</label>
        <input type="email" name="client_email" id="client_email" required>
        <br>
        <label for="service_id">Service :</label>
        <select name="service_id" id="service_id" required>
            <option value="" disabled selected>Choisissez un service</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= $service['id'] ?>"><?= $service['nom'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="coiffeur_id">Coiffeur :</label>
        <select name="coiffeur_id" id="coiffeur_id" required>
            <option value="" disabled selected>Choisissez un coiffeur</option>
            <?php foreach ($coiffeurs as $coiffeur): ?>
                <option value="<?= $coiffeur['id'] ?>"><?= $coiffeur['nom'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit">Suivant</button>
    </form>
</body>
</html>

