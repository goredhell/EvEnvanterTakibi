<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$konum_id = $_GET['id'] ?? null;
if (!$konum_id) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Geçersiz konum ID.</div></div>';
    exit;
}

// Konumu çek
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE id = ?");
$stmt->execute([$konum_id]);
$konum = $stmt->fetch();

if (!$konum) {
    echo '<div class="container mt-4"><div class="alert alert-warning">Konum bulunamadı.</div></div>';
    exit;
}

// Alt konumlar
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE ebeveyn_id = ?");
$stmt->execute([$konum_id]);
$alt_konumlar = $stmt->fetchAll();

// Ürünler
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE konum_id = ?");
$stmt->execute([$konum_id]);
$urunler = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($konum['ad']) ?> - Konum Detayı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h4 class="mb-4">📦 <?= htmlspecialchars($konum['ad']) ?> – Konum Detayı</h4>

    <div class="mb-4">
        <label class="form-label fw-bold">🔳 QR Bağlantısı:</label><br>
        <a href="qr.php?token=<?= urlencode($konum['token']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
            QR Kodunu Göster
        </a>
    </div>

    <div class="mb-4">
        <h5>📁 Alt Konumlar</h5>
        <?php if (count($alt_konumlar) === 0): ?>
            <div class="text-muted">Alt konum yok.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($alt_konumlar as $alt): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <?= htmlspecialchars($alt['ad']) ?>
                        </div>
                        <div class="btn-group">
                            <a href="konum_detay.php?id=<?= $alt['id'] ?>" class="btn btn-sm btn-outline-primary">Detay</a>
                            <a href="konum_duzenle.php?id=<?= $alt['id'] ?>" class="btn btn-sm btn-outline-secondary">✏️</a>
                            <a href="konum_sil.php?id=<?= $alt['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Bu alt konumu silmek istediğinize emin misiniz?');">🗑️</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <h5>📄 Ürünler</h5>
        <?php if (count($urunler) === 0): ?>
            <div class="text-muted">Bu konumda ürün bulunmuyor.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($urunler as $u): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row">
                        <div>
                            <strong><?= htmlspecialchars($u['ad']) ?></strong>
                            <?php if ($u['aciklama']): ?>
                                – <?= htmlspecialchars($u['aciklama']) ?>
                            <?php endif; ?>
                            <?php if (!empty($u['adet'])): ?>
                                <div class="text-muted">Adet: <?= htmlspecialchars($u['adet']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="btn-group mt-2 mt-md-0">
                            <a href="urun_duzenle.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-secondary">✏️</a>
                            <a href="urun_sil.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">🗑️</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <a href="konumlar.php" class="btn btn-secondary">← Tüm Konumlara Dön</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
