<?php
session_start(); // Démarre une session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    require '../config/db.php';

    // Récupérer l'email soumis
    $email = $_POST['email'];

    // Vérifier si le client existe
    $stmt = $conn->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client) {
        // Si le client existe, créer une session
        $_SESSION['client_id'] = $client['id']; // ID du client
        $_SESSION['client_email'] = $client['email']; // Email du client

        // Rediriger vers la page de gestion des réservations
        header("Location: ../content/rendez_vous.php");
        exit();
    } else {
        echo "Email non trouvé. Veuillez réessayer ou vous inscrire.";
    }
}
?>
