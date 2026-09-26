<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php'; // contient $conn (objet mysqli)

$error = "";

// Ajouter une classe
if (isset($_POST['nom']) && !empty($_POST['nom'])) {
    $nom = $_POST['nom'];

    $stmt = $conn->prepare("INSERT INTO classe (nom) VALUES (?)");
    $stmt->bind_param("s", $nom);

    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        $error = "Erreur : cette classe existe déjà.";
    }
}

// Récupérer les classes existantes
$result = $conn->query("SELECT * FROM classe ORDER BY id DESC");
$classes = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Classes</title>

    <p><a href="etudiants.php" style="color: #007bff; text-decoration: none;">→ Aller à la gestion des etudiants</a></p>
    
   <p><a href="annees.php" style="display: inline-block; padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">← Retour </a></p>



    <style>
        body { font-family: Arial; padding: 20px; background: #f0f8ff; }
        h2 { color: #333; }
        input[type="text"] {
            padding: 10px; width: 200px;
        }
        input[type="submit"] {
            padding: 10px 15px; background: #007bff; color: white; border: none;
            border-radius: 5px;
        }
        table {
            margin-top: 20px; border-collapse: collapse; width: 50%;
        }
        th, td {
            padding: 10px; border: 1px solid #ccc; text-align: center;
        }
        .error { color: red; }
    </style>
</head>
<body>















    <h2>Gestion des Classes</h2>

<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
    <input type="text" name="nom" placeholder="Ex: Terminale S" required>
    <input type="submit" value="Ajouter">
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nom de la classe</th>
    </tr>
    <?php foreach ($classes as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['nom']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
