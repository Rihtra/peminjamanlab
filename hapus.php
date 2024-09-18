<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Koneksi ke database
$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Hapus ruangan
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
$stmt->execute([$id]);

header('Location: kelolaadmin.php');
exit;
?>
