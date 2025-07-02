<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Hiyerarşik konumları yazdıran fonksiyon
function konumlariListeleAgac($pdo, $ebeveyn_id = null, $seviye = 0) {
    $stmt = $pdo->prepare("SELECT * FROM konumlar WHERE ebeveyn_id " . ($ebeveyn_id === null ? "IS NULL" : "= ?") . " ORDER BY ad");
    $stmt->execute($ebeveyn_id === null ? [] : [$ebeveyn_id]);
    $liste = $stmt->fetchAll();

    foreach ($liste as $konum) {
        $girinti = str_repeat("-", $seviye);
        $style = $seviye === 0 ? ' style="font-weight:bold"' : '';
        echo '<option value="' . $konum['id'] . '"' . $style . '>' . $girinti . htmlspecialchars($konum['ad']) . '</option>';
        konumlariListeleAgac($pdo, $konum['id'], $seviye + 1);
    }
}


// Ürün ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $konum_id = (int)($_POST['konum_id'] ?? 0);
    $adet = $_POST['adet'] !== '' ? (int)$_POST['adet'] : null;

    if ($ad === '' || $konum_id === 0) {
        $hata = "Lütfen gerekli alanları doldurun.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO urunler (ad, aciklama, konum_id, adet) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ad, $aciklama, $konum_id, $adet]);
        header("Location: urunler.php");
        exit;
    }
}
?>

<div class="container mt-4" style="max-width:600px;">
    <h3>➕ Yeni Ürün Ekle</h3>

    <?php if (isset($hata)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Ürün Adı</label>
            <input type="text" name="ad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Açıklama (İsteğe Bağlı)</label>
            <textarea name="aciklama" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Bulunduğu Konum</label>
            <select name="konum_id" class="form-select" required>
                <option value="">— Konum Seçin —</option>
                <?php konumlariListeleAgac($pdo); ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Adet (İsteğe Bağlı)</label>
            <input type="number" name="adet" class="form-control" min="0">
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="urunler.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
