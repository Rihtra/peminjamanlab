<?php
session_start();

// Koneksi ke database
$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah user adalah admin
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        // Jika admin ditemukan, set session dan redirect ke dashboard admin
        $_SESSION['user'] = $admin['username'];
        $_SESSION['role'] = 'admin';
        header('Location: admindasbor.php');
        exit;
    }

    // Cek apakah user adalah mahasiswa
    $stmt = $pdo->prepare("SELECT * FROM students WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $student = $stmt->fetch();

    if ($student) {
        // Jika mahasiswa ditemukan, set session dan redirect ke dashboard mahasiswa
        $_SESSION['user'] = $student['username'];
        $_SESSION['role'] = 'student';
        header('Location: mahasiswadasbor.php');
        exit;
    }
    // Cek apakah user adalah ketua
    $stmt = $pdo->prepare("SELECT * FROM ketua WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $ketua = $stmt->fetch();

    if ($ketua) {
        // Jika ketua ditemukan, set session dan redirect ke dashboard mahasiswa
        $_SESSION['user'] = $ketua['username'];
        $_SESSION['role'] = 'ketua';
        header('Location: ketuadashbor.php');
        exit;
    }

    // Jika tidak ditemukan user, tampilkan pesan error
    echo "Username atau password salah!";
}
?>
