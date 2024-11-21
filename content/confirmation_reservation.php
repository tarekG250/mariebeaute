<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/confirmation_reservation.css">
    <title>Confirmation</title>
</head>
<body>
    <h1>Réservation Confirmée</h1>
    <p>Votre rendez-vous a été enregistré avec succès !</p>
    <a href="reservation_step1.php">Réserver un autre rendez-vous</a>
</body>
</html>

