<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: formulaire.php");
    exit();
}

// Connexion à la base de données
require '../config/db.php';

// Vérifier si un ID de réservation est passé
if (!isset($_GET['id'])) {
    die("Aucun ID de réservation fourni.");
}

$reservation_id = $_GET['id'];

try {
    // Vérifier si la réservation appartient au client connecté
    $query = "
        SELECT r.*, s.nom AS service_nom, c.nom AS coiffeur_nom
        FROM reservations r
        INNER JOIN services s ON r.service_id = s.id
        INNER JOIN coiffeurs c ON r.coiffeur_id = c.id
        WHERE r.id = ? AND r.client_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$reservation_id, $_SESSION['client_id']]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        die("Rendez-vous introuvable ou accès non autorisé.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annuler un rendez-vous</title>
</head>
<body>
    <h1>Annuler votre rendez-vous</h1>

    <p>Êtes-vous sûr de vouloir annuler ce rendez-vous ?</p>
    <ul>
        <li><strong>Service :</strong> <?= htmlspecialchars($reservation['service_nom']) ?></li>
        <li><strong>Coiffeur :</strong> <?= htmlspecialchars($reservation['coiffeur_nom']) ?></li>
        <li><strong>Date :</strong> <?= htmlspecialchars($reservation['date']) ?></li>
        <li><strong>Heure :</strong> <?= htmlspecialchars($reservation['heure']) ?></li>
    </ul>

    <form action="../traitements/traitement_annuler.php" method="POST">
        <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation_id) ?>">
        <button type="submit">Confirmer l'annulation</button>
        <a href="gestion.php">Retour</a>
    </form>
</body>
</html>
