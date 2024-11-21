<?php
// Connexion à la base de données
require '../config/db.php';

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = htmlspecialchars($_POST['name']); // Sécurise l'entrée
    $service = htmlspecialchars($_POST['service']);
    $commentaire = htmlspecialchars($_POST['feedback']);
    $note = intval($_POST['rating']); // Convertit la note en entier

    // Préparation de la requête d'insertion
    $sql = "INSERT INTO commentaires (nom, service, commentaire, note) VALUES (:nom, :service, :commentaire, :note)";
    $stmt = $conn->prepare($sql);

    try {
        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':nom' => $nom,
            ':service' => $service,
            ':commentaire' => $commentaire,
            ':note' => $note
        ]);
        $message = "Merci pour votre évaluation !";
    } catch (PDOException $e) {
        $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laissez un commentaire</title>
    <link rel="stylesheet" href="../css/commentaire.css">
 
</head>
<body>
    <div class="form-container">
    <main class="comment-page">
        <h1>Partagez votre expérience </h1>
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= $message ?></p>
        <?php endif; ?>
         <div class="form-wrapper">
        <form action="" method="POST" class="comment-form">
            <div class="form-group">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required placeholder="Entrez votre nom">
            </div>
            <div class="form-group">
                <label for="service">Service:</label>
                <select id="service" name="service" required>
                    <option value="coiffure">Coiffure</option>
                    <option value="coloration">Coloration</option>
                </select>
            </div>
            <div class="form-group">
                <label for="rating">Note :</label>
                <div class="star-rating">
                    <input type="radio" name="rating" id="star5" value="5">
                    <label for="star5" title="5 étoiles">&#9733;</label>
                    <input type="radio" name="rating" id="star4" value="4">
                    <label for="star4" title="4 étoiles">&#9733;</label>
                    <input type="radio" name="rating" id="star3" value="3">
                    <label for="star3" title="3 étoiles">&#9733;</label>
                    <input type="radio" name="rating" id="star2" value="2">
                    <label for="star2" title="2 étoiles">&#9733;</label>
                    <input type="radio" name="rating" id="star1" value="1">
                    <label for="star1" title="1 étoile">&#9733;</label>
                </div>
            </div>
          <div class="form-group">
    <textarea id="feedback" name="feedback" rows="5" required placeholder="Entrez votre commentaire ici..."></textarea>
    </div>
        <div class="form-group">
        <button type="submit" class="cta-button">Envoyer mon évaluation</button>
    </div>

    <a href="http://localhost:4208/mariebeaute/content/index.php" class="cta-button">Retour</a>

        </form>
        </div>
    </main>
    </div>
</body>
</html>

