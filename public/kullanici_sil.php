<?php
include 'auth.php';
include 'db.php';

if ($_SESSION['admin'] != 1) {
    echo "Yetkiniz yok.";
    exit;
}

$id = $_GET['id'] ?? null;

if ($id == $_SESSION['kullanici_id']) {
    echo "Kendi hesabınızı silemezsiniz!";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM kullanicilar WHERE id = ?");
$stmt->execute([$id]);

header("Location: kullanicilar.php");
exit;
