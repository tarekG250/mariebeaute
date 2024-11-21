<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des plages horaires bloquées</title>
    <style>
        /* Style global pour le fond et les boutons */
        body {
            background-color: #00d9ff; /* Fond de la page */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: #033d47; /* Couleur de texte */
        }

        /* Style des boutons */
        .cta-button {
            background-color: #00d9ff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease; /* Transition au survol */
        }

        /* Effet de survol pour les boutons */
        .cta-button:hover {
            background-color: #033d47;
        }

        /* Style pour le formulaire */
        form {
            margin: 20px auto;
            padding: 20px;
            max-width: 500px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        /* Style pour les messages d'erreur */
        p {
            color: red;
        }

        /* Espacement entre les éléments du formulaire */
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Gestion des plages horaires bloquées</h1>

    <!-- Formulaire d'ajout -->
    <h2>Ajouter une plage horaire bloquée</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="coiffeur_id">Coiffeur :</label>
            <select name="coiffeur_id" required>
                <?php
                // Connexion à la base de données
                $host = 'localhost';
                $dbname = 'salon_coiffure';
                $username = 'root';
                $password = '';
                
                try {
                    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Récupérer la liste des coiffeurs disponibles
                    $sql = "SELECT id, nom FROM coiffeurs WHERE disponibilite = 1"; // Seulement les coiffeurs disponibles
                    $stmt = $conn->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                    }
                } catch (PDOException $e) {
                    die("Erreur de connexion : " . $e->getMessage());
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="date">Date :</label>
            <input type="date" name="date" required>
        </div>

        <div class="form-group">
            <label for="heure_debut">Heure de début :</label>
            <input type="time" name="heure_debut" value="09:00" min="09:00" max="17:30" step="1800" required>
        </div>

        <div class="form-group">
            <label for="heure_fin">Heure de fin :</label>
            <input type="time" name="heure_fin" value="09:30" min="09:30" max="17:30" step="1800" required>
        </div>

        <div class="form-group">
            <label for="raison">Raison :</label>
            <input type="text" name="raison" required>
        </div>

        <div style="display: flex; justify-content: space-between;">
            <button class="cta-button" type="submit">Bloquer les Rendez-vous</button>
        </div>

        <a href="http://localhost:4208/mariebeaute/content/index.php" class="cta-button">Retour</a>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $coiffeur_id = $_POST['coiffeur_id'];
        $date = $_POST['date'];
        $heure_debut = $_POST['heure_debut'];
        $heure_fin = $_POST['heure_fin'];
        $raison = $_POST['raison'];

        // Validation des horaires
        $start = strtotime($heure_debut);
        $end = strtotime($heure_fin);

        // Vérification que l'heure de début est avant l'heure de fin
        if ($start >= $end) {
            echo "<p style='color: red;'>L'heure de début doit être avant l'heure de fin.</p>";
        } else {
            // Vérification que les horaires sont entre 9h00 et 17h30
            if ($start >= strtotime('09:00') && $end <= strtotime('17:30') && ($start % 1800 == 0) && ($end % 1800 == 0)) {
                // Connexion à la base de données pour insérer la plage horaire bloquée
                try {
                    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Insérer la plage horaire bloquée dans la table
                    $sql = "INSERT INTO plages_horaires_bloquees (coiffeur_id, date, heure_debut, heure_fin, raison)
                            VALUES (:coiffeur_id, :date, :heure_debut, :heure_fin, :raison)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':coiffeur_id', $coiffeur_id);
                    $stmt->bindParam(':date', $date);
                    $stmt->bindParam(':heure_debut', $heure_debut);
                    $stmt->bindParam(':heure_fin', $heure_fin);
                    $stmt->bindParam(':raison', $raison);

                    // Exécution de la requête
                    $stmt->execute();

                    // Confirmation
                    echo "<h3>Plage horaire bloquée ajoutée :</h3>";
                    echo "<p><strong>Coiffeur ID:</strong> $coiffeur_id</p>";
                    echo "<p><strong>Date:</strong> $date</p>";
                    echo "<p><strong>Heure de début:</strong> $heure_debut</p>";
                    echo "<p><strong>Heure de fin:</strong> $heure_fin</p>";
                    echo "<p><strong>Raison:</strong> $raison</p>";
                } catch (PDOException $e) {
                    die("Erreur d'ajout : " . $e->getMessage());
                }
            } else {
                echo "<p style='color: red;'>Les horaires doivent être compris entre 9h00 et 17h30, et doivent être multiples de 30 minutes.</p>";
            }
        }
    }
    ?>
</body>
</html>
