<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$konum_id = $_GET['id'] ?? null;
if (!$konum_id) {
    echo "Konum ID eksik.";
    exit;
}

// Bu konumu çek
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE id = ?");
$stmt->execute([$konum_id]);
$konum = $stmt->fetch();

if (!$konum) {
    echo "Konum bulunamadı.";
    exit;
}

// Alt konumları çek
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE ebeveyn_id = ?");
$stmt->execute([$konum_id]);
$alt_konumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bu konumun içindeki ürünleri çek
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE konum_id = ?");
$stmt->execute([$konum_id]);
$urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($konum['ad']) ?> - Konum Detayı</title>
</head>
<body>
    <h2>📦 <?= htmlspecialchars($konum['ad']) ?> – Konum Detayı</h2>

    <p><strong>QR Token:</strong> <?= htmlspecialchars($konum['token']) ?></p>
    <p><a href="qr.php?token=<?= urlencode($konum['token']) ?>">🔳 QR Kodu Göster</a></p>

    <h3>📁 Alt Konumlar</h3>
    <?php if (count($alt_konumlar) === 0): ?>
        <p>Alt konum yok.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($alt_konumlar as $alt): ?>
                <li>
                    <a href="konum_detay.php?id=<?= $alt['id'] ?>">
                        <?= htmlspecialchars($alt['ad']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h3>📦 Bu Konumdaki Ürünler</h3>
    <?php if (count($urunler) === 0): ?>
        <p>Ürün bulunamadı.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($urunler as $u): ?>
                <li>
                    <strong><?= htmlspecialchars($u['ad']) ?></strong>
                    <?php if ($u['aciklama']): ?>
                        – <?= htmlspecialchars($u['aciklama']) ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="konum.php">← Tüm Konumlara Dön</a></p>
</body>
</html>
