<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Tüm konumları çek
$stmt = $pdo->query("SELECT * FROM konumlar ORDER BY ad ASC");
$konumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Konumları ebeveyn_id'ye göre grupla
$gruplar = [];
foreach ($konumlar as $k) {
    $gruplar[$k['ebeveyn_id']][] = $k;
}

// Hiyerarşik listeleme fonksiyonu
function yazKonumlar($ebeveyn_id = null, $seviye = 0) {
    global $gruplar;
    if (!isset($gruplar[$ebeveyn_id])) return;

    foreach ($gruplar[$ebeveyn_id] as $konum) {
        echo '<tr>';
        echo '<td>' . str_repeat('— ', $seviye) . htmlspecialchars($konum['ad']) . '</td>';
        echo '<td><a href="konum_detay.php?id=' . $konum['id'] . '" class="btn btn-sm btn-outline-primary">Detay</a></td>';
        echo '</tr>';
        yazKonumlar($konum['id'], $seviye + 1);
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📁 Konumlar</h3>
        <a href="konum_ekle.php" class="btn btn-success">➕ Yeni Konum Ekle</a>
    </div>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Konum Adı</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php yazKonumlar(); ?>
        </tbody>
    </table>
</div>
