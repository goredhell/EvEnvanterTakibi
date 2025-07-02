<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$arama = trim($_GET['q'] ?? '');
$sonuclar = [];

if ($arama !== '') {
    $stmt = $pdo->prepare("
        SELECT u.ad AS urun_adi, u.aciklama, k.ad AS konum_adi, k.id AS konum_id
        FROM urunler u
        LEFT JOIN konumlar k ON u.konum_id = k.id
        WHERE u.ad LIKE ?
        ORDER BY u.ad
    ");
    $stmt->execute(['%' . $arama . '%']);
    $sonuclar = $stmt->fetchAll();
}
?>

<div class="container mt-4">
    <h3>🔍 Ürün Ara</h3>

    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Ürün adını girin..." value="<?= htmlspecialchars($arama) ?>" required>
            <button type="submit" class="btn btn-primary">Ara</button>
        </div>
    </form>

    <?php if ($arama !== ''): ?>
        <h5 class="mb-3">Arama Sonuçları: "<strong><?= htmlspecialchars($arama) ?></strong>"</h5>

        <?php if (count($sonuclar) === 0): ?>
            <div class="alert alert-warning">Ürün bulunamadı.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($sonuclar as $u): ?>
                    <div class="list-group-item">
                        <strong><?= htmlspecialchars($u['urun_adi']) ?></strong>
                        <?php if ($u['aciklama']): ?>
                            – <?= htmlspecialchars($u['aciklama']) ?>
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
    <?php endif; ?>
</div>
