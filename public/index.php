<?php
include 'auth.php';
include 'menu.php';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Envanter Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="alert alert-success text-center">
        👋 Merhaba, <strong><?= htmlspecialchars($_SESSION['kullanici_adi']) ?></strong>! Hoş geldiniz.
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-md-4">
            <a href="konumlar.php" class="btn btn-outline-primary w-100 py-3 shadow-sm">
                📁 Konumları Görüntüle
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <a href="urunler.php" class="btn btn-outline-primary w-100 py-3 shadow-sm">
                📄 Ürünleri Görüntüle
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <a href="urun_ekle.php" class="btn btn-outline-success w-100 py-3 shadow-sm">
                ➕ Yeni Ürün Ekle
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <a href="urun_ara.php" class="btn btn-outline-secondary w-100 py-3 shadow-sm">
                🔍 Ürün / Konum Ara
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <a href="sifre_degistir.php" class="btn btn-outline-warning w-100 py-3 shadow-sm">
                🔐 Şifreni Değiştir
            </a>
        </div>

        <?php if ($_SESSION['admin']): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <a href="kullanicilar.php" class="btn btn-outline-dark w-100 py-3 shadow-sm">
                    👥 Kullanıcı Yönetimi
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <a href="qr_toplu.php" class="btn btn-outline-info w-100 py-3 shadow-sm">
                    🖨️ Toplu QR Yazdır
                </a>
            </div>
        <?php endif; ?>

        <div class="col-12 col-sm-6 col-md-4">
            <a href="logout.php" class="btn btn-outline-danger w-100 py-3 shadow-sm">
                🚪 Oturumu Kapat
            </a>
        </div>
    </div>
</div>

</body>
</html>
