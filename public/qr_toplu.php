<?php
include 'auth.php';
include 'db.php';
require_once 'phpqrcode.php'; // Mevcut QR kütüphanen

// Tüm konumları çek
$stmt = $pdo->query("SELECT ad, token FROM konumlar ORDER BY ad");
$konumlar = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Toplu QR Kodlar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: sans-serif; }
        .qr-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .qr-item {
            width: 30%;
            min-width: 200px;
            text-align: center;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 2px 2px 8px #eee;
        }
        .qr-item img {
            max-width: 100%;
            height: auto;
        }
        @media print {
            .qr-item { page-break-inside: avoid; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

<div style="padding:20px;">
    <h3>📦 Tüm Konumlar İçin QR Kodlar</h3>
    <p>Her QR kodun altında konum adı yer alır. Yazdırmaya uygun görünüm.</p>

    <div class="qr-grid">
        <?php
        // QR görüntülerinin geçici olarak saklanacağı klasör
        $qr_klasor = __DIR__ . '/temp_qr/';
        if (!is_dir($qr_klasor)) {
            mkdir($qr_klasor, 0755, true);
        }

        foreach ($konumlar as $konum):
            $qr_text = "https://aytek.tr/konum_mobil.php?token=" . urlencode($konum['token']); // Domaini değiştir
            $dosya_adi = 'qr_' . md5($konum['token']) . '.png';
            $tam_yol = $qr_klasor . $dosya_adi;
            $gosterim_yolu = 'temp_qr/' . $dosya_adi;

            if (!file_exists($tam_yol)) {
                QRcode::png($qr_text, $tam_yol, QR_ECLEVEL_L, 4);
            }
        ?>
            <div class="qr-item">
                <img src="<?= htmlspecialchars($gosterim_yolu) ?>" alt="QR Kod">
                <div class="mt-2 fw-bold"><?= htmlspecialchars($konum['ad']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
