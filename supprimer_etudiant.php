<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID étudiant manquant.";
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM etudiant WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: etudiants.php");
exit();
?>
