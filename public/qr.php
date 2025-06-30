<?php
include 'auth.php';
include 'db.php';
include 'phpqrcode.php';  // kütüphane dosyasını dahil et

$token = $_GET['token'] ?? '';

if (!$token) {
    echo "Geçersiz bağlantı.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE token = ?");
$stmt->execute([$token]);
$konum = $stmt->fetch();

if (!$konum) {
    echo "Konum bulunamadı.";
    exit;
}

$base_url = "https://aytek.tr";
$url = $base_url . "/konum.php?token=" . urlencode($konum['token']);

header('Content-Type: image/png');
\QRcode::png($url, false, QR_ECLEVEL_L, 8, 2);
exit;
