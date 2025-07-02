<?php
include 'db.php';
include 'menu.php'; // opsiyonel

$token = $_GET['token'] ?? '';
$hata = null;
$basarili = false;

// Burada token ile eşleşen kullanıcıyı veritabanından bulmamız gerekirdi.
// Bu demo versiyonunda doğrudan manuel kullanıcı ID alalım:
$kullanici_id = 1; // Geçici (gerçekte token tablosundan alınmalı)

// Gerçek uygulamada bu satırlar şöyle olmalı:
// $stmt = $pdo->prepare("SELECT id FROM kullanicilar WHERE sifre_token = ?");
// $stmt->execute([$token]);
// $kullanici = $stmt->fetch();
// if ($kullanici) { $kullanici_id = $kullanici['id']; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yeni = $_POST['yeni'] ?? '';
    $yeni2 = $_POST['yeni2'] ?? '';

    if ($yeni !== $yeni2) {
        $hata = "Yeni şifreler uyuşmuyor.";
    } elseif (strlen($yeni) < 5) {
        $hata = "Yeni şifre en az 5 karakter olmalı.";
    } else {
        $hash = password_hash($yeni, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE kullanicilar SET sifre_hash = ? WHERE id = ?");
        $stmt->execute([$hash, $kullanici_id]);

        // Token silinebilir (şimdilik yok)
        $basarili = true;
    }
}
?>

<div class="container mt-4" style="max-width: 500px;">
    <h3>🔁 Şifre Yenile</h3>

    <?php if ($basarili): ?>
        <div class="alert alert-success">Şifreniz başarıyla güncellendi. <a href="login.php">Giriş Yap</a></div>
    <?php else: ?>
        <?php if ($hata): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Yeni Şifre</label>
                <input type="password" name="yeni" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Yeni Şifre (Tekrar)</label>
                <input type="password" name="yeni2" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Şifreyi Güncelle</button>
        </form>
    <?php endif; ?>
</div>
