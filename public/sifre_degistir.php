<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eski = $_POST['eski'] ?? '';
    $yeni = $_POST['yeni'] ?? '';
    $yeni2 = $_POST['yeni2'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $stmt->execute([$_SESSION['kullanici_id']]);
    $kullanici = $stmt->fetch();

    if (!$kullanici || !password_verify($eski, $kullanici['sifre_hash'])) {
        $hata = "Eski şifre hatalı.";
    } elseif ($yeni !== $yeni2) {
        $hata = "Yeni şifreler uyuşmuyor.";
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

<div class="container mt-4">
    <h3>🔐 Şifre Değiştir</h3>

    <?php if (isset($hata)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php elseif (isset($mesaj)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>

    <form method="post" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Eski Şifre</label>
            <input type="password" name="eski" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Yeni Şifre</label>
            <input type="password" name="yeni" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Yeni Şifre (Tekrar)</label>
            <input type="password" name="yeni2" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Şifreyi Güncelle</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
