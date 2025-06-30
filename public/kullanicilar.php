<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

$admin_mi = ($_SESSION['admin'] == 1);

// Kullanıcıları getir
if ($admin_mi) {
    $stmt = $pdo->query("SELECT * FROM kullanicilar ORDER BY kullanici_adi");
} else {
    $stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $stmt->execute([$_SESSION['kullanici_id']]);
}

$kullanicilar = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Yönetimi</title>
</head>
<body>
    <h2>Kullanıcılar</h2>

    <p>
        <?php if ($admin_mi): ?>
            <a href="kullanici_ekle.php">+ Yeni Kullanıcı Ekle</a> |
        <?php endif; ?>
        <a href="index.php">🏠 Ana Sayfa</a>
    </p>

    <table border="1" cellpadding="6">
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Admin?</th>
            <th>İşlemler</th>
        </tr>
        <?php foreach ($kullanicilar as $k): ?>
            <tr>
                <td><?= htmlspecialchars($k['id']) ?></td>
                <td><?= htmlspecialchars($k['kullanici_adi']) ?></td>
                <td><?= $k['admin'] ? '✅' : '❌' ?></td>
                <td>
                    <?php if ($_SESSION['kullanici_id'] == $k['id']): ?>
                        <a href="sifre_degistir.php">🔐 Şifremi Değiştir</a>
                    <?php elseif ($admin_mi): ?>
                        <a href="sifre_sifirla.php?id=<?= $k['id'] ?>">🔁 Parola Sıfırla Linki</a> |
                        <a href="kullanici_sil.php?id=<?= $k['id'] ?>" onclick="return confirm('Kullanıcı silinsin mi?')">🗑 Sil</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
