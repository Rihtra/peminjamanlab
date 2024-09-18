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
    GROUP BY r.id
");
$stmt->execute([$hari_sekarang]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Lab - Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-green-600 text-white text-center py-4">
        <h1 class="text-3xl font-bold">Daftar Lab</h1>
    </header>

    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold mb-4 text-center">Pilih Lab untuk Pemesanan</h2>
            <p class="text-gray-600 mb-6 text-center">Lihat daftar lab dan pilih lab yang ingin Anda pesan.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($rooms as $room): ?>
                    <div class="bg-gradient-to-r from-green-200 via-green-300 to-green-400 shadow-lg rounded-lg p-4 border border-gray-200 transform transition-transform hover:scale-105 hover:shadow-xl">
                        <div class="flex items-center mb-4">
                            <img src="path-to-icon.png" alt="Lab Icon" class="w-12 h-12 mr-4">
                            <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($room['name']); ?></h2>
                        </div>
                        <p><strong>Kapasitas:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
                        <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($room['description']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($room['status']); ?> 
                        <?php if ($room['status'] == 'Tidak Tersedia'): ?>
                            (Jadwal: <?php echo htmlspecialchars($room['booking_times']); ?>)
                        <?php endif; ?>
                        </p>
                        <?php if ($room['status'] == 'Tersedia'): ?>
                            <button onclick="openModal('modal-room-<?php echo $room['id']; ?>')" class="bg-gradient-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 text-white font-bold py-2 px-4 rounded mt-4 transition-all">Pilih Lab</button>
                        <?php else: ?>
                            <p class="text-red-500 font-bold mt-4">Tidak Tersedia</p>
                        <?php endif; ?>
                    </div>

                    <!-- Modal Structure -->
                    <div id="modal-room-<?php echo $room['id']; ?>" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                            <h2 class="text-xl font-bold mb-4"><?php echo htmlspecialchars($room['name']); ?></h2>
                            <p><strong>Kapasitas:</strong> <?php echo htmlspecialchars($room['capacity']); ?></p>
                            <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($room['description']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($room['status']); ?> 
                            <?php if ($room['status'] == 'Tidak Tersedia'): ?>
                                (Jadwal: <?php echo htmlspecialchars($room['booking_times']); ?>)
                            <?php endif; ?>
                            </p>
                            <form method="GET" action="mahasiswa.php">
                                <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Pilih Lab dan Pesan</button>
                            </form>
                            <button onclick="closeModal('modal-room-<?php echo $room['id']; ?>')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mt-4">Tutup</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</body>
</html>
