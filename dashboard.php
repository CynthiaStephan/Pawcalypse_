<?php
session_start();
require './config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Récupération des données
$resources = $pdo->query("SELECT * FROM resources")->fetchAll();
$missions = $pdo->query("SELECT * FROM missions")->fetchAll();
// Calcul du pourcentage d'invasion
$total_missions = $pdo->query("SELECT COUNT(*) as total FROM missions")->fetch()['total'];
$completed_missions = $pdo->query("SELECT COUNT(*) as completed FROM missions WHERE status = 'terminée'")->fetch()['completed'];

$invasion_percentage = ($total_missions > 0) ? ($completed_missions / $total_missions) * 100 : 0;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawcalypse Now !</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>

        body {
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle, #1a1a1a, #000000);
            color: #ff004c;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #8b0000, #ff004c, #8b0000);
            color: white;
            padding: 1rem;
            text-align: center;
            text-shadow: 2px 2px 10px #ff004c;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 15px #ff004c;
        }

        h1, h2 {
            text-align: center;
            color: #fff;
            text-shadow: 1px 1px 5px #717171;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
            color: white;
        }

        table th, table td {
            padding: 0.75rem;
            text-align: center;
            border: 1px solid #ff004c;
        }

        table th {
            background-color: rgba(255, 0, 76, 0.2);
        }

        table td {
            background-color: rgba(255, 255, 255, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="number"], input[type="text"], select {
            padding: 0.5rem;
            border: 1px solid #ff004c;
            background: #1a1a1a;
            color: #ff004c;
            border-radius: 5px;
            width: 90%;
            margin: 0.5rem auto;
        }
        
        button {
            background: linear-gradient(to right, #ff004c, #8b0000);
            color: black;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-transform: uppercase;
            box-shadow: 0 0 10px #ff004c;
        }

        button:hover {
            background: linear-gradient(to right, #8b0000, #ff004c);
            box-shadow: 0 0 15px #ff004c;
        }

        .logout a {
            color: #ff004c;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 1rem;
            text-shadow: 0 0 10px #ff004c;
        }

        .logout a:hover {
            color: #8b0000;
        }
        .invasion-progress {
            text-align: center;
            margin-bottom: 2rem;
            color: #fff;
        }

        .progress-bar-container {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #ff004c;
            border-radius: 10px;
            width: 80%;
            margin: 0 auto;
            height: 20px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(to right, #ff004c, #8b0000);
            height: 100%;
            transition: width 0.5s ease-in-out;
            box-shadow: 0 0 15px #ff004c;
        }

        .progress-percentage {
            margin-top: 10px;
            font-size: 1.5rem;
            text-shadow: 0 0 10px #ff004c;
        }


        /* Animation */
        @keyframes glow {
            0% { box-shadow: 0 0 10px #ff004c; }
            50% { box-shadow: 0 0 20px #8b0000; }
            100% { box-shadow: 0 0 10px #ff004c; }
        }

        table {
            animation: glow 2s infinite alternate;
        }

        button {
            animation: glow 1.5s infinite alternate;
        }
        .hologram-container {
            display: flex;
            justify-content: center;
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        #earth {
            position: relative;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: transparent url("https://i.imgur.com/Qk4UXcU.png");
            background-size: cover;
            box-shadow: inset 0px -20px 50px 10px #00ffff80,
                0px 0px 30px 6px #00ffff70;
            transform-style: preserve-3d;
            transform: rotate(20deg);
            animation: rotate 15s linear infinite;
        }

        @keyframes rotate {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 530px 0;
            }
        }

        .invasion-percentage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.8rem;
            color: #00ffff;
            text-shadow: 0 0 20px #00ffff, 0 0 30px #00ffff80;
            animation: pulse 2s infinite alternate;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                text-shadow: 0 0 20px #00ffff, 0 0 30px #00ffff80;
            }

            100% {
                transform: translate(-50%, -50%) scale(1.1);
                text-shadow: 0 0 30px #00ffff, 0 0 50px #00ffffa0;
            }
        }
        
    </style>
</head>
<body>
    <header>
        <h1>Pawcalypse Now !</h1>
    </header>

    <div class="container">
        <div class="hologram-container">
            <div id="earth"></div>
            <div class="invasion-percentage">
                <?php echo number_format($invasion_percentage, 2); ?>% Invasion Réussie
            </div>
        </div>



        <!-- Gestion des stocks -->
        <h2>Gestion des Stocks</h2>
        <form method="post" action="/functions/update_resources.php">
            <table>
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>Quantité</th>
                        <th>Nouvelle Quantité</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resources as $resource): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($resource['resource_name']); ?></td>
                            <td><?php echo htmlspecialchars($resource['quantity']); ?></td>
                            <td>
                                <input type="number" name="new_quantity[<?php echo $resource['id']; ?>]" min="0">
                            </td>
                            <td>
                                <button type="submit" name="update_resource" value="<?php echo $resource['id']; ?>">Mettre à jour</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>

        <!-- Gestion des missions -->
        <h2>Gestion des Missions</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom de la Mission</th>
                    <th>Statut</th>
                    <th>Nouveau Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($missions as $mission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mission['mission_name']); ?></td>
                        <td><?php echo htmlspecialchars($mission['status']); ?></td>
                        <td>
                            <form method="post" action="/functions/update_missions.php">
                                <select name="new_status">
                                    <option value="planifiée" <?php echo $mission['status'] === 'planifiée' ? 'selected' : ''; ?>>Planifiée</option>
                                    <option value="en cours" <?php echo $mission['status'] === 'en cours' ? 'selected' : ''; ?>>En cours</option>
                                    <option value="terminée" <?php echo $mission['status'] === 'terminée' ? 'selected' : ''; ?>>Terminée</option>
                                </select>
                                <input type="hidden" name="mission_id" value="<?php echo $mission['id']; ?>">
                                <button type="submit">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <!-- Ajouter une nouvelle mission -->
        <h2>Lancer une Nouvelle Mission</h2>
        <form method="post" action="/functions/add_mission.php">
            <label for="mission_name">Nom de la Mission :</label>
            <input type="text" id="mission_name" name="mission_name" required>
            
            <label for="resource_id">Allouer une Ressource :</label>
            <select id="resource_id" name="resource_id" required>
                <?php foreach ($resources as $resource): ?>
                    <option value="<?php echo $resource['id']; ?>"><?php echo htmlspecialchars($resource['resource_name']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="resource_quantity">Quantité :</label>
            <input type="number" id="resource_quantity" name="resource_quantity" min="1" required>
            
            <button type="submit">Créer Mission</button>
        </form>

        <div class="logout">
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
</body>
</html>
