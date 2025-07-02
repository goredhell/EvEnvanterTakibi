<?php
require_once 'phpqrcode.php'; // QR kütüphanesini dahil et
include 'db.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    http_response_code(400);
    exit("Geçersiz token.");
}

// Konumu kontrol et
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE token = ?");
$stmt->execute([$token]);
$konum = $stmt->fetch();

if (!$konum) {
    http_response_code(404);
    exit("Konum bulunamadı.");
}

// QR verisi
$base_url = "https://aytek.tr"; // kendi alan adını gir
$url = $base_url . "/konum.php?token=" . urlencode($token);
$base_url = "https://ALANADIN.COM"; // kendi alan adını gir
$url = $base_url . "/konum_mobil.php?token=" . urlencode($token);

header('Content-Type: image/png');
QRcode::png($url, false, QR_ECLEVEL_L, 8, 2); // kalite, boyut, kenarlık ayarları
exit;
