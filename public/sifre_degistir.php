<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$hata = '';
$basari = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mevcut = $_POST['mevcut_sifre'] ?? '';
    $yeni1 = $_POST['yeni_sifre1'] ?? '';
    $yeni2 = $_POST['yeni_sifre2'] ?? '';

    // Kullanıcıyı getir
    $stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $stmt->execute([$_SESSION['kullanici_id']]);
    $kullanici = $stmt->fetch();

    if (!$kullanici || !password_verify($mevcut, $kullanici['sifre_hash'])) {
        $hata = 'Mevcut şifre hatalı.';
    } elseif ($yeni1 !== $yeni2) {
        $hata = 'Yeni şifreler eşleşmiyor.';
    } elseif (strlen($yeni1) < 6) {
        $hata = 'Yeni şifre en az 6 karakter olmalı.';
    } else {
        $yeni_hash = password_hash($yeni1, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE kullanicilar SET sifre_hash = ? WHERE id = ?");
        $stmt->execute([$yeni_hash, $_SESSION['kullanici_id']]);
        $basari = 'Şifreniz başarıyla güncellendi.';
    }
}
?>

<div class="container mt-4" style="max-width: 500px;">
    <h3>🔐 Şifre Değiştir</h3>

    <?php if ($hata): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php elseif ($basari): ?>
        <div class="alert alert-success"><?= htmlspecialchars($basari) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Mevcut Şifreniz</label>
            <input type="password" name="mevcut_sifre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Yeni Şifre</label>
            <input type="password" name="yeni_sifre1" class="form-control" required minlength="6">
        </div>

        <div class="mb-3">
            <label class="form-label">Yeni Şifre (Tekrar)</label>
            <input type="password" name="yeni_sifre2" class="form-control" required minlength="6">
        </div>

        <button type="submit" class="btn btn-primary">Şifreyi Güncelle</button>
    </form>
</div>
