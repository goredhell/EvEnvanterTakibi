<?php
include 'auth.php';
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('<div class="container mt-4"><div class="alert alert-danger">Geçersiz konum ID.</div></div>');
}

// Konum var mı?
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE id = ?");
$stmt->execute([$id]);
$konum = $stmt->fetch();

if (!$konum) {
    die('<div class="container mt-4"><div class="alert alert-warning">Konum bulunamadı.</div></div>');
}

// Alt konum var mı?
$stmt = $pdo->prepare("SELECT COUNT(*) FROM konumlar WHERE ebeveyn_id = ?");
$stmt->execute([$id]);
$alt_sayisi = $stmt->fetchColumn();

// Ürün var mı?
$stmt = $pdo->prepare("SELECT COUNT(*) FROM urunler WHERE konum_id = ?");
$stmt->execute([$id]);
$urun_sayisi = $stmt->fetchColumn();

// Silme kontrolü
if ($alt_sayisi > 0 || $urun_sayisi > 0) {
    echo '<div class="container mt-4">';
    echo '<div class="alert alert-danger">';
    echo 'Bu konum silinemez çünkü ';
    if ($alt_sayisi > 0) echo $alt_sayisi . ' alt konum, ';
    if ($urun_sayisi > 0) echo $urun_sayisi . ' ürün ';
    echo 'bulunmaktadır.';
    echo '</div>';
    echo '<a href="konumlar.php" class="btn btn-secondary">← Geri Dön</a>';
    echo '</div>';
    exit;
}

// Sil
$stmt = $pdo->prepare("DELETE FROM konumlar WHERE id = ?");
$stmt->execute([$id]);

header("Location: konumlar.php");
exit;
