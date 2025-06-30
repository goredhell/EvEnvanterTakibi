<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Tüm konumları çek (hiyerarşi için)
$stmt = $pdo->query("SELECT id, ad, ebeveyn_id FROM konumlar");
$konumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
$konumMap = [];
foreach ($konumlar as $k) {
    $konumMap[$k['id']] = $k;
}

// Konum yolunu oluştur
function konumYolu($konum_id) {
    global $konumMap;
    $parcalar = [];
    while ($konum_id && isset($konumMap[$konum_id])) {
        array_unshift($parcalar, $konumMap[$konum_id]['ad']);
        $konum_id = $konumMap[$konum_id]['ebeveyn_id'];
    }
    return implode(" → ", $parcalar);
}

// Arama
$arama = trim($_GET['q'] ?? '');
$sonuclar = [];

if ($arama !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM urunler 
        WHERE ad LIKE ?
        ORDER BY ad
    ");
    $stmt->execute(['%' . $arama . '%']);
    $sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ürün Arama</title>
</head>
<body>
    <h2>🔍 Ürün Arama</h2>

    <form method="get">
        <input type="text" name="q" value="<?= htmlspecialchars($arama) ?>" placeholder="örn: kablo, matkap, kitap..." size="40">
        <button type="submit">Ara</button>
    </form>

    <?php if ($arama !== ''): ?>
        <h3>Sonuçlar:</h3>
        <?php if (count($sonuclar) === 0): ?>
            <p>Sonuç bulunamadı.</p>
        <?php else: ?>
            <table border="1" cellpadding="6">
                <tr>
                    <th>Ürün</th>
                    <th>Açıklama</th>
                    <th>Konum</th>
                </tr>
                <?php foreach ($sonuclar as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['ad']) ?></td>
                        <td><?= nl2br(htmlspecialchars($u['aciklama'])) ?></td>
                        <td><?= konumYolu($u['konum_id']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
