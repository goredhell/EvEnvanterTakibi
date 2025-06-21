<?php
include 'auth.php';
include 'db.php';

if ($_SESSION['admin'] != 1) {
    echo "Bu sayfaya erisiminiz yok.";
    exit;
}

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$stmt->execute([$id]);
$kullanici = $stmt->fetch();

if (!$kullanici) {
    echo "Kullanici bulunamadi.";
    exit;
}

// Token uret (ornek olarak, simdilik link icinde ID ile gonderiyoruz)
$token = bin2hex(random_bytes(16));
// Bu token'i veritabaninda saklayabiliriz, simdilik sadece linkte gosteriyoruz

$link = "https://senin-domainin.com/sifre_yenile.php?uid=" . $kullanici['id'] . "&token=$token";
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Parola Sifirlama</title></head>
<body>
    <h3><?= htmlspecialchars($kullanici['kullanici_adi']) ?> icin parola sifirlama linki:</h3>
    <input type="text" style="width: 100%;" readonly value="<?= htmlspecialchars($link) ?>">
    <p>Bu linki kullaniciya iletebilirsiniz.</p>
    <p><a href="kullanicilar.php">? Geri</a></p>
</body>
</html>
