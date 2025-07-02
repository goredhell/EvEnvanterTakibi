<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo '<div class="container mt-4"><div class="alert alert-danger">Geçersiz konum ID.</div></div>';
    exit;
}

// Konumu al
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE id = ?");
$stmt->execute([$id]);
$konum = $stmt->fetch();

if (!$konum) {
    echo '<div class="container mt-4"><div class="alert alert-warning">Konum bulunamadı.</div></div>';
    exit;
}

// Diğer konumlar (kendisi hariç)
$stmt = $pdo->prepare("SELECT id, ad FROM konumlar WHERE id != ? ORDER BY ad");
$stmt->execute([$id]);
$konumlar = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $ebeveyn_id = $_POST['ebeveyn_id'] !== '' ? (int)$_POST['ebeveyn_id'] : null;

    if ($ad === '') {
        $hata = "Konum adı zorunludur.";
    } else {
        $stmt = $pdo->prepare("UPDATE konumlar SET ad = ?, ebeveyn_id = ? WHERE id = ?");
        $stmt->execute([$ad, $ebeveyn_id, $id]);
        header("Location: konumlar.php");
        exit;
    }
}
?>

<div class="container mt-4">
    <h3>✏️ Konumu Düzenle</h3>

    <?php if (isset($hata)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="post" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Konum Adı</label>
            <input type="text" name="ad" class="form-control" value="<?= htmlspecialchars($konum['ad']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">İçinde Bulunduğu Konum</label>
            <select name="ebeveyn_id" class="form-select">
                <option value="">— Üst konum yok —</option>
                <?php foreach ($konumlar as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($konum['ebeveyn_id'] == $k['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['ad']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="konumlar.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
