<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Geçersiz ürün ID.</div></div>';
    exit;
}

// Ürünü al
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE id = ?");
$stmt->execute([$id]);
$urun = $stmt->fetch();

if (!$urun) {
    echo '<div class="container mt-4"><div class="alert alert-warning">Ürün bulunamadı.</div></div>';
    exit;
}

// Konumlar
$stmt = $pdo->query("SELECT id, ad FROM konumlar ORDER BY ad ASC");
$konumlar = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $konum_id = $_POST['konum_id'] !== '' ? (int)$_POST['konum_id'] : null;
	$adet = is_numeric($_POST['adet'] ?? null) ? (int)$_POST['adet'] : null;

    if ($ad === '') {
        $hata = "Ürün adı zorunludur.";
    } else {
        $stmt = $pdo->prepare("UPDATE urunler SET ad = ?, aciklama = ?, konum_id = ?, adet = ? WHERE id = ?");
		$stmt->execute([$ad, $aciklama, $konum_id, $adet, $id]);
        header("Location: urunler.php");
        exit;
    }
}
?>

<div class="container mt-4">
    <h3>✏️ Ürünü Düzenle</h3>

    <?php if (isset($hata)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Ürün Adı</label>
            <input type="text" name="ad" class="form-control" value="<?= htmlspecialchars($urun['ad']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="aciklama" class="form-control" rows="3"><?= htmlspecialchars($urun['aciklama']) ?></textarea>
        </div>
		
		<div class="mb-3">
			<label class="form-label">Adet (İsteğe Bağlı)</label>
			<input type="number" name="adet" class="form-control" min="1" value="<?= htmlspecialchars($urun['adet']) ?>">
		</div>

        <div class="mb-3">
            <label class="form-label">Konum</label>
            <select name="konum_id" class="form-select">
                <option value="">— Konum Seçin —</option>
                <?php foreach ($konumlar as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($urun['konum_id'] == $k['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['ad']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="urunler.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
