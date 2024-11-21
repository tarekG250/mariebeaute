<?php
// Inclure le fichier de connexion
require '../config/db.php';

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $client_nom = $_POST['client_nom'];
    $client_email = $_POST['client_email'];
    $coiffeur_id = $_POST['coiffeur_id'];
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    try {
        // Démarrer une transaction
        $conn->beginTransaction();

        // Vérifier si le client existe déjà
        $client_query = $conn->prepare("SELECT id FROM clients WHERE email = :email");
        $client_query->bindValue(":email", $client_email, PDO::PARAM_STR);
        $client_query->execute();
        $client = $client_query->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            // Si le client existe, obtenir son ID
            $client_id = $client['id'];
        } else {
            // Sinon, créer un nouveau client
            $stmt = $conn->prepare("INSERT INTO clients (nom, email) VALUES (:nom, :email)");
            $stmt->bindValue(":nom", $client_nom, PDO::PARAM_STR);
            $stmt->bindValue(":email", $client_email, PDO::PARAM_STR);
            $stmt->execute();
            $client_id = $conn->lastInsertId();
        }

        // Insérer la réservation
        $stmt = $conn->prepare("INSERT INTO reservations (client_id, coiffeur_id, service_id, date, heure) VALUES (:client_id, :coiffeur_id, :service_id, :date, :heure)");
        $stmt->bindValue(":client_id", $client_id, PDO::PARAM_INT);
        $stmt->bindValue(":coiffeur_id", $coiffeur_id, PDO::PARAM_INT);
        $stmt->bindValue(":service_id", $service_id, PDO::PARAM_INT);
        $stmt->bindValue(":date", $date, PDO::PARAM_STR);
        $stmt->bindValue(":heure", $heure, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            // Confirmer la transaction
            $conn->commit();
            echo "Réservation réussie!";
        } else {
            // Annuler la transaction en cas d'échec
            $conn->rollBack();
            echo "Erreur lors de la réservation.";
        }
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $conn->rollBack();
        echo "Erreur: " . $e->getMessage();
    }
}
?>
