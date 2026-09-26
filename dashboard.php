<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: index.php");
    exit();
}

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "gestion_inscription");

// Récupération des statistiques
$total_etudiant = $conn->query("SELECT COUNT(*) AS total FROM etudiant")->fetch_assoc()['total'];
$total_inscription = $conn->query("SELECT COUNT(*) AS total FROM inscription")->fetch_assoc()['total'];
$total_classe = $conn->query("SELECT COUNT(*) AS total FROM classe")->fetch_assoc()['total'];
$total_annee = $conn->query("SELECT COUNT(*) AS total FROM annee_scolaire")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Gestion des Inscriptions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7fc;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background-color: #007bff;
            padding-top: 40px;
            min-height: 100vh;
            color: white;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
            position: fixed;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .main-content {
            margin-left: 220px;
            padding: 30px;
            width: 100%;
        }

        .header {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: #333;
        }

        .header p {
            margin-top: 10px;
            color: #555;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            padding: 25px;
            text-align: center;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card i {
            font-size: 40px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .card h2 {
            margin: 0;
            font-size: 36px;
            color: #222;
        }

        .card p {
            color: #666;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="annees.php"><i class="fas fa-calendar-alt"></i> Années Scolaires</a>
    <a href="classes.php"><i class="fas fa-school"></i> Classes</a>
    <a href="etudiants.php"><i class="fas fa-user-graduate"></i> Étudiants</a>
    <a href="inscriptions.php"><i class="fas fa-file-signature"></i> Inscriptions</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Bienvenue sur l'application de gestion d'inscription 🎓</h1>
        <p>Connecté en tant que <strong><?= htmlspecialchars($_SESSION['utilisateur']) ?></strong></p>
    </div>

    <div class="stats">
        <div class="card">
            <i class="fas fa-user-graduate"></i>
            <h2><?= $total_etudiant ?></h2>
            <p>Étudiants</p>
        </div>
        <div class="card">
            <i class="fas fa-file-signature"></i>
            <h2><?= $total_inscription ?></h2>
            <p>Inscriptions</p>
        </div>
        <div class="card">
            <i class="fas fa-school"></i>
            <h2><?= $total_classe ?></h2>
            <p>Classes</p>
        </div>
        <div class="card">
            <i class="fas fa-calendar-alt"></i>
            <h2><?= $total_annee ?></h2>
            <p>Années Scolaires</p>
        </div>
    </div>
</div>

</body>
</html>
