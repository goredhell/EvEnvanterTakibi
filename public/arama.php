<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$arama = trim($_GET['q'] ?? '');
$urun_sonuclar = [];
$konum_sonuclar = [];

if ($arama !== '') {
    // Ürünlerde arama
    $stmt = $pdo->prepare("
        SELECT u.ad AS urun_adi, u.aciklama, u.adet, k.ad AS konum_adi, k.id AS konum_id
        FROM urunler u
        LEFT JOIN konumlar k ON u.konum_id = k.id
        WHERE u.ad LIKE ? OR u.aciklama LIKE ? OR k.ad LIKE ?
        ORDER BY u.ad
    ");
    $stmt->execute(['%' . $arama . '%', '%' . $arama . '%', '%' . $arama . '%']);
    $urun_sonuclar = $stmt->fetchAll();

    // Konumlarda arama
    $stmt = $pdo->prepare("
        SELECT id, ad FROM konumlar
        WHERE ad LIKE ?
        ORDER BY ad
    ");
    $stmt->execute(['%' . $arama . '%']);
    $konum_sonuclar = $stmt->fetchAll();
}
?>

<div class="container mt-4">
    <h3>🔍 Global Arama</h3>

    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Ürün, konum veya açıklama ara..." value="<?= htmlspecialchars($arama) ?>" required>
            <button type="submit" class="btn btn-primary">Ara</button>
        </div>
    </form>

    <?php if ($arama !== ''): ?>
        <h5 class="mb-3">Arama Sonuçları: "<strong><?= htmlspecialchars($arama) ?></strong>"</h5>

        <!-- Ürün Sonuçları -->
        <div class="mb-4">
            <h6>📄 Eşleşen Ürünler</h6>
            <?php if (count($urun_sonuclar) === 0): ?>
                <div class="text-muted">Eşleşen ürün bulunamadı.</div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($urun_sonuclar as $u): ?>
                        <div class="list-group-item">
                            <strong><?= htmlspecialchars($u['urun_adi']) ?></strong>
                            <?php if ($u['aciklama']): ?>
                                – <?= htmlspecialchars($u['aciklama']) ?>
                            <?php endif; ?>

                            <?php if ($u['adet'] !== null): ?>
                                <div><span class="badge bg-primary"><?= intval($u['adet']) ?> adet</span></div>
                            <?php endif; ?>

                            <div class="mt-1 text-muted">
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
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Konum Sonuçları -->
        <div class="mb-4">
            <h6>📦 Eşleşen Konumlar</h6>
            <?php if (count($konum_sonuclar) === 0): ?>
                <div class="text-muted">Eşleşen konum bulunamadı.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($konum_sonuclar as $k): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($k['ad']) ?>
                            <a href="konum_detay.php?id=<?= $k['id'] ?>" class="btn btn-sm btn-outline-primary">Detay</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
