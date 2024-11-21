<?php
session_start();
require 'header.php';
require '../config/db.php';

// Rediriger si l'étape 1 n'est pas complétée
if (!isset($_SESSION['client_nom'], $_SESSION['client_email'], $_SESSION['service_id'], $_SESSION['coiffeur_id'])) {
    header("Location: reservation_step1.php");
    exit;
}

// Récupérer les données de la session
$client_nom = $_SESSION['client_nom'];
$client_email = $_SESSION['client_email'];
$service_id = $_SESSION['service_id'];
$coiffeur_id = $_SESSION['coiffeur_id'];

// Plages horaires : 9h à 18h par intervalles de 30 minutes
$debut = new DateTime('09:00');
$fin = new DateTime('18:00');
$interval = new DateInterval('PT30M');
$plages = [];
for ($time = clone $debut; $time < $fin; $time->add($interval)) {
    $plages[] = $time->format('H:i');
}

// Vérifier les créneaux déjà réservés et les plages horaires bloquées
$date_dispo = isset($_POST['date']) ? $_POST['date'] : null;
$plages_occupees = [];
$plages_bloquees = [];
if ($date_dispo) {
    // Récupérer les plages horaires réservées
    $query = $conn->prepare("SELECT DATE_FORMAT(heure, '%H:%i') AS heure FROM reservations WHERE coiffeur_id = ? AND date = ?");
    $query->execute([$coiffeur_id, $date_dispo]);
    $plages_occupees = $query->fetchAll(PDO::FETCH_COLUMN); // Récupérer les heures réservées

    // Récupérer les plages horaires bloquées
    $query = $conn->prepare("SELECT DATE_FORMAT(heure_debut, '%H:%i') AS heure FROM plages_horaires_bloquees WHERE coiffeur_id = ? AND date = ?");
    $query->execute([$coiffeur_id, $date_dispo]);
    $plages_bloquees = $query->fetchAll(PDO::FETCH_COLUMN); // Récupérer les heures bloquées
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['heure'])) {
    $heure = $_POST['heure'];

    // Vérification stricte en base de données
    $checkQuery = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE coiffeur_id = ? AND date = ? AND heure = ?");
    $checkQuery->execute([$coiffeur_id, $date_dispo, $heure]);
    $alreadyBooked = $checkQuery->fetchColumn();

    if ($alreadyBooked > 0) {
        $error = "Ce créneau est déjà réservé. Veuillez en choisir un autre.";
    } else {
        // Insérer le client si l'email n'existe pas
        $clientQuery = $conn->prepare("SELECT id FROM clients WHERE email = ?");
        $clientQuery->execute([$client_email]);
        $client = $clientQuery->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            $client_id = $client['id'];
        } else {
            $insertClient = $conn->prepare("INSERT INTO clients (nom, email) VALUES (?, ?)");
            $insertClient->execute([$client_nom, $client_email]);
            $client_id = $conn->lastInsertId();
        }

        // Insérer la réservation
        $insertReservation = $conn->prepare("INSERT INTO reservations (client_id, coiffeur_id, service_id, date, heure) VALUES (?, ?, ?, ?, ?)");
        $insertReservation->execute([$client_id, $coiffeur_id, $service_id, $date_dispo, $heure]);

        // Confirmation
        header("Location: confirmation_reservation.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étape 2 - Réservation</title>
    <link rel="stylesheet" href="../css/reservation_step1.css">
</head>
<body>
    <h1>Réserver un Rendez-Vous - Étape 2</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="date">Date :</label>
        <input type="date" name="date" id="date" value="<?= htmlspecialchars($date_dispo) ?>" required onchange="this.form.submit()">
        <br><br>
        <?php if ($date_dispo): ?>
            <h3>Choisissez une plage horaire :</h3>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Horaire</th>
                        <th>Disponibilité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plages as $horaire): ?>
                        <tr>
                            <td><?= $horaire ?></td>
                            <td>
                                <?php if (in_array($horaire, $plages_occupees) || in_array($horaire, $plages_bloquees)): ?>
                                    <span style="color: red;">Indisponible</span>
                                <?php else: ?>
                                    <label>
                                        <input type="radio" name="heure" value="<?= $horaire ?>" required>
                                        Disponible
                                    </label>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <button type="submit">Confirmer</button>
        <?php else: ?>
            <p>Sélectionnez une date pour voir les créneaux disponibles.</p>
        <?php endif; ?>
    </form>
</body>
</html>
