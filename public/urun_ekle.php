<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Tüm konumları çek (seçim için)
$stmt = $pdo->query("SELECT id, ad, ebeveyn_id FROM konumlar");
$konumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
$konumMap = [];
foreach ($konumlar as $k) {
    $konumMap[$k['id']] = $k;
}

// Hiyerarşik ad üretimi
function konumYolu($konum_id) {
    global $konumMap;
    $parcalar = [];
    while ($konum_id && isset($konumMap[$konum_id])) {
        array_unshift($parcalar, $konumMap[$konum_id]['ad']);
        $konum_id = $konumMap[$konum_id]['ebeveyn_id'];
    }
    return implode(" → ", $parcalar);
}

// Form gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urun_ad = trim($_POST['ad'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $konum_id = $_POST['konum_id'] ?? null;

    if ($urun_ad && $konum_id) {
        $stmt = $pdo->prepare("INSERT INTO urunler (ad, aciklama, konum_id) VALUES (?, ?, ?)");
        $stmt->execute([$urun_ad, $aciklama, $konum_id]);
        header("Location: urunler.php");
        exit;
    } else {
        $hata = "Ürün adı ve konum zorunludur.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yeni Ürün Ekle</title>
</head>
<body>
    <h2>➕ Yeni Ürün Ekle</h2>

    <?php if (isset($hata)): ?>
        <p style="color:red;"><?= htmlspecialchars($hata) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Ürün Adı:</label><br>
        <input type="text" name="ad" required><br><br>

        <label>Açıklama:</label><br>
        <textarea name="aciklama" rows="3" cols="40"></textarea><br><br>

        <label>Konum Seç:</label><br>
        <select name="konum_id" required>
            <option value="">-- Seçiniz --</option>
            <?php foreach ($konumlar as $k): ?>
                <option value="<?= $k['id'] ?>"><?= konumYolu($k['id']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Kaydet</button>
    </form>

    <p><a href="urunler.php">← Ürün Listesine Dön</a></p>
</body>
</html>
