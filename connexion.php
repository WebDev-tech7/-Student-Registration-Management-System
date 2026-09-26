<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Préparer la requête avec mysqli
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE username = ?");
    $stmt->bind_param("s", $username); // "s" = string
    $stmt->execute();

    // Obtenir le résultat
    $result = $stmt->get_result();
    $utilisateur = $result->fetch_assoc();

    // Vérifier le mot de passe
    if ($utilisateur && password_verify($password, $utilisateur['password'])) {
        $_SESSION['utilisateur'] = $utilisateur['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: index.php?error=1");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
