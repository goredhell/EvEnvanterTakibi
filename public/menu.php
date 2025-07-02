<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$admin_mi = $_SESSION['admin'] ?? 0;
?>

<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">📦 Envanter</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">🏠 Ana Sayfa</a></li>
        <li class="nav-item"><a class="nav-link" href="kullanicilar.php">👤 Kullanıcılar</a></li>
        <?php if ($admin_mi): ?>
          <li class="nav-item"><a class="nav-link" href="kullanici_ekle.php">➕ Kullanıcı Ekle</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="sifre_degistir.php">🔐 Şifre Değiştir</a></li>
        <li class="nav-item"><a class="nav-link" href="konumlar.php">📁 Konumlar</a></li>
        <li class="nav-item"><a class="nav-link" href="urunler.php">📄 Ürünler</a></li>
		<li class="nav-item"><a class="nav-link" href="qr_toplu.php">🔳 Toplu QR Yazdır</a></li>
        <li class="nav-item"><a class="nav-link" href="urun_ara.php">🔍 Ürün Ara</a></li>
		<li class="nav-item"><a class="nav-link" href="arama.php">🔍 Detay Ara</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Çıkış Yap</a></li>
      </ul>
    </div>
  </div>
</nav>
