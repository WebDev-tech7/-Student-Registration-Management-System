<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID étudiant manquant.";
    exit();
}

$id = intval($_GET['id']);

// Récupérer les classes
$classes = $conn->query("SELECT * FROM classe")->fetch_all(MYSQLI_ASSOC);

// Récupérer les infos de l’étudiant
$stmt = $conn->prepare("SELECT * FROM etudiant WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$etudiant = $result->fetch_assoc();

if (!$etudiant) {
    echo "Étudiant introuvable.";
    exit();
}

// Mise à jour de l'étudiant
if (
    isset($_POST['matricule'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'], $_POST['sexe'], $_POST['classe_id'], $_POST['date_naissance'])
) {
    $stmt = $conn->prepare("UPDATE etudiant SET matricule = ?, nom = ?, prenom = ?, email = ?, telephone = ?, sexe = ?, date_naissance = ?, classe_id = ? WHERE id = ?");
    $stmt->bind_param(
        "sssssssii",
        $_POST['matricule'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['email'],
        $_POST['telephone'],
        $_POST['sexe'],
        $_POST['date_naissance'],
        $_POST['classe_id'],
        $id
    );
    $stmt->execute();
    header("Location: etudiants.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Étudiant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 40px;
        }
        h2 {
            color: #333;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, select {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Modifier l'étudiant</h2>

<form method="POST">
    <input type="text" name="matricule" value="<?= htmlspecialchars($etudiant['matricule']) ?>" required>
    <input type="text" name="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>
    <input type="text" name="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($etudiant['email']) ?>" required>
    <input type="tel" name="telephone" value="<?= htmlspecialchars($etudiant['telephone']) ?>" required>
    <input type="date" name="date_naissance" value="<?= htmlspecialchars($etudiant['date_naissance']) ?>" required>
    <select name="sexe" required>
        <option value="masculin" <?= $etudiant['sexe'] === 'masculin' ? 'selected' : '' ?>>Masculin</option>
        <option value="feminin" <?= $etudiant['sexe'] === 'feminin' ? 'selected' : '' ?>>Féminin</option>
    </select>
    <select name="classe_id" required>
        <?php foreach ($classes as $classe): ?>
            <option value="<?= $classe['id'] ?>" <?= $classe['id'] == $etudiant['classe_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($classe['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Enregistrer les modifications">
</form>

<a href="etudiants.php">← Retour à la liste des étudiants</a>

</body>
</html>

