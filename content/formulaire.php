<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulaire.css">
    <title>Formulaire de Connexion</title>
</head>
<body>
    <form action="../traitements/login.php" method="POST">
        <h1>Connexion</h1>
        <label for="email">Votre email :</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Se connecter</button>
        <button onclick="navigationIndex()">Retour</button>
    </form>
</body>
</html>

<script>
    function navigationIndex() {
        window.location.href = "http://localhost:4208/mariebeaute/content/index.php";
    }
</script>
