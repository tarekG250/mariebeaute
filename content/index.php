<?php
require "header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G & P Coiffure - Prenez votre Rendez-Vous</title>
    <link rel="stylesheet" href="../css/index.css"> <!-- Lien vers le fichier CSS externe -->
</head>
<body>
    <main>
        <!-- Section Accueil mise à jour avec une description détaillée du site -->
        <section id="accueil" class="hero-section">
            <div class="hero-content">
                <h2>Bienvenue chez G & P Coiffure</h2>
                <img src="../images/LogoMarieBeaute.webp" alt="Logo G & P Coiffure" class="site-logo">
            </div>
        </section>

        <!-- Section Services -->
        <section id="services" class="services-section">
            <h2>Nos Services</h2>
            <div class="services-grid">
                <div class="service-item">
                    <h3>Coupe de cheveux</h3>
                    <p>Notre service de coupe est effectué par des experts pour tous les types de cheveux.</p>
                </div>
                <div class="service-item">
                    <h3>Coloration</h3>
                    <p>Nous proposons des services de coloration de cheveux qui vous feront briller.</p>
                </div>
                <div class="service-item">
                    <h3>Soins capillaires</h3>
                    <p>Des soins nourrissants pour garder vos cheveux en bonne santé et éclatants.</p>
                </div>
            </div>
        </section>

        <!-- Section Réservation -->
        <section id="reservation" class="reservation-section">
            <h2>Réservez Votre Rendez-Vous</h2>
            <p>Choisissez un coiffeur et une plage horaire qui vous conviennent, et recevez une confirmation instantanée de votre rendez-vous.</p>
            <a href="reservation_step1.php" class="cta-button">Réserver maintenant</a>
        </section>

        <section id="contact-services" class="contact-services-section">
    <br>
    <h2>Options supplémentaires</h2>
    <br> <br>
    <div class="services-grid">
        <!-- Section Faire un commentaire -->
        <div class="service-item">
            <h3>Commentaire</h3>
            <br> <br>
            <a href="commentaire.php" class="cta-button">Commenter</a>
            <a href="liste_commentaires.php" class="cta-button">Lire des commentaires</a>
        </div>

            <!-- Section Contact -->
            <div class="service-item">
                <h3>Contactez-Nous</h3>
                <br> <br>
                <a href="mailto:pablo.pardo@gpcoiffure.com" class="cta-button">Envoyer un Email</a>
            </div>

            <!-- Section Liste des rendez-vous -->
            <div class="service-item">
                <h3>Autres options</h3>
                <br> <br>
                <a href="http://localhost:4208/mariebeaute/content/listeRendezVous.php?coiffeur_id=1" class="cta-button">Voir Rendez-vous</a>
                <a href="http://localhost:4208/mariebeaute/content/avantBloquer.php" class="cta-button">Bloquer Rendez-vous</a>
            </div>
        </div>
    </section>

    <br>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 G & P Coiffure - Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
