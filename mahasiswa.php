<?php
session_start();
if ($_SESSION['role'] !== 'student') {
    header('Location: index.php');
    exit;
}

$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

$room_id = $_GET['room_id'] ?? null;

if (!$room_id) {
    header('Location: daftar_lab.php');
    exit;
}

$hari_ini = date('l', strtotime("today"));
$hari_mapping = array(
    'Monday' => 'Senin', 
    'Tuesday' => 'Selasa', 
    'Wednesday' => 'Rabu', 
    'Thursday' => 'Kamis', 
    'Friday' => 'Jumat', 
    'Saturday' => 'Sabtu', 
    'Sunday' => 'Minggu'
);
$hari_sekarang = $hari_mapping[$hari_ini];

// Ambil detail ruangan
$stmt = $pdo->prepare("
    SELECT r.id, r.name, r.capacity, r.description,
           IF(COUNT(b.id) > 0, 'Tidak Tersedia', 'Tersedia') AS status,
           GROUP_CONCAT(CONCAT('Dari ', b.start_time, ' hingga ', b.end_time) SEPARATOR ', ') AS booking_times
    FROM rooms r
    LEFT JOIN bookings b 
    ON r.id = b.room_id 
    AND b.day_of_week = ? 
    AND b.status = 'approved' 
    AND (
        (b.start_time <= CURTIME() AND b.end_time > CURTIME())
    )
    WHERE r.id = ?
    GROUP BY r.id
");
$stmt->execute([$hari_sekarang, $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    header('Location: daftar_lab.php');
    exit;
}

// Jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = $_POST['student_name'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $booking_date = $_POST['booking_date'];

    $overlap_stmt = $pdo->prepare("
        SELECT * FROM bookings 
        WHERE room_id = ? 
        AND day_of_week = ? 
        AND (
            (start_time <= ? AND end_time > ?) 
            OR 
            (start_time < ? AND end_time >= ?)
        )
    ");
    $overlap_stmt->execute([$room_id, $hari_sekarang, $start_time, $start_time, $end_time, $end_time]);

    if ($overlap_stmt->fetch()) {
        echo "Waktu yang Anda pilih bertabrakan dengan jadwal kelas!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO bookings (student_name, room_id, start_time, end_time, status, booking_date, day_of_week)
                               VALUES (?, ?, ?, ?, 'pending', ?, ?)");
        $stmt->execute([$student_name, $room_id, $start_time, $end_time, $booking_date, $hari_sekarang]);

        echo "Pemesanan berhasil! Menunggu konfirmasi admin.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Lab - Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-green-600 text-white text-center py-4">
        <h1 class="text-2xl font-bold">Formulir Pemesanan Lab</h1>
    </header>

    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl mx-auto">
            <h2 class="text-2xl font-semibold mb-4 text-center">Pesan Ruangan</h2>
            <p class="text-gray-600 mb-6 text-center">Lab: <?php echo htmlspecialchars($room['name']); ?></p>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="student_name" class="block text-sm font-medium text-gray-700">Nama Anda:</label>
                    <input type="text" id="student_name" name="student_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" />
                </div>
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai:</label>
                    <input type="time" id="start_time" name="start_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" />
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai:</label>
                    <input type="time" id="end_time" name="end_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" />
                </div>
                <div>
                    <label for="booking_date" class="block text-sm font-medium text-gray-700">Tanggal Pemesanan:</label>
                    <input type="date" id="booking_date" name="booking_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" />
                </div>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded">Kirim Pemesanan</button>
            </form>
        </div>
    </div>
</body>
</html>
