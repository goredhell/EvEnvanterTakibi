<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    echo "Geçersiz bağlantı.";
    exit;
}

// Token'e göre konumu bul
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE token = ?");
$stmt->execute([$token]);
$konum = $stmt->fetch();

if (!$konum) {
    echo "Konum bulunamadı.";
    exit;
}

// Alt konumları çek
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE ebeveyn_id = ? ORDER BY ad");
$stmt->execute([$konum['id']]);
$alt_konumlar = $stmt->fetchAll();

// Ürünleri çek
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE konum_id = ? ORDER BY ad");
$stmt->execute([$konum['id']]);
$urunler = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($konum['ad']) ?> - Konum</title>
</head>
<body>
    <h2>📦 <?= htmlspecialchars($konum['ad']) ?></h2>

    <p>
        <a href="konum_ekle.php?ebeveyn_id=<?= $konum['id'] ?>">+ Alt Konum Ekle</a> |
        <a href="urun_ekle.php?konum_id=<?= $konum['id'] ?>">+ Ürün Ekle</a> |
        <a href="qr.php?token=<?= urlencode($konum['token']) ?>" target="_blank">QR Kod</a> |
        <a href="index.php">🏠 Ana Sayfa</a>
    </p>

    <h3>📂 Alt Konumlar</h3>
    <?php if (count($alt_konumlar) > 0): ?>
        <ul>
            <?php foreach ($alt_konumlar as $alt): ?>
                <li>
                    <a href="konum.php?token=<?= urlencode($alt['token']) ?>">
                        <?= htmlspecialchars($alt['ad']) ?>
                    </a>
                    [QR: <a href="qr.php?token=<?= urlencode($alt['token']) ?>" target="_blank">Göster</a>]
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Bu konumun içinde alt konum yok.</p>
    <?php endif; ?>

    <h3>🎯 Ürünler</h3>
    <?php if (count($urunler) > 0): ?>
        <ul>
            <?php foreach ($urunler as $urun): ?>
                <li>
                    <strong><?= htmlspecialchars($urun['ad']) ?></strong><br>
                    <small><?= nl2br(htmlspecialchars($urun['aciklama'])) ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Bu konumda ürün bulunmuyor.</p>
    <?php endif; ?>
</body>
</html>
