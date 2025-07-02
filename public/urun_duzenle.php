<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Geçersiz ürün ID.</div></div>';
    exit;
}

// Ürünü çek
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE id = ?");
$stmt->execute([$id]);
$urun = $stmt->fetch();

if (!$urun) {
    echo '<div class="container mt-4"><div class="alert alert-warning">Ürün bulunamadı.</div></div>';
    exit;
}

// Konumları çek
$stmt = $pdo->query("SELECT id, ad FROM konumlar ORDER BY ad");
$konumlar = $stmt->fetchAll();

$hata = '';
$basari = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $adet = trim($_POST['adet'] ?? '');
    $konum_id = $_POST['konum_id'] ?? null;

    if ($ad === '') {
        $hata = 'Ürün adı boş olamaz.';
    } else {
        $stmt = $pdo->prepare("UPDATE urunler SET ad = ?, aciklama = ?, adet = ?, konum_id = ? WHERE id = ?");
        $stmt->execute([
            $ad,
            $aciklama,
            $adet !== '' ? $adet : null,
            $konum_id ?: null,
            $id
        ]);
        $basari = 'Ürün başarıyla güncellendi.';
        // Güncel bilgileri tekrar al
        $stmt = $pdo->prepare("SELECT * FROM urunler WHERE id = ?");
        $stmt->execute([$id]);
        $urun = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ürün Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h4 class="mb-4">✏️ Ürünü Düzenle</h4>

    <?php if ($hata): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php elseif ($basari): ?>
        <div class="alert alert-success"><?= htmlspecialchars($basari) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-12">
            <label for="ad" class="form-label">Ürün Adı <span class="text-danger">*</span></label>
            <input type="text" name="ad" id="ad" class="form-control" value="<?= htmlspecialchars($urun['ad']) ?>" required>
        </div>

        <div class="col-12">
            <label for="aciklama" class="form-label">Açıklama</label>
            <textarea name="aciklama" id="aciklama" class="form-control" rows="3"><?= htmlspecialchars($urun['aciklama']) ?></textarea>
        </div>

        <div class="col-12 col-md-6">
            <label for="adet" class="form-label">Adet</label>
            <input type="number" name="adet" id="adet" class="form-control" value="<?= htmlspecialchars($urun['adet']) ?>">
        </div>

        <div class="col-12 col-md-6">
            <label for="konum_id" class="form-label">Konum</label>
            <select name="konum_id" id="konum_id" class="form-select">
                <option value="">— Konum Seçin —</option>
                <?php foreach ($konumlar as $konum): ?>
                    <option value="<?= $konum['id'] ?>" <?= $konum['id'] == $urun['konum_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($konum['ad']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">💾 Kaydet</button>
        </div>
    </form>

    <div class="mt-4">
        <a href="urunler.php" class="btn btn-outline-secondary">← Ürün Listesine Dön</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
