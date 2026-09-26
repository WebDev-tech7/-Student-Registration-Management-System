<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM inscription WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: inscriptions.php?success=1");
        exit();
    } else {
        header("Location: inscriptions.php?error=1");
        exit();
    }
} else {
    header("Location: inscriptions.php");
    exit();
}
