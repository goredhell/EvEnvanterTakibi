<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Tüm konumları çek
$stmt = $pdo->query("SELECT * FROM konumlar");
$konumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ID'ye göre eşle
$konumMap = [];
foreach ($konumlar as $k) {
    $konumMap[$k['id']] = $k;
}

// Hiyerarşik gösterim
function konumYazdir($parent_id = null, $level = 0) {
    global $konumMap;

    foreach ($konumMap as $k) {
        if ($k['ebeveyn_id'] == $parent_id) {
            echo str_repeat("— ", $level) . htmlspecialchars($k['ad']) . " ";
            echo "<a href='konum_detay.php?id={$k['id']}'>🔍</a> ";
            echo "<a href='qr.php?token={$k['token']}'>🔳</a> ";
            echo "<a href='konum_sil.php?id={$k['id']}' onclick=\"return confirm('Silinsin mi?')\">🗑</a><br>";
            konumYazdir($k['id'], $level + 1);
        }
    }
}
?>

<h2>📦 Konumlar (Dolap, Kutu, vb.)</h2>

<p><a href="konum_ekle.php">+ Yeni Konum Ekle</a></p>

<pre>
<?php konumYazdir(); ?>
</pre>
