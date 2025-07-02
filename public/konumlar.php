<?php
include 'auth.php';
include 'db.php';
include 'menu.php';

// Tüm konumları çek
$stmt = $pdo->query("SELECT * FROM konumlar ORDER BY ad ASC");
$konumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grupla
$gruplar = [];
foreach ($konumlar as $k) {
    $gruplar[$k['ebeveyn_id']][] = $k;
}

// Hiyerarşi yazdıran fonksiyon
function yazKonumlar($ebeveyn_id = null, $seviye = 0) {
    global $gruplar;
    if (!isset($gruplar[$ebeveyn_id])) return;

    foreach ($gruplar[$ebeveyn_id] as $konum) {
        echo '<tr>';
        echo '<td>' . str_repeat('— ', $seviye) . htmlspecialchars($konum['ad']) . '</td>';
        echo '<td class="text-center">';
        echo '<a href="konum_detay.php?id=' . $konum['id'] . '" class="btn btn-sm btn-outline-primary me-1">Detay</a>';
        echo '<a href="konum_duzenle.php?id=' . $konum['id'] . '" class="btn btn-sm btn-outline-secondary me-1">Düzenle</a>';
        echo '<a href="konum_sil.php?id=' . $konum['id'] . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Silmek istediğine emin misin?\')">Sil</a>';
        echo '</td>';
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

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Konum Adı</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php yazKonumlar(); ?>
        </tbody>
    </table>
</div>
