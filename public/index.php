<?php
include 'auth.php';
include 'menu.php';
?>

<div class="container mt-4">
    <div class="alert alert-success">
        👋 Merhaba, <strong><?= htmlspecialchars($_SESSION['kullanici_adi']) ?></strong>! Hoş geldiniz.
    </div>

    <div class="row g-3">
        <div class="col-md-6 col-lg-4">
            <a href="konumlar.php" class="btn btn-outline-primary w-100 py-3">
                📁 Konumları Görüntüle
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="urunler.php" class="btn btn-outline-primary w-100 py-3">
                📄 Ürünleri Görüntüle
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="urun_ekle.php" class="btn btn-outline-success w-100 py-3">
                ➕ Yeni Ürün Ekle
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="urun_ara.php" class="btn btn-outline-secondary w-100 py-3">
                🔍 Ürün Ara
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="sifre_degistir.php" class="btn btn-outline-warning w-100 py-3">
                🔐 Şifreni Değiştir
            </a>
        </div>

        <?php if ($_SESSION['admin']): ?>
            <div class="col-md-6 col-lg-4">
                <a href="kullanicilar.php" class="btn btn-outline-dark w-100 py-3">
                    👥 Kullanıcı Yönetimi
                </a>
            </div>
        <?php endif; ?>

        <div class="col-md-6 col-lg-4">
            <a href="logout.php" class="btn btn-outline-danger w-100 py-3">
                🚪 Oturumu Kapat
            </a>
        </div>
    </div>
</div>
