<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Konumları çek
$stmt = $pdo->query("SELECT id, ad FROM konumlar ORDER BY ad ASC");
$konumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $konum_id = $_POST['konum_id'] !== '' ? (int)$_POST['konum_id'] : null;

    if ($ad === '') {
        $hata = "Ürün adı zorunludur.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO urunler (ad, aciklama, konum_id) VALUES (?, ?, ?)");
        $stmt->execute([$ad, $aciklama, $konum_id]);
        header("Location: urunler.php");
        exit;
    }
}
?>

<div class="container mt-4">
    <h3>➕ Yeni Ürün Ekle</h3>

    <?php if (isset($hata)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="post" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Ürün Adı</label>
            <input type="text" name="ad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Açıklama (İsteğe Bağlı)</label>
            <textarea name="aciklama" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Konum Seç</label>
            <select name="konum_id" class="form-select">
                <option value="">— Konum Seçin —</option>
                <?php foreach ($konumlar as $k): ?>
                    <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['ad']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="urunler.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
