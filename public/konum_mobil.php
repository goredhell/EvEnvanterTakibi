<?php
include 'db.php';

$token = $_GET['token'] ?? '';
if (!$token) {
    echo "<div style='padding:20px;'>Geçersiz bağlantı.</div>";
    exit;
}

// Konumu al
$stmt = $pdo->prepare("SELECT * FROM konumlar WHERE token = ?");
$stmt->execute([$token]);
$konum = $stmt->fetch();

if (!$konum) {
    echo "<div style='padding:20px;'>Konum bulunamadı.</div>";
    exit;
}

// Ürünleri al
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE konum_id = ?");
$stmt->execute([$konum['id']]);
$urunler = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($konum['ad']) ?> | Konum Görüntüleme</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width:600px;">
    <h4 class="mb-3 text-center">📦 <?= htmlspecialchars($konum['ad']) ?></h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">🧾 Bu Konumda Bulunan Ürünler</h5>
            <?php if (count($urunler) === 0): ?>
                <p class="text-muted">Bu konumda kayıtlı ürün bulunmuyor.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($urunler as $u): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <strong><?= htmlspecialchars($u['ad']) ?></strong>
                                <?php if ($u['aciklama']): ?>
                                    <div class="text-muted small"><?= htmlspecialchars($u['aciklama']) ?></div>
                                <?php endif; ?>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                <?= ($u['adet'] !== null) ? intval($u['adet']) . ' adet' : '—' ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center">
        <a href="/" class="btn btn-outline-secondary btn-sm">← Ana Sayfa</a>
    </div>
</div>

</body>
</html>
