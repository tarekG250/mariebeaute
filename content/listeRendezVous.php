<?php
$host = 'localhost';
$dbname = 'salon_coiffure';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer la liste des coiffeurs pour la liste déroulante
    $coiffeursQuery = "SELECT id, nom FROM coiffeurs";
    $coiffeursStmt = $conn->query($coiffeursQuery);
    $coiffeurs = $coiffeursStmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si un coiffeur a été sélectionné
    $selectedCoiffeur = isset($_GET['coiffeur_id']) ? $_GET['coiffeur_id'] : null;

    // Préparer une requête pour récupérer les rendez-vous filtrés par coiffeur
    if ($selectedCoiffeur) {
        $query = "
        SELECT 
            r.date, 
            r.heure, 
            c.nom AS client_nom, 
            s.nom AS service_nom, 
            co.nom AS coiffeur_nom
        FROM reservations r
        JOIN clients c ON r.client_id = c.id
        JOIN services s ON r.service_id = s.id
        JOIN coiffeurs co ON r.coiffeur_id = co.id
        WHERE co.id = :coiffeur_id
        ORDER BY r.date ASC, r.heure ASC";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':coiffeur_id', $selectedCoiffeur, PDO::PARAM_INT);
        $stmt->execute();

        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $reservations = [];
    }

    // Affichage du formulaire et des rendez-vous
    echo "<!DOCTYPE html>";
    echo "<html lang='fr'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Rendez-vous par coiffeur</title>";
    echo "<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            text-align: center;
        }
        form, table, .btn {
            margin: 20px auto;
        }
        label {
            font-size: 1.2em;
            margin-right: 10px;
        }
        select, .btn {
            font-size: 1em;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
        }
        select {
            background-color: #00d9ff;
            color: white;
            cursor: pointer;
        }
        select:hover {
            background-color: #033d47;
        }
        table {
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #00d9ff;
            color: white;
        }
        .btn {
            background-color: #00d9ff;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #033d47;
        }
    </style>";
    echo "</head>";
    echo "<body>";

    echo "<h1>Liste des rendez-vous par coiffeur</h1>";
    echo "<form method='GET' action=''>";
    echo "<label for='coiffeur_id'>Choisissez un coiffeur :</label>";
    echo "<select name='coiffeur_id' id='coiffeur_id' onchange='this.form.submit()'>";
    echo "<option value=''>-- Tous les coiffeurs --</option>";
    foreach ($coiffeurs as $coiffeur) {
        $selected = ($coiffeur['id'] == $selectedCoiffeur) ? 'selected' : '';
        echo "<option value='" . htmlspecialchars($coiffeur['id']) . "' $selected>" . htmlspecialchars($coiffeur['nom']) . "</option>";
    }
    echo "</select>";
    echo "</form>";

    if ($selectedCoiffeur) {
        if (count($reservations) > 0) {
            echo "<h2>Rendez-vous pour le coiffeur sélectionné</h2>";
            echo "<table>";
            echo "<tr><th>Date</th><th>Heure</th><th>Client</th><th>Service</th></tr>";

            foreach ($reservations as $reservation) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($reservation['date']) . "</td>";
                echo "<td>" . htmlspecialchars($reservation['heure']) . "</td>";
                echo "<td>" . htmlspecialchars($reservation['client_nom']) . "</td>";
                echo "<td>" . htmlspecialchars($reservation['service_nom']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Aucun rendez-vous trouvé pour ce coiffeur.</p>";
        }
    } else {
        echo "<p>Sélectionnez un coiffeur pour voir les rendez-vous.</p>";
    }

    // Bouton de retour
    echo "<a href='index.php' class='btn'>Retour</a>";

    echo "</body>";
    echo "</html>";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
