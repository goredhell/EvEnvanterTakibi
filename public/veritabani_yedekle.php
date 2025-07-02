<?php
include 'auth.php';
include 'db.php';

// Zaman damgası
$dosya_adi = 'yedek_' . date('Y-m-d_H-i-s') . '.sql';

header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="' . $dosya_adi . '"');

$tables = [];
$q = $pdo->query("SHOW TABLES");
while ($row = $q->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
}

$output = "-- Veritabanı Yedeği\n";
$output .= "-- Tarih: " . date('Y-m-d H:i:s') . "\n\n";

foreach ($tables as $table) {
    // CREATE TABLE
    $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
    $create = $stmt->fetch(PDO::FETCH_ASSOC);
    $output .= "--\n-- Tablo yapısı: `$table`\n--\n\n";
    $output .= $create['Create Table'] . ";\n\n";

    // INSERT INTO
    $stmt = $pdo->query("SELECT * FROM `$table`");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows)) {
        $output .= "--\n-- `$table` tablosu verisi\n--\n\n";
        foreach ($rows as $row) {
            $cols = array_map(function($v) use ($pdo) {
                return $pdo->quote($v);
            }, array_values($row));
            $output .= "INSERT INTO `$table` VALUES (" . implode(", ", $cols) . ");\n";
        }
        $output .= "\n";
    }
}

echo $output;
exit;
