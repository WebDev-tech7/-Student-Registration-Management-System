

<?php
session_start();

if (isset($_SESSION['utilisateur'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Gestion des Inscriptions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-box input[type="submit"] {
            background-color: #007bff;
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

<div class="login-box">
    <h2>Connexion</h2>
    <?php
    if (isset($_GET['error'])) {
        echo "<div class='error'>Identifiants incorrects !</div>";
    }
    ?>
    <form method="POST" action="connexion.php">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit" value="Se connecter">
    </form>

 <!-- Lien vers l'inscription  -->
    <p style="text-align: center; margin-top: 10px;">
        <a href="register.php">Créer un compte</a>
    </p>   

         



</div>

</body>
</html>








