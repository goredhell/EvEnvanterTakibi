<?php
require_once('tcpdf/tcpdf.php');
include 'auth.php';
include 'db.php';

// Veritabanından konumları çek
$stmt = $pdo->query("SELECT ad, token FROM konumlar ORDER BY ad");
$konumlar = $stmt->fetchAll();

// Yeni PDF nesnesi
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Envanter Sistemi');
$pdf->SetTitle('Toplu QR Kodlar');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

// Stil
$pdf->SetFont('helvetica', '', 10);

$col = 0;
$maxCols = 3;
$cellWidth = 60;
$cellHeight = 70;

foreach ($konumlar as $index => $konum) {
    $qr_path = __DIR__ . '/temp_qr/qr_' . md5($konum['token']) . '.png';

    // QR dosyası yoksa üret (senin QR üretim sistemini kullanarak)
    if (!file_exists($qr_path)) {
        $qr_text = "https://yourdomain.com/konum_mobil.php?token=" . urlencode($konum['token']);
        include_once 'phpqrcode.php';
        QRcode::png($qr_text, $qr_path, QR_ECLEVEL_L, 4);
    }

    if ($col === 0) {
        $pdf->Ln(5);
    }

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // QR görseli
    $pdf->Image($qr_path, $x + 5, $y, 40, 40, 'PNG');

    // Konum adı
    $pdf->SetXY($x, $y + 42);
    $pdf->MultiCell($cellWidth, 20, $konum['ad'], 0, 'C', false, 0, '', '', true, 0, false, true, 0);

    $col++;
    if ($col >= $maxCols) {
        $col = 0;
        $pdf->Ln($cellHeight);
    } else {
        $pdf->SetX($x + $cellWidth);
    }
}

// PDF çıktısı → indir
$pdf->Output('toplu_qr_kodlar.pdf', 'D');
