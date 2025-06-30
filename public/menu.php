<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$admin_mi = $_SESSION['admin'] ?? 0;
?>

<nav style="padding: 10px; background-color: #eee; margin-bottom: 20px;">
    <a href="index.php">🏠 Ana Sayfa</a> |
	<a href="konum.php">📦 Envanter</a> |
	<a href="urun_ara.php">🔍 Ürün Ara</a> |
    <a href="kullanicilar.php">👤 Kullanıcılar</a> |
    <?php if ($admin_mi): ?>
        <a href="kullanici_ekle.php">➕ Kullanıcı Ekle</a> |
    <?php endif; ?>
    <a href="sifre_degistir.php">🔐 Şifre Değiştir</a> |
    <a href="logout.php">🚪 Çıkış Yap</a>
</nav>
