<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

if ($_SESSION['admin'] != 1) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Bu sayfaya erişim yetkiniz yok.</div></div>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kadi = trim($_POST['kullanici_adi'] ?? '');
    $sifre = $_POST['sifre'] ?? '';
    $admin = isset($_POST['admin']) ? 1 : 0;

    if (strlen($kadi) < 3 || strlen($sifre) < 5) {
        $hata = "Kullanıcı adı veya şifre çok kısa.";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM kullanicilar WHERE kullanici_adi = ?");
        $stmt->execute([$kadi]);
        if ($stmt->fetchColumn() > 0) {
            $hata = "Bu kullanıcı adı zaten kullanılıyor.";
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

<div class="container mt-4">
    <h3>➕ Yeni Kullanıcı Ekle</h3>

    <?php if (isset($hata)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="post" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Kullanıcı Adı</label>
            <input type="text" name="kullanici_adi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Şifre</label>
            <input type="password" name="sifre" class="form-control" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="admin" id="admin">
            <label class="form-check-label" for="admin">Admin Yetkisi</label>
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="kullanicilar.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
