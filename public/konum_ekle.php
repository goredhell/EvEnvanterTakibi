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


// Konum ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $ebeveyn_id = $_POST['ebeveyn_id'] !== '' ? (int)$_POST['ebeveyn_id'] : null;

    if ($ad === '') {
        $hata = "Konum adı boş olamaz.";
    } else {
        $token = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("INSERT INTO konumlar (ad, ebeveyn_id, token) VALUES (?, ?, ?)");
        $stmt->execute([$ad, $ebeveyn_id, $token]);
        header("Location: konumlar.php");
        exit;
    }
}
?>

<div class="container mt-4" style="max-width:600px;">
    <h3>➕ Yeni Konum Ekle</h3>

    <?php if (isset($hata)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="post" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Konum Adı</label>
            <input type="text" name="ad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">İçinde Bulunduğu Konum (İsteğe Bağlı)</label>
            <select name="ebeveyn_id" class="form-select">
                <option value="">— Üst konum yok —</option>
                <?php konumlariListeleAgac($pdo); ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="konumlar.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
