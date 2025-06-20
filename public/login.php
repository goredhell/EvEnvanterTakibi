<?php
session_start();
if (isset($_SESSION['kullanici_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Giriş Yap</title>
</head>
<body>
    <h2>Giriş Yap</h2>
    <?php if (isset($_GET['hata'])): ?>
        <p style="color:red;">Kullanıcı adı veya şifre hatalı</p>
    <?php endif; ?>
    <form method="post" action="login_post.php">
        <label>Kullanıcı Adı:</label><br>
        <input type="text" name="kullanici_adi" required><br><br>
        <label>Şifre:</label><br>
        <input type="password" name="sifre" required><br><br>
        <button type="submit">Giriş Yap</button>
    </form>
</body>
</html>
