<?php
session_start();
require 'header.php';
require '../config/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: formulaire.php");
    exit();
}

// Vérifier si les données ont été envoyées via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les nouvelles informations de service et de coiffeur
    $service_id = $_POST['service_id'] ?? $_SESSION['service_id'];
    $coiffeur_id = $_POST['coiffeur_id'] ?? $_SESSION['coiffeur_id'];

    // Mise à jour du service et du coiffeur dans la base de données
    $updateQuery = $conn->prepare("UPDATE reservations SET service_id = ?, coiffeur_id = ? WHERE id = ? AND client_id = ?");
    $updateQuery->execute([$service_id, $coiffeur_id, $_SESSION['reservation_id'], $_SESSION['client_id']]);

    // Mettre à jour les variables de session
    $_SESSION['service_id'] = $service_id;
    $_SESSION['coiffeur_id'] = $coiffeur_id;
}

// Plages horaires disponibles
$debut = new DateTime('09:00');
$fin = new DateTime('18:00');
$interval = new DateInterval('PT30M');
$plages = [];
for ($time = clone $debut; $time < $fin; $time->add($interval)) {
    $plages[] = $time->format('H:i');
}

// Vérifier les créneaux déjà réservés
$date_dispo = isset($_POST['date']) ? $_POST['date'] : null;
$plages_occupees = [];
if ($date_dispo) {
    // Reformater l'heure en format 'HH:MM' directement dans la requête SQL
    $query = $conn->prepare("SELECT DATE_FORMAT(heure, '%H:%i') AS heure FROM reservations WHERE coiffeur_id = ? AND date = ?");
    $query->execute([$coiffeur_id, $date_dispo]);
    $plages_occupees = $query->fetchAll(PDO::FETCH_COLUMN); // Récupérer les heures réservées au format 'HH:MM'
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
        // Mettre à jour la réservation avec la nouvelle date et heure
        $updateReservation = $conn->prepare("UPDATE reservations SET date = ?, heure = ? WHERE id = ?");
        $updateReservation->execute([$date_dispo, $heure, $_SESSION['reservation_id']]);

        // Confirmation
        header("Location: rendez_vous.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Rendez-Vous - Étape 2</title>
    <link rel="stylesheet" href="../css/modifier_step1.css">
</head>
<body>
    <h1>Modifier votre Rendez-Vous - Étape 2</h1>

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
                                <?php if (in_array($horaire, $plages_occupees)): ?>
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


