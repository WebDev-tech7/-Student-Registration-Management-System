


<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

$error = "";
$success = "";

// Récupération des données
$annees = $conn->query("SELECT * FROM annee_scolaire ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
$classes = $conn->query("SELECT * FROM classe ORDER BY nom ASC")->fetch_all(MYSQLI_ASSOC);
$etudiants = $conn->query("SELECT * FROM etudiant ORDER BY nom ASC")->fetch_all(MYSQLI_ASSOC);

// Traitement du formulaire
if (
    isset($_POST['etudiant_id'], $_POST['annee_id'], $_POST['classe_id']) &&
    !empty($_POST['etudiant_id']) &&
    !empty($_POST['annee_id']) &&
    !empty($_POST['classe_id'])
) {
    $etudiant_id = intval($_POST['etudiant_id']);
    $annee_id = intval($_POST['annee_id']);
    $classe_id = intval($_POST['classe_id']);

    // Vérification d'inscription existante
    $stmt = $conn->prepare("SELECT * FROM inscription WHERE etudiant_id = ? AND annee_id = ?");
    $stmt->bind_param("ii", $etudiant_id, $annee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existe = $result->fetch_assoc();

    if ($existe) {
        $error = "⚠️ Cet étudiant est déjà inscrit pour cette année scolaire.";
    } else {
        $stmt = $conn->prepare("INSERT INTO inscription (etudiant_id, annee_id, classe_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $etudiant_id, $annee_id, $classe_id);
        if ($stmt->execute()) {
            $success = "✅ Inscription enregistrée avec succès.";
        } else {
            $error = "❌ Une erreur est survenue lors de l'inscription.";
        }
    }
}


// Construction de la requête dynamique selon les filtres GET
$conditions = [];
$params = [];

if (!empty($_GET['etudiant_id'])) {
    $conditions[] = "i.etudiant_id = ?";
    $params[] = intval($_GET['etudiant_id']);
}

if (!empty($_GET['annee_id'])) {
    $conditions[] = "i.annee_id = ?";
    $params[] = intval($_GET['annee_id']);
}

if (!empty($_GET['classe_id'])) {
    $conditions[] = "i.classe_id = ?";
    $params[] = intval($_GET['classe_id']);
}

$where = "";
if (!empty($conditions)) {
    $where = "WHERE " . implode(" AND ", $conditions);
}

$sql = "
    SELECT i.id, e.matricule, e.nom, e.prenom, c.nom AS classe, a.libelle AS annee
    FROM inscription i
    JOIN etudiant e ON i.etudiant_id = e.id
    JOIN classe c ON i.classe_id = c.id
    JOIN annee_scolaire a ON i.annee_id = a.id
    $where
    ORDER BY i.id DESC
";

// Utilisation de requêtes préparées pour éviter l'injection
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $types = str_repeat("i", count($params)); // tous les paramètres sont des entiers
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$inscriptions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

















































?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscriptions / Réinscriptions</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
        h2, h3 { color: #333; }
        select, input[type="submit"] {
            padding: 10px; margin: 5px; width: 220px;
        }
        table {
            margin-top: 20px; border-collapse: collapse; width: 100%;
            background-color: white;
        }
        th, td {
            padding: 10px; border: 1px solid #ccc; text-align: center;
        }
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<p><a href="etudiants.php">&larr; Retour à la gestion des étudiants</a></p>

<h2>Inscriptions / Réinscriptions des Étudiants</h2>











<h3>Recherche d'inscription</h3>
<form method="GET">
    <select name="etudiant_id">
        <option value="">-- Étudiant --</option>
        <?php foreach ($etudiants as $et): ?>
            <option value="<?= $et['id'] ?>" <?= (isset($_GET['etudiant_id']) && $_GET['etudiant_id'] == $et['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($et['matricule'] . ' - ' . $et['nom'] . ' ' . $et['prenom']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="annee_id">
        <option value="">-- Année scolaire --</option>
        <?php foreach ($annees as $an): ?>
            <option value="<?= $an['id'] ?>" <?= (isset($_GET['annee_id']) && $_GET['annee_id'] == $an['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($an['libelle']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="classe_id">
        <option value="">-- Classe --</option>
        <?php foreach ($classes as $cl): ?>
            <option value="<?= $cl['id'] ?>" <?= (isset($_GET['classe_id']) && $_GET['classe_id'] == $cl['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cl['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="🔍 Rechercher">
</form>



























































<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
<?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

<form method="POST">
    <select name="etudiant_id" required>
        <option value="">-- Sélectionner un étudiant --</option>
        <?php foreach ($etudiants as $et): ?>
            <option value="<?= $et['id'] ?>">
                <?= htmlspecialchars($et['matricule'] . ' - ' . $et['nom'] . ' ' . $et['prenom']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="annee_id" required>
        <option value="">-- Sélectionner une année scolaire --</option>
        <?php foreach ($annees as $an): ?>
            <option value="<?= $an['id'] ?>"><?= htmlspecialchars($an['libelle']) ?></option>
        <?php endforeach; ?>
    </select>

    <select name="classe_id" required>
        <option value="">-- Sélectionner une classe --</option>
        <?php foreach ($classes as $cl): ?>
            <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['nom']) ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Valider l'inscription">
</form>

<h3>Inscriptions enregistrées</h3>

<table>
    <tr>
        <th>ID</th>
        <th>Matricule</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Classe</th>
        <th>Année</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($inscriptions as $ins): ?>
        <tr>
            <td><?= $ins['id'] ?></td>
            <td><?= htmlspecialchars($ins['matricule']) ?></td>
            <td><?= htmlspecialchars($ins['nom']) ?></td>
            <td><?= htmlspecialchars($ins['prenom']) ?></td>
            <td><?= htmlspecialchars($ins['classe']) ?></td>
            <td><?= htmlspecialchars($ins['annee']) ?></td>

        <td>
            <a href="modifier_inscription.php?id=<?= $ins['id'] ?>">✏️ Modifier</a> |
            <a href="supprimer_inscription.php?id=<?= $ins['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">🗑️ Supprimer</a>
        </td>

        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>

