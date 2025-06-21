<?php
include 'auth.php';
include 'db.php';

// Sadece admin erişebilir
if ($_SESSION['admin'] != 1) {
    echo "Bu sayfayı görüntüleme yetkiniz yok.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kadi = trim($_POST['kullanici_adi'] ?? '');
    $sifre = $_POST['sifre'] ?? '';
    $admin = ($_SESSION['admin'] == 1 && isset($_POST['admin'])) ? 1 : 0;

    if (strlen($kadi) < 3 || strlen($sifre) < 5) {
        $hata = "Kullanıcı adı veya şifre çok kısa.";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM kullanicilar WHERE kullanici_adi = ?");
        $stmt->execute([$kadi]);
        if ($stmt->fetchColumn() > 0) {
            $hata = "Bu kullanıcı adı zaten alınmış.";
        } else {
            $hash = password_hash($sifre, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO kullanicilar (kullanici_adi, sifre_hash, admin) VALUES (?, ?, ?)");
            $stmt->execute([$kadi, $hash, $admin]);
            header("Location: kullanicilar.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yeni Kullanıcı Ekle</title>
</head>
<body>
    <h2>Yeni Kullanıcı Ekle</h2>

    <form method="post">
        <label>Kullanıcı Adı:</label><br>
        <input type="text" name="kullanici_adi" required><br><br>

        <label>Şifre:</label><br>
        <input type="password" name="sifre" required><br><br>

        <?php if ($_SESSION['admin'] == 1): ?>
            <label><input type="checkbox" name="admin" value="1"> Admin Yetkisi</label><br><br>
        <?php endif; ?>

        <button type="submit">Kaydet</button>
    </form>

    <p><a href="kullanicilar.php">← Geri</a></p>

    <?php if (isset($hata)): ?>
        <p style="color:red;"><?= htmlspecialchars($hata) ?></p>
    <?php endif; ?>
</body>
</html>
