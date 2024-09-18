<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header('Location: index.php');
    exit;
}
date_default_timezone_set('Asia/Jakarta');

$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Debug: Cek nama mahasiswa dari sesi
$student_name = $_SESSION['user'];

// Ambil booking aktif berdasarkan nama mahasiswa dari sesi
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE student_name = ? AND status = 'approved' AND booking_date = CURDATE() AND end_time > CURTIME()");
$stmt->execute([$student_name]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

// Hitung waktu tersisa (waktu selesai dikurangi waktu sekarang)
$time_remaining = null;
if ($booking) {
    $end_time = strtotime($booking['end_time']);
    $current_time = time();
    $time_remaining = $end_time - $current_time; // Waktu tersisa dalam detik
}

// Handle perpanjangan waktu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['extend_time'])) {
    // Ambil booking ID dari sesi
    $booking_id = $booking['id'];

    // Waktu tambahan (misalnya 30 menit)
    $additional_time = 30;

    // Cek apakah ruangan tersedia untuk diperpanjang
    $stmt = $pdo->prepare("
        SELECT * FROM bookings 
        WHERE room_id = ? 
        AND booking_date = CURDATE() 
        AND start_time > ? 
        AND status = 'approved'
    ");
    $stmt->execute([$booking['room_id'], $booking['end_time']]);

    if (!$stmt->fetch()) {
        // Jika tidak ada booking setelah waktu habis, perpanjang waktu
        $new_end_time = date('H:i:s', strtotime("+$additional_time minutes", strtotime($booking['end_time'])));

        // Update booking end time
        $update_stmt = $pdo->prepare("UPDATE bookings SET end_time = ? WHERE id = ?");
        $update_stmt->execute([$new_end_time, $booking_id]);

        $message = "Waktu booking berhasil diperpanjang hingga $new_end_time";
        $booking['end_time'] = $new_end_time; // Update data booking
        $time_remaining = strtotime($new_end_time) - time(); // Update waktu tersisa
    } else {
        $message = "Ruangan tidak bisa diperpanjang karena sudah ada booking berikutnya.";
    }
}

// Cek apakah ada laporan untuk dicetak
$laporan = isset($_SESSION['laporan']) ? $_SESSION['laporan'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function printLaporan() {
            var printContent = document.getElementById("laporan");
            var WinPrint = window.open('', '', 'width=900,height=650');
            WinPrint.document.write(printContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
    </script>
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-blue-600 text-white p-4 shadow-md">
        <h2 class="text-2xl font-semibold text-center">Dashboard Mahasiswa</h2>
    </header>

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md mt-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold">Selamat datang, <?php echo htmlspecialchars($_SESSION['user']); ?> (Mahasiswa)</h1>
        </div>
        <?php if ($booking): ?>
        <h2 class="text-2xl font-semibold mb-2">Anda sedang menggunakan ruangan <?php echo htmlspecialchars($booking['room_id']); ?></h2>
        <p class="mb-4">Waktu pemesanan Anda berakhir pada: <?php echo htmlspecialchars($booking['end_time']); ?></p>

        <div id="countdown" class="text-xl font-bold text-red-500 mb-4"></div>

        <script>
            // Menghitung waktu tersisa dalam detik
            var timeRemaining = <?php echo $time_remaining; ?>;

            function updateCountdown() {
                var hours = Math.floor(timeRemaining / 3600);
                var minutes = Math.floor((timeRemaining % 3600) / 60);
                var seconds = timeRemaining % 60;

                // Format dan tampilkan countdown
                document.getElementById('countdown').textContent = 
                    hours + " Jam " + minutes + " Menit " + seconds + " Detik";

                // Alert saat waktu tinggal 5 menit
                if (timeRemaining === 300) {
                    alert("Waktu booking Anda tinggal 5 menit lagi!");
                }

                if (timeRemaining > 0) {
                    timeRemaining--; // Kurangi waktu tersisa
                } else {
                    document.getElementById('countdown').textContent = "Waktu telah habis!";
                }
            }

            // Jalankan countdown setiap detik
            setInterval(updateCountdown, 1000);
        </script>

        <!-- Tombol Perpanjangan Waktu -->
        <form method="post" class="mt-4">
            <input type="hidden" name="extend_time" value="1">
            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Perpanjang Waktu (30 menit)</button>
        </form>

        <!-- Pesan Perpanjangan -->
        <?php if (isset($message)): ?>
            <p class="mt-4 text-green-500"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php else: ?>
        <p class="text-lg">Anda tidak memiliki booking aktif saat ini.</p>
        <?php endif; ?>

        <!-- Laporan yang Bisa Dicetak -->
        <?php if ($laporan): ?>
            <div id="laporan" class="mt-6">
                <h2 class="text-xl font-bold mb-2">Laporan Booking</h2>
                <p>Nama Mahasiswa: <?php echo htmlspecialchars($laporan['student_name']); ?></p>
                <p>Ruangan: <?php echo htmlspecialchars($laporan['room_name']); ?></p>
                <p>Tanggal Booking: <?php echo htmlspecialchars($laporan['booking_date']); ?></p>
                <p>Waktu: <?php echo htmlspecialchars($laporan['start_time']); ?> - <?php echo htmlspecialchars($laporan['end_time']); ?></p>
            </div>
            <button onclick="printLaporan()" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 mt-4">Print Laporan</button>
            <?php unset($_SESSION['laporan']); // Hapus laporan setelah ditampilkan ?>
        <?php endif; ?>

        <div class="mt-6">
            <a href="daftarlab.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Pesan Ruangan</a>
            <a href="mahadewa.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 ml-4">Lihat Jadwal Ruangan Mata Kuliah</a>
            <a href="logout.php" class="inline-block bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 ml-4">Logout</a>
        </div>
    </div>

    <footer class="bg-gray-800 text-white text-center py-4 mt-6">
        <p>&copy; 2024 Lab Booking System</p>
    </footer>
</body>
</html>
