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

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>👤 Kullanıcılar</h3>
        <?php if ($admin_mi): ?>
            <a href="kullanici_ekle.php" class="btn btn-success btn-sm">➕ Yeni Kullanıcı Ekle</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Admin?</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($kullanicilar as $k): ?>
                    <tr>
                        <td><?= htmlspecialchars($k['id']) ?></td>
                        <td><?= htmlspecialchars($k['kullanici_adi']) ?></td>
                        <td><?= $k['admin'] ? '✅' : '❌' ?></td>
                        <td>
                            <?php if ($_SESSION['kullanici_id'] == $k['id']): ?>
                                <a href="sifre_degistir.php" class="btn btn-outline-primary btn-sm">🔐 Şifremi Değiştir</a>
                            <?php elseif ($admin_mi): ?>
                                <a href="sifre_sifirla.php?id=<?= $k['id'] ?>" class="btn btn-outline-warning btn-sm">🔁 Parola Sıfırla</a>
                                <a href="kullanici_sil.php?id=<?= $k['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Kullanıcı silinsin mi?')">🗑 Sil</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
