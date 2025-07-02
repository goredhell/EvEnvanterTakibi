<?php
session_start();
if (isset($_SESSION['kullanici_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Yap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
          <h4>🔐 Giriş Yap</h4>
        </div>
        <div class="card-body">
          <?php if (isset($_GET['hata'])): ?>
            <div class="alert alert-danger">Kullanıcı adı veya şifre hatalı.</div>
          <?php endif; ?>

          <form method="post" action="login_post.php">
            <div class="mb-3">
              <label class="form-label">Kullanıcı Adı</label>
              <input type="text" name="kullanici_adi" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Şifre</label>
              <input type="password" name="sifre" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>
