<?php
include 'auth.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eski = $_POST['eski'] ?? '';
    $yeni = $_POST['yeni'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $stmt->execute([$_SESSION['kullanici_id']]);
    $kullanici = $stmt->fetch();

    if (!$kullanici || !password_verify($eski, $kullanici['sifre_hash'])) {
        $hata = "Eski şifre hatalı!";
    } elseif (strlen($yeni) < 5) {
        $hata = "Yeni şifre en az 5 karakter olmalı.";
    } else {
        $hash = password_hash($yeni, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE kullanicilar SET sifre_hash = ? WHERE id = ?");
        $stmt->execute([$hash, $_SESSION['kullanici_id']]);
        $mesaj = "Şifreniz başarıyla güncellendi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Şifre Değiştir</title></head>
<body>
<h2>Şifreni Değiştir</h2>

<form method="post">
    <label>Eski Şifre:</label><br>
    <input type="password" name="eski" required><br><br>

    <label>Yeni Şifre:</label><br>
    <input type="password" name="yeni" required><br><br>

    <button type="submit">Güncelle</button>
</form>

<p><a href="kullanicilar.php">← Geri Dön</a></p>

<?php if (isset($hata)): ?>
    <p style="color:red;"><?= htmlspecialchars($hata) ?></p>
<?php elseif (isset($mesaj)): ?>
    <p style="color:green;"><?= htmlspecialchars($mesaj) ?></p>
<?php endif; ?>
</body>
</html>
