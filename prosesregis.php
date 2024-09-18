<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Cek apakah username sudah terdaftar
    if ($role === 'admin') {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    } elseif ($role === 'student') {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE username = ?");
    } elseif($role === 'ketua'){
        $stmt = $pdo->prepare("SELECT * FROM ketua WHERE username = ?");

    }

    $stmt->execute([$username]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        echo "Username sudah digunakan. Silakan pilih username lain.";
    } else {
        // Tambahkan user baru ke database
        if ($role === 'admin') {
            $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        } elseif ($role === 'student') {
            $stmt = $pdo->prepare("INSERT INTO students (username, password) VALUES (?, ?)");
        } elseif ($role === 'ketua') {
            $stmt = $pdo->prepare("INSERT INTO ketua (username, password) VALUES (?, ?)");
        }

        $stmt->execute([$username, $password]);
        echo "Registrasi berhasil! <a href='index.php'>Login di sini</a>";
    }
}
?>
