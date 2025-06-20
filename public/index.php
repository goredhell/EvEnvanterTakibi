<?php
include 'auth.php';
include 'db.php';

// En üst düzey konumları çek (ebeveyni olmayanlar)
$stmt = $pdo->query("SELECT * FROM konumlar WHERE ebeveyn_id IS NULL ORDER BY ad");
$konumlar = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Envanter Yönetimi</title>
</head>
<body>
    <h2>Merhaba, <?= htmlspecialchars($_SESSION['kullanici_adi']) ?>!</h2>

    <p>
        <a href="konum_ekle.php">+ Yeni Konum Ekle</a> |
        <a href="logout.php">Çıkış Yap</a>
    </p>

    <h3>Üst Düzey Konumlar</h3>
    <ul>
        <?php foreach ($konumlar as $konum): ?>
            <li>
                <a href="konum.php?token=<?= urlencode($konum['token']) ?>">
                    <?= htmlspecialchars($konum['ad']) ?>
                </a>
                [QR: <a href="qr.php?token=<?= urlencode($konum['token']) ?>" target="_blank">Görüntüle</a>]
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
