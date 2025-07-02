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

<div class="container mt-4">
    <h3>📦 <?= htmlspecialchars($konum['ad']) ?> – Konum Detayı</h3>

    <div class="mb-3">
        <label class="form-label fw-bold">QR Bağlantısı:</label><br>
        <a href="qr.php?token=<?= urlencode($konum['token']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
            🔳 QR Kodunu Göster
        </a>
    </div>

    <div class="mb-4">
        <h5>📁 Alt Konumlar</h5>
        <?php if (count($alt_konumlar) === 0): ?>
            <div class="text-muted">Alt konum yok.</div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($alt_konumlar as $alt): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($alt['ad']) ?>
                        <a href="konum_detay.php?id=<?= $alt['id'] ?>" class="btn btn-sm btn-outline-primary">Detay</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

<div class="mb-4">
    <h5>📄 Ürünler</h5>
    <?php if (count($urunler) === 0): ?>
        <div class="text-muted">Bu konumda ürün bulunmuyor.</div>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($urunler as $u): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <strong><?= htmlspecialchars($u['ad']) ?></strong>
                        <?php if ($u['aciklama']): ?>
                            – <?= htmlspecialchars($u['aciklama']) ?>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        <?= ($u['adet'] !== null) ? intval($u['adet']) . ' adet' : '—' ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

    <a href="konumlar.php" class="btn btn-secondary">← Tüm Konumlara Dön</a>
</div>
