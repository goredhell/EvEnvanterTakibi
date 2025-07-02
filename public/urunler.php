<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Ürünleri konum adıyla birlikte al
$stmt = $pdo->query("
    SELECT u.id, u.ad, u.aciklama, u.adet, k.ad AS konum_ad
    FROM urunler u
    LEFT JOIN konumlar k ON u.konum_id = k.id
    ORDER BY u.ad
");
$urunler = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📄 Ürünler</h3>
        <a href="urun_ekle.php" class="btn btn-success">➕ Yeni Ürün Ekle</a>
    </div>

    <?php if (count($urunler) === 0): ?>
        <div class="alert alert-info">Henüz kayıtlı ürün bulunmuyor.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Açıklama</th>
						<th>Adet</th>
                        <th>Konum</th>
                        <th>İşlemler</th>
					</tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($urunler as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['ad']) ?></td>
                            <td><?= htmlspecialchars($u['aciklama']) ?: '-' ?></td>
							<td><?= htmlspecialchars($u['adet'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($u['konum_ad'] ?: 'Tanımsız') ?></td>
                            <td>
                                <a href="urun_duzenle.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-secondary me-1">Düzenle</a>
                                <a href="urun_sil.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
