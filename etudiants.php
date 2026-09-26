<?php 
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

$error = "";

// Récupérer les classes pour le menu déroulant
$result = $conn->query("SELECT * FROM classe");
$classes = $result->fetch_all(MYSQLI_ASSOC);

// Ajouter un étudiant
if (
    isset($_POST['matricule'], $_POST['nom'], $_POST['prenom'], $_POST['classe_id'], $_POST['email'], $_POST['telephone'], $_POST['sexe'], $_POST['date_naissance']) &&
    !empty($_POST['matricule']) &&
    !empty($_POST['nom']) &&
    !empty($_POST['prenom']) &&
    !empty($_POST['classe_id']) &&
    !empty($_POST['email']) &&
    !empty($_POST['telephone']) &&
    !empty($_POST['sexe']) &&
    !empty($_POST['date_naissance'])
) {
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $sexe = $_POST['sexe'];
    $classe_id = $_POST['classe_id'];
    $date_naissance = $_POST['date_naissance'];

    $stmt = $conn->prepare("INSERT INTO etudiant (matricule, nom, prenom, email, telephone, sexe, classe_id, date_naissance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssis", $matricule, $nom, $prenom, $email, $telephone, $sexe, $classe_id, $date_naissance);

    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        $error = "Erreur : matricule ou email déjà utilisé.";
    }
}

// Récupérer les étudiants avec leur classe
$sql = "
    SELECT e.id, e.matricule, e.nom, e.prenom, e.email, e.telephone, e.sexe, e.date_naissance, c.nom AS classe
    FROM etudiant e
    JOIN classe c ON e.classe_id = c.id
    ORDER BY e.id DESC
";
$result = $conn->query($sql);
$etudiants = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Étudiants</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #fdfdfd; }
        h2 { color: #333; }
        input, select {
            padding: 10px; margin: 5px; width: 200px;
        }
        input[type="submit"] {
            background: #28a745; color: white; border: none; border-radius: 5px;
        }
        table {
            margin-top: 20px; border-collapse: collapse; width: 100%;
        }
        th, td {
            padding: 10px; border: 1px solid #ccc; text-align: center;
        }
        .error { color: red; }
        .actions a {
            padding: 5px 10px;
            text-decoration: none;
            margin: 0 3px;
            border-radius: 4px;
        }
        .edit { background-color: #007bff; color: white; }
        .delete { background-color: #dc3545; color: white; }
    </style>
    <script>
        function searchStudents() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("table tbody tr");

            rows.forEach(row => {
                const cells = row.querySelectorAll("td");
                let match = false;

                cells.forEach(cell => {
                    if (cell.innerText.toLowerCase().includes(input)) {
                        match = true;
                    }
                });

                row.style.display = match ? "" : "none";
            });
        }
    </script>
</head>
<body>

<h2>Gestion des Étudiants</h2>

<p><a href="inscriptions.php" style="color: #007bff; text-decoration: none;">→ Aller à la gestion des inscriptions</a></p>
<p><a href="classes.php" style="display: inline-block; padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">← Retour </a></p>

<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
    <input type="text" name="matricule" placeholder="Matricule" required>
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="tel" name="telephone" placeholder="Téléphone" required>
    <input type="date" name="date_naissance" required>
    <select name="sexe" required>
        <option value="">-- Sexe --</option>
        <option value="masculin">Masculin</option>
        <option value="feminin">Féminin</option>
    </select>
    <select name="classe_id" required>
        <option value="">-- Classe --</option>
        <?php foreach ($classes as $cl): ?>
            <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['nom']) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Ajouter">
</form>

<form onsubmit="event.preventDefault(); searchStudents();" style="margin-top: 20px;">
    <input type="text" id="searchInput" placeholder="Rechercher un étudiant..." style="width: 300px; padding: 10px;">
    <button type="submit" style="padding: 10px;">Rechercher</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date Naissance</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Sexe</th>
            <th>Classe</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($etudiants as $e): ?>
        <tr>
            <td><?= $e['id'] ?></td>
            <td><?= htmlspecialchars($e['matricule']) ?></td>
            <td><?= htmlspecialchars($e['nom']) ?></td>
            <td><?= htmlspecialchars($e['prenom']) ?></td>
            <td><?= htmlspecialchars($e['date_naissance']) ?></td>
            <td><?= htmlspecialchars($e['email']) ?></td>
            <td><?= htmlspecialchars($e['telephone']) ?></td>
            <td><?= htmlspecialchars($e['sexe']) ?></td>
            <td><?= htmlspecialchars($e['classe']) ?></td>
            <td class="actions">
                <a href="modifier_etudiant.php?id=<?= $e['id'] ?>" class="edit">Modifier</a>
                <a href="supprimer_etudiant.php?id=<?= $e['id'] ?>" class="delete" onclick="return confirm('Voulez-vous vraiment supprimer cet étudiant ?');">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
