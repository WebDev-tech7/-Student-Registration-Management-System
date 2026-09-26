<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php'; // contient la variable $conn (mysqli)

$error = "";

// Ajouter une année scolaire
if (isset($_POST['libelle']) && !empty($_POST['libelle'])) {
    $libelle = $_POST['libelle'];

    $stmt = $conn->prepare("INSERT INTO annee_scolaire (libelle) VALUES (?)");
    $stmt->bind_param("s", $libelle);
    
    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        $error = "Erreur : année déjà existante.";
    }
}

// Récupérer toutes les années
$result = $conn->query("SELECT * FROM annee_scolaire ORDER BY id DESC");
$annees = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Années Scolaires</title>



<p><a href="classes.php" style="color: #007bff; text-decoration: none;">→ Aller à la gestion des classes</a></p>


<p><a href="dashboard.php" style="display: inline-block; padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">← Retour </a></p>




    <style>
        body { font-family: Arial; padding: 20px; background: #f9f9f9; }
        h2 { color: #333; }
        form input[type="text"] {
            padding: 10px; width: 200px; margin-right: 10px;
        }
        form input[type="submit"] {
            padding: 10px 15px; background: #28a745; color: white; border: none;
            border-radius: 5px;
        }
        table { margin-top: 20px; border-collapse: collapse; width: 50%; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        .error { color: red; }
    </style>


 









</head>
<body>














<h2>Gestion des Années Scolaires</h2>

<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
    <input type="text" name="libelle" placeholder="Ex: 2024-2025" required>
    <input type="submit" value="Ajouter">
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Libellé</th>
    </tr>
    <?php foreach ($annees as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= htmlspecialchars($a['libelle']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
