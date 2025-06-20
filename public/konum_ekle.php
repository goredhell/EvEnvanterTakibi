<?php
include 'auth.php';
include 'db.php';

// POST isteği geldiğinde konumu ekle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'] ?? '';
    $ebeveyn_id = $_POST['ebeveyn_id'] ?? null;

    if (empty($ad)) {
        $hata = "Konum adı boş bırakılamaz.";
    } else {
        // Token oluştur (örnek: uniqid() + random)
        $token = bin2hex(random_bytes(8));

        $stmt = $pdo->prepare("INSERT INTO konumlar (ad, ebeveyn_id, token) VALUES (?, ?, ?)");
        $stmt->execute([$ad, $ebeveyn_id ?: null, $token]);

        header("Location: index.php");
        exit;
    }
}

// Tüm konumları çek — seçim listesi için
$stmt = $pdo->query("SELECT id, ad FROM konumlar ORDER BY ad");
$konumlar = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yeni Konum Ekle</title>
</head>
<body>
    <h2>Yeni Konum Ekle</h2>
    <form method="post">
        <label>Konum Adı:</label><br>
        <input type="text" name="ad" required><br><br>

        <label>Hangi konumun içinde (isteğe bağlı):</label><br>
        <select name="ebeveyn_id">
            <option value="">-- Üst Düzey Konum --</option>
            <?php foreach ($konumlar as $konum): ?>
                <option value="<?= $konum['id'] ?>"><?= htmlspecialchars($konum['ad']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Ekle</button>
    </form>

    <p><a href="index.php">← Geri Dön</a></p>

    <?php if (isset($hata)): ?>
        <p style="color:red;"><?= htmlspecialchars($hata) ?></p>
    <?php endif; ?>
</body>
</html>
