<?php
// Connexion à la base de données
$host = 'localhost'; // Changez avec vos paramètres
$dbname = 'salon_coiffure'; // Nom de votre base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = ''; // Mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Requête pour récupérer les commentaires
$sql = "SELECT id, nom, service, commentaire, note, date_creation FROM commentaires ORDER BY date_creation DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voici les commentaires de nos clients ! </title>
    <link rel="stylesheet" href="../css/liste_commentaires.css"> 
</head>
<body>
    <main>
        <h1>Voici les commentaires de nos clients !</h1>
        <?php if (count($commentaires) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom du client</th>
                        <th>Service reçu</th>
                        <th>Commentaire</th>
                        <th>Note</th>
                        <th>Date du commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commentaires as $commentaire): ?>
                        <tr>
                            <td><?= htmlspecialchars($commentaire['nom']) ?></td>
                            <td><?= htmlspecialchars($commentaire['service']) ?></td>
                            <td><?= htmlspecialchars($commentaire['commentaire']) ?></td>
                            <td><?= htmlspecialchars($commentaire['note']) ?></td>
                            <td><?= htmlspecialchars($commentaire['date_creation']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun commentaire pour le moment.</p>
        <?php endif; ?>
    </main>
</body>
</html>
