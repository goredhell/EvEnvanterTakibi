<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Geçersiz bağlantı.</div></div>';
    exit;
}

// Konumu al
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE token = ?");
$stmt->execute([$token]);
$konum = $stmt->fetch();

if (!$konum) {
    echo '<div class="container mt-4"><div class="alert alert-warning">Konum bulunamadı.</div></div>';
    exit;
}
?>

<div class="container mt-4 text-center">
    <h3>🔳 <?= htmlspecialchars($konum['ad']) ?> – QR Kod</h3>

    <img class="my-4 border border-dark rounded"
         src="qr_goster.php?token=<?= urlencode($token) ?>"
         alt="QR Kod">

    <p class="text-muted">Bu QR kodu ilgili kutuya yapıştırarak hızlı erişim sağlayabilirsiniz.</p>
    <a href="konum_detay.php?id=<?= $konum['id'] ?>" class="btn btn-secondary">← Geri Dön</a>
</div>
