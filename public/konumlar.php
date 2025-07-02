<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Konumları çek
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE ebeveyn_id IS NULL ORDER BY ad");
$stmt->execute();
$konumlar = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konumlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">📁 Tüm Konumlar</h4>
        <a href="konum_ekle.php" class="btn btn-success">➕ Yeni Konum</a>
    </div>

    <?php if (count($konumlar) === 0): ?>
        <div class="alert alert-warning">Henüz konum tanımlanmamış.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($konumlar as $konum): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <a href="konum_detay.php?id=<?= $konum['id'] ?>" class="fw-bold text-decoration-none">
                                <?= htmlspecialchars($konum['ad']) ?>
                            </a>
                        </div>
                        <div class="btn-group mt-2 mt-md-0">
                            <a href="konum_duzenle.php?id=<?= $konum['id'] ?>" class="btn btn-sm btn-outline-secondary">✏️</a>
                            <a href="konum_sil.php?id=<?= $konum['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu konumu silmek istediğinize emin misiniz?');">🗑️</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
