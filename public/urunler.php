<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Tüm ürünleri ve konumlarını al
$stmt = $pdo->query("
    SELECT urunler.id, urunler.ad AS urun_adi, urunler.aciklama, urunler.konum_id,
           k1.ad AS konum_adi, k1.ebeveyn_id
    FROM urunler
    LEFT JOIN konumlar k1 ON urunler.konum_id = k1.id
");
$urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Konum hiyerarşisini kurmak için tüm konumları al
$stmt = $pdo->query("SELECT id, ad, ebeveyn_id FROM konumlar");
$konumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
$konumMap = [];
foreach ($konumlar as $k) {
    $konumMap[$k['id']] = $k;
}

// Konum yolu bulucu (recursive)
function konumYolu($konum_id) {
    global $konumMap;
    $parcalar = [];
    while ($konum_id && isset($konumMap[$konum_id])) {
        array_unshift($parcalar, $konumMap[$konum_id]['ad']);
        $konum_id = $konumMap[$konum_id]['ebeveyn_id'];
    }
    return implode(" → ", $parcalar);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ürün Listesi</title>
</head>
<body>
    <h2>📦 Ürünler</h2>

    <p><a href="urun_ekle.php">+ Yeni Ürün Ekle</a></p>

    <table border="1" cellpadding="6">
        <tr>
            <th>Ürün Adı</th>
            <th>Açıklama</th>
            <th>Konum</th>
            <?php if ($_SESSION['admin'] == 1): ?>
            <th>İşlem</th>
            <?php endif; ?>
        </tr>
        <?php foreach ($urunler as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['urun_adi']) ?></td>
                <td><?= nl2br(htmlspecialchars($u['aciklama'])) ?></td>
                <td><?= konumYolu($u['konum_id']) ?></td>
                <?php if ($_SESSION['admin'] == 1): ?>
                <td>
                    <a href="urun_sil.php?id=<?= $u['id'] ?>" onclick="return confirm('Silinsin mi?')">🗑 Sil</a>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
