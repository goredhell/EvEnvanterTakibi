<?php
include 'auth.php';
include 'db.php';

$konum_id = $_GET['konum_id'] ?? null;

if (!$konum_id) {
    echo "Geçersiz konum.";
    exit;
}

// Konum bilgisi
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE id = ?");
$stmt->execute([$konum_id]);
$konum = $stmt->fetch();

if (!$konum) {
    echo "Konum bulunamadı.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'] ?? '';
    $aciklama = $_POST['aciklama'] ?? '';

    if (empty($ad)) {
        $hata = "Ürün adı boş bırakılamaz.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO urunler (konum_id, ad, aciklama) VALUES (?, ?, ?)");
        $stmt->execute([$konum_id, $ad, $aciklama]);

        header("Location: konum.php?token=" . urlencode($konum['token']));
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($konum['ad']) ?> - Ürün Ekle</title>
</head>
<body>
    <h2>🎯 <?= htmlspecialchars($konum['ad']) ?> konumuna ürün ekle</h2>

    <form method="post">
        <label>Ürün Adı:</label><br>
        <input type="text" name="ad" required><br><br>

        <label>Açıklama (isteğe bağlı):</label><br>
        <textarea name="aciklama" rows="4" cols="40"></textarea><br><br>

        <button type="submit">Ürünü Ekle</button>
    </form>

    <p><a href="konum.php?token=<?= urlencode($konum['token']) ?>">← Geri Dön</a></p>

    <?php if (isset($hata)): ?>
        <p style="color:red;"><?= htmlspecialchars($hata) ?></p>
    <?php endif; ?>
</body>
</html>
