<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Ürünleri ve konumlarını çek
$stmt = $pdo->query("
    SELECT u.*, k.ad AS konum_adi
    FROM urunler u
    LEFT JOIN konumlar k ON u.konum_id = k.id
    ORDER BY u.ad
");
$urunler = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ürünler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">📄 Tüm Ürünler</h4>
        <a href="urun_ekle.php" class="btn btn-success">➕ Yeni Ürün</a>
    </div>

    <?php if (count($urunler) === 0): ?>
        <div class="alert alert-warning">Henüz ürün eklenmemiş.</div>
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
                        <div class="text-muted">
                            📍 Konum:
                            <?php if ($u['konum_adi']): ?>
                                <a href="konum_detay.php?id=<?= $u['konum_id'] ?>" class="link-secondary">
                                    <?= htmlspecialchars($u['konum_adi']) ?>
                                </a>
                            <?php else: ?>
                                <em>Tanımsız</em>
                            <?php endif; ?>
                        </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
