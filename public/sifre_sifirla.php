<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

if ($_SESSION['admin'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Bu sayfaya erişim izniniz yok.</div></div>';
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo '<div class="container mt-5"><div class="alert alert-warning">Geçersiz kullanıcı ID.</div></div>';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$stmt->execute([$id]);
$kullanici = $stmt->fetch();

if (!$kullanici) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Kullanıcı bulunamadı.</div></div>';
    exit;
}

$token = bin2hex(random_bytes(16)); // Token üret
$link = "https://aytek.tr/sifre_yenile.php?token=" . $token; // Gerçek domainini gir

// İleride bu tokeni veritabanında saklayarak kontrol edebiliriz
?>

<div class="container mt-4">
    <h3>🔁 Şifre Sıfırlama Bağlantısı</h3>

    <div class="alert alert-info">
        <strong><?= htmlspecialchars($kullanici['kullanici_adi']) ?></strong> adlı kullanıcı için geçici bağlantı:
    </div>

    <div class="mb-3">
        <input type="text" class="form-control" value="<?= $link ?>" readonly onclick="this.select();">
    </div>

    <p class="text-muted">Bu bağlantı ileride e-posta ile gönderilebilir. Şimdilik manuel olarak paylaşılmalıdır.</p>
    <a href="kullanicilar.php" class="btn btn-secondary">← Geri Dön</a>
</div>
