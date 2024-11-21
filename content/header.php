<?php
// Démarrer la session seulement si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Site de Réservation</title>
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>

    <!-- En-tête -->
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><strong><a href="index.php">Accueil</a></strong></li>
                    <li><strong><a href="reservation_step1.php">Réserver</a></strong></li>
                    <li><strong><a href="rendez_vous.php">Mes Rendez-Vous</a></strong></li>
                    <?php if (isset($_SESSION['client_id'])): ?>
                        <li><strong><a href="profil.php">Mon Profil</a></strong></li>
                        <li><strong><a href="../traitements/logout.php">Se Déconnecter</a></strong></li>
                    <?php else: ?>
                        <li><strong><a href="formulaire.php">Se Connecter</a></strong></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

</body>
</html>

