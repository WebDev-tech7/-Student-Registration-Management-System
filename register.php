<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Vérifier si l'utilisateur existe déjà
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->fetch_assoc()) {
        $error = "Nom d'utilisateur déjà utilisé.";
    } else {
        // Hacher le mot de passe et enregistrer l'utilisateur
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO utilisateur (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();

        // Rediriger vers la page de connexion après inscription
        header("Location: index.php?registered=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .register-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .register-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .register-box input[type="text"],
        .register-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .register-box input[type="submit"] {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Créer un compte</h2>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit" value="S'inscrire">
    </form>
    <p style="text-align: center; margin-top: 10px;">
        <a href="index.php">Déjà un compte ? Connexion</a>
    </p>
</div>

</body>
</html>
