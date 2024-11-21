<?php
$host = 'localhost';
$dbname = 'salon_coiffure';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la table "clients"
    $tableClients = "
    CREATE TABLE IF NOT EXISTS clients (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB";
    $conn->exec($tableClients);

    // Vérifier si des clients existent avant d'insérer des données
    $checkClients = "SELECT COUNT(*) FROM clients";
    $stmt = $conn->query($checkClients);
    $clientCount = $stmt->fetchColumn();
    if ($clientCount == 0) {
        // Insertion de données dans la table "clients" seulement si elle est vide
        $insertClients = "
        INSERT INTO clients (nom, email) VALUES
        ('Jean Dupont', 'jean.dupont@email.com'),
        ('Marie Martin', 'marie.martin@email.com'),
        ('Pierre Durand', 'pierre.durand@email.com')
        ";
        $conn->exec($insertClients);
    }

    // Création de la table "coiffeurs"
    $tableCoiffeurs = "
    CREATE TABLE IF NOT EXISTS coiffeurs (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL,
        disponibilite BOOLEAN DEFAULT 1,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB";
    $conn->exec($tableCoiffeurs);

    // Vérifier si des coiffeurs existent avant d'insérer des données
    $checkCoiffeurs = "SELECT COUNT(*) FROM coiffeurs";
    $stmt = $conn->query($checkCoiffeurs);
    $coiffeurCount = $stmt->fetchColumn();
    if ($coiffeurCount == 0) {
        // Insertion de données dans la table "coiffeurs" seulement si elle est vide
        $insertCoiffeurs = "
        INSERT INTO coiffeurs (nom, disponibilite) VALUES
        ('Alice Leclerc', 1),
        ('David Gauthier', 1),
        ('Sophie Baudoin', 0)
        ";
        $conn->exec($insertCoiffeurs);
    }

    // Création de la table "services"
    $tableServices = "
    CREATE TABLE IF NOT EXISTS services (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        nom VARCHAR(50) NOT NULL,
        description TEXT,
        prix DECIMAL(10, 2) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB";
    $conn->exec($tableServices);

    // Vérifier si des services existent avant d'insérer des données
    $checkServices = "SELECT COUNT(*) FROM services";
    $stmt = $conn->query($checkServices);
    $serviceCount = $stmt->fetchColumn();
    if ($serviceCount == 0) {
        // Insertion de données dans la table "services" seulement si elle est vide
        $insertServices = "
        INSERT INTO services (nom, description, prix) VALUES
        ('Coupe Homme', 'Coupe classique pour homme', 25.00),
        ('Coupe Femme', 'Coupe stylisée pour femme', 35.00),
        ('Coloration', 'Coloration complète des cheveux', 50.00)
        ";
        $conn->exec($insertServices);
    }

    // Création de la table "reservations"
    $tableReservations = "
    CREATE TABLE IF NOT EXISTS reservations (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        client_id INT UNSIGNED NOT NULL,
        coiffeur_id INT UNSIGNED NOT NULL,
        service_id INT UNSIGNED NOT NULL,
        date DATE NOT NULL,
        heure TIME NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (client_id) REFERENCES clients(id),
        FOREIGN KEY (coiffeur_id) REFERENCES coiffeurs(id),
        FOREIGN KEY (service_id) REFERENCES services(id)
    ) ENGINE=InnoDB";
    $conn->exec($tableReservations);

    // Vérifier si des réservations existent avant d'insérer des données
    $checkReservations = "SELECT COUNT(*) FROM reservations";
    $stmt = $conn->query($checkReservations);
    $reservationCount = $stmt->fetchColumn();
    if ($reservationCount == 0) {
        // Insertion de données dans la table "reservations" seulement si elle est vide
        $insertReservations = "
        INSERT INTO reservations (client_id, coiffeur_id, service_id, date, heure) VALUES
        (1, 1, 1, '2024-11-20', '10:00'),
        (2, 2, 2, '2024-11-21', '14:30'),
        (3, 3, 3, '2024-11-22', '16:00')
        ";
        $conn->exec($insertReservations);
    }

    // Création de la table "commentaires"
    $tableCommentaires = "
    CREATE TABLE IF NOT EXISTS commentaires (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        service VARCHAR(255) NOT NULL,
        commentaire TEXT NOT NULL,
        note INT NOT NULL,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    $conn->exec($tableCommentaires);

    // Création de la table "plages_horaires_bloquees"
    $tablePlagesBloquees = "
    CREATE TABLE IF NOT EXISTS plages_horaires_bloquees (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        coiffeur_id INT UNSIGNED NOT NULL,
        date DATE NOT NULL,
        heure_debut TIME NOT NULL,
        heure_fin TIME NOT NULL,
        raison VARCHAR(255) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (coiffeur_id) REFERENCES coiffeurs(id)
    ) ENGINE=InnoDB";
    $conn->exec($tablePlagesBloquees);

    $tableUserBloque = "
    CREATE TABLE IF NOT EXISTS UserBloque (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB";
    $conn->exec($tableUserBloque);

    // Insertion de l'utilisateur "PabloGenevieve" avec son mot de passe haché
    $motDePasse = 'qwertyuio23456789sdfghj';  // Le mot de passe fourni
    $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);

    $insertUtilisateur = "
    INSERT INTO UserBloque (username, password) VALUES
    ('PabloGenevieve', '$motDePasseHache')
    ";
    $conn->exec($insertUtilisateur);


} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
