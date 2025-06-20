<?php
include 'auth.php';
include 'db.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    echo "Geçersiz bağlantı.";
    exit;
}

// Konumu veritabanından çek
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE token = ?");
$stmt->execute([$token]);
$konum = $stmt->fetch();

if (!$konum) {
    echo "Konum bulunamadı.";
    exit;
}

// QR kodda yönlenecek tam adres:
$base_url = "https://senin-domainin.com"; // burada kendi adresini kullan
$url = $base_url . "/konum.php?token=" . urlencode($konum['token']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($konum['ad']) ?> - QR Kod</title>
</head>
<body>
    <h2>QR Kod: <?= htmlspecialchars($konum['ad']) ?></h2>
    <img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=<?= urlencode($url) ?>" alt="QR Kod"><br><br>
    <p>Bu QR kodu kutuya yapıştır, okuttuğunda bu konuma ulaşılır.</p>
    <p><a href="konum.php?token=<?= urlencode($konum['token']) ?>">↩ Geri Dön</a></p>
</body>
</html>
