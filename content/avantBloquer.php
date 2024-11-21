<!-- 
user : PabloGenevieve
password : 123456 
--->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avant bloque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #00d9ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .input-field:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .login-button {
            width: 100%;
            padding: 10px;
            background-color: #00d9ff;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login-button:hover {
            background-color: #033d47;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        .back-button {
            width: 100%;
            padding: 10px;
            background-color: #00d9ff;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .back-button:hover {
            background-color: #033d47;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Connexion</h2>
    <form action="#" method="POST">
        <input type="text" class="input-field" name="username" placeholder="Utilisateur" required>
        <input type="password" class="input-field" name="password" placeholder="Mot de passe" required>
        <button type="submit" class="login-button">Se connecter</button>
    </form>

    <?php
// Connexion à la base de données
$servername = "localhost";  // Remplacez par votre serveur
$username_db = "root";      // Remplacez par votre nom d'utilisateur MySQL
$password_db = "";          // Remplacez par votre mot de passe MySQL
$dbname = "salon_coiffure"; // Nom de la base de données

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Requête pour vérifier l'utilisateur et le mot de passe
    $sql = "SELECT * FROM userBloque WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérification si un utilisateur correspondant est trouvé
    if ($result->num_rows > 0) {
        // Si l'utilisateur et le mot de passe sont corrects
        header("Location: http://localhost:4208/mariebeaute/content/bloquerRendezVous.php");
        exit(); // N'oubliez pas d'ajouter l'exit() pour arrêter l'exécution du script après la redirection

    } else {
        // Si les informations de connexion sont incorrectes
        echo "<p class='error-message'>Utilisateur ou mot de passe incorrect.</p>";
    }

    $stmt->close();
}

$conn->close();
?>


    <button class="back-button" onclick="window.history.back()">Retour</button>
</div>

</body>
</html>
