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

// Konum yolunu bul (ebeveyn zinciri)
function getKonumYolu($pdo, $konum) {
    $yol = [];
    while ($konum) {
        $yol[] = $konum;
        if (!$konum['ebeveyn_id']) break;

        $stmt = $pdo->prepare("SELECT * FROM konumlar WHERE id = ?");
        $stmt->execute([$konum['ebeveyn_id']]);
        $konum = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return array_reverse($yol);
}

// Alt konumları da içerecek şekilde tüm konum ID'lerini getir
function getAltKonumIdler($pdo, $ebeveyn_id) {
    $idler = [$ebeveyn_id];

    $stmt = $pdo->prepare("SELECT id FROM konumlar WHERE ebeveyn_id = ?");
    $stmt->execute([$ebeveyn_id]);
    $altlar = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($altlar as $alt_id) {
        $idler = array_merge($idler, getAltKonumIdler($pdo, $alt_id));
    }

    return $idler;
}

$konum_yolu = getKonumYolu($pdo, $konum);
$konum_idler = getAltKonumIdler($pdo, $konum['id']);

// Ürünleri al (tüm alt konumlar dahil)
$in_placeholders = implode(',', array_fill(0, count($konum_idler), '?'));
$stmt = $pdo->prepare("SELECT * FROM urunler WHERE konum_id IN ($in_placeholders)");
$stmt->execute($konum_idler);
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

    <div class="mb-3">
        <small class="text-muted">📍 Konum Yolu:</small><br>
        <div class="fw-semibold">
            <?php foreach ($konum_yolu as $i => $k): ?>
                <?php if ($i > 0): ?> &gt; <?php endif; ?>
                <?= htmlspecialchars($k['ad']) ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">🧾 Bu Konum ve Alt Konumlardaki Ürünler</h5>
            <?php if (count($urunler) === 0): ?>
                <p class="text-muted">Hiç ürün bulunamadı.</p>
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
