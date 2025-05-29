<?php
include 'db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=:id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    header("Location: index.php");
}
