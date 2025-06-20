<?php
session_start();
include 'db.php'; // veritabanı bağlantısı buraya yazılacak

$kullanici_adi = $_POST['kullanici_adi'] ?? '';
$sifre = $_POST['sifre'] ?? '';

if (!$kullanici_adi || !$sifre) {
    header("Location: login.php?hata=1");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
$stmt->execute([$kullanici_adi]);
$kullanici = $stmt->fetch();

if ($kullanici && password_verify($sifre, $kullanici['sifre_hash'])) {
    $_SESSION['kullanici_id'] = $kullanici['id'];
    $_SESSION['kullanici_adi'] = $kullanici['kullanici_adi'];
    $_SESSION['admin'] = $kullanici['admin'];
    header("Location: index.php");
} else {
    header("Location: login.php?hata=1");
}
