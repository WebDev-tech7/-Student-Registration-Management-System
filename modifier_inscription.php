<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: index.php");
    exit();
}
require_once 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: inscriptions.php");
    exit();
}

$id = intval($_GET['id']);

// Récupération de l'inscription
$stmt = $conn->prepare("SELECT * FROM inscription WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$inscription = $result->fetch_assoc();

if (!$inscription) {
    header("Location: inscriptions.php");
    exit();
}

// Récupération des données
$etudiants = $conn->query("SELECT * FROM etudiant ORDER BY nom ASC")->fetch_all(MYSQLI_ASSOC);
$classes = $conn->query("SELECT * FROM classe ORDER BY nom ASC")->fetch_all(MYSQLI_ASSOC);
$annees = $conn->query("SELECT * FROM annee_scolaire ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);

// Traitement modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $etudiant_id = intval($_POST['etudiant_id']);
    $classe_id = intval($_POST['classe_id']);
    $annee_id = intval($_POST['annee_id']);

    $stmt = $conn->prepare("UPDATE inscription SET etudiant_id = ?, classe_id = ?, annee_id = ? WHERE id = ?");
    $stmt->bind_param("iiii", $etudiant_id, $classe_id, $annee_id, $id);
    if ($stmt->execute()) {
        header("Location: inscriptions.php?update=1");
        exit();
    } else {
        $error = "Erreur lors de la modification.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f2f2f2;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        select, input[type="submit"], a.button {
            padding: 10px;
            width: 100%;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        a.button {
            display: inline-block;
            text-align: center;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
        }
        a.button:hover {
            background-color: #5a6268;
        }
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">Modifier l'inscription</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Étudiant :</label>
        <select name="etudiant_id" required>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id'] == $inscription['etudiant_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['matricule'] . " - " . $e['nom'] . " " . $e['prenom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Classe :</label>
        <select name="classe_id" required>
            <?php foreach ($classes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $inscription['classe_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Année scolaire :</label>
        <select name="annee_id" required>
            <?php foreach ($annees as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $a['id'] == $inscription['annee_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="💾 Enregistrer les modifications">
        <a href="inscriptions.php" class="button">↩️ Annuler</a>
    </form>

</body>
</html>
