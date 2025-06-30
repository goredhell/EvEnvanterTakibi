<?php
include 'db.php';

$uid = $_GET['uid'] ?? null;
$token = $_GET['token'] ?? null;

// Geçici kontrol – ileride token veritabanında doğrulanmalı
if (!$uid || !$token || strlen($token) < 10) {
    echo "Geçersiz bağlantı.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$stmt->execute([$uid]);
$kullanici = $stmt->fetch();

if (!$kullanici) {
    echo "Kullanıcı bulunamadı.";
    exit;
}

// Form gönderildiyse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yeni = $_POST['yeni'] ?? '';
    $yeni2 = $_POST['yeni2'] ?? '';

    if ($yeni !== $yeni2) {
        $hata = "Şifreler uyuşmuyor.";
    } elseif (strlen($yeni) < 5) {
        $hata = "Yeni şifre en az 5 karakter olmalı.";
    } else {
        $hash = password_hash($yeni, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE kullanicilar SET sifre_hash = ? WHERE id = ?");
        $stmt->execute([$hash, $uid]);
        $mesaj = "Şifre başarıyla güncellendi. Giriş yapabilirsiniz.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Parola Yenile</title></head>
<body>
    <h2>Parola Yenile</h2>

    <?php if (!isset($mesaj)): ?>
        <form method="post">
            <label>Yeni Şifre:</label><br>
            <input type="password" name="yeni" required><br><br>

            <label>Yeni Şifre (Tekrar):</label><br>
            <input type="password" name="yeni2" required><br><br>

            <button type="submit">Kaydet</button>
        </form>
    <?php endif; ?>

    <p><a href="login.php">← Giriş Sayfası</a></p>

    <?php if (isset($hata)): ?>
        <p style="color:red;"><?= htmlspecialchars($hata) ?></p>
    <?php elseif (isset($mesaj)): ?>
        <p style="color:green;"><?= htmlspecialchars($mesaj) ?></p>
    <?php endif; ?>
</body>
</html>
