<?php
include 'auth.php';
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Geçersiz ürün ID.</div></div>';
    exit;
}

// Ürün var mı?
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE id = ?");
$stmt->execute([$id]);
$urun = $stmt->fetch();

if (!$urun) {
    echo '<div class="container mt-4"><div class="alert alert-warning">Ürün bulunamadı.</div></div>';
    exit;
}

// Sil
$stmt = $pdo->prepare("DELETE FROM urunler WHERE id = ?");
$stmt->execute([$id]);

header("Location: urunler.php");
exit;
