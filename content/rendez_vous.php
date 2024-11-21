<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: formulaire.php"); // Rediriger vers la page de connexion si aucune session
    exit();
}

// Connexion à la base de données
require 'header.php';
require '../config/db.php';

// Récupérer l'ID du client depuis la session
$client_id = $_SESSION['client_id'];

try {
    // Récupérer les rendez-vous du client
    $query = "
        SELECT 
            r.id AS reservation_id, 
            s.nom AS service_nom, 
            c.nom AS coiffeur_nom, 
            r.date, 
            r.heure 
        FROM 
            reservations r
        INNER JOIN 
            services s ON r.service_id = s.id
        INNER JOIN 
            coiffeurs c ON r.coiffeur_id = c.id
        WHERE 
            r.client_id = ?
        ORDER BY 
            r.date, r.heure
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$client_id]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur lors de la récupération des réservations : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes rendez-vous</title>
    <link rel="stylesheet" href="../css/rendez_vous.css">
</head>
<body>
    <h1>Vos rendez-vous</h1>

    <?php if (count($reservations) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Coiffeur</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['reservation_id']) ?></td>
                        <td><?= htmlspecialchars($reservation['service_nom']) ?></td>
                        <td><?= htmlspecialchars($reservation['coiffeur_nom']) ?></td>
                        <td><?= htmlspecialchars($reservation['date']) ?></td>
                        <td><?= htmlspecialchars($reservation['heure']) ?></td>
                        <td>
                        <form action="modifier_step1.php" method="GET">
    <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['reservation_id']) ?>">
    <button type="submit" class="modifier">Modifier</button>
</form>
    <form action="../traitements/traitement_annuler.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler ce rendez-vous ?');">
        <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($reservation['reservation_id']) ?>">
        <button type="submit" class="annuler">Annuler</button>
    </form>
</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Vous n'avez aucun rendez-vous prévu.</p>
    <?php endif; ?>

    <p><a href="../traitements/logout.php">Se déconnecter</a></p>
</body>
</html>
