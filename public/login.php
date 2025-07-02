<?php
session_start();
include 'db.php';

$hata = $_GET['hata'] ?? null;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="min-width: 300px; max-width: 400px; width: 100%;">
        <h4 class="mb-4 text-center text-primary">🔐 Giriş Yap</h4>

        <?php if ($hata): ?>
            <div class="alert alert-danger">Kullanıcı adı veya şifre hatalı.</div>
        <?php endif; ?>

        <form method="post" action="login_kontrol.php">
            <div class="mb-3">
                <label for="kullanici_adi" class="form-label">Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" id="kullanici_adi" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label for="sifre" class="form-label">Şifre</label>
                <input type="password" name="sifre" id="sifre" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
        </form>
    </div>
</div>

</body>
</html>