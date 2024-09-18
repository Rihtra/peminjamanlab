<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $class_name = $_POST['class_name'];
    $subject_name = $_POST['subject_name'];

    // Set status default jika tidak ada informasi lain
    $status = 'pending'; // Atau sesuai dengan default status yang Anda inginkan

    // Insert jadwal ke database
    $stmt = $pdo->prepare("INSERT INTO bookings (room_id, day_of_week, start_time, end_time, class_name, subject_name, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$room_id, $day_of_week, $start_time, $end_time, $class_name, $subject_name, $status]);

    echo "<div class='bg-green-500 text-white p-4 rounded'>Jadwal berhasil ditambahkan!</div>";
}

// Ambil daftar ruangan
$rooms_stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $rooms_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Input Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Input Jadwal Kelas untuk Ruangan</h1>
        <form method="post" class="space-y-4">
            <div class="flex flex-col">
                <label for="room_id" class="text-gray-700 font-medium mb-1">Pilih Ruangan:</label>
                <select id="room_id" name="room_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['id']; ?>"><?php echo htmlspecialchars($room['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex flex-col">
                <label for="day_of_week" class="text-gray-700 font-medium mb-1">Pilih Hari:</label>
                <select id="day_of_week" name="day_of_week" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex flex-col w-full">
                    <label for="start_time" class="text-gray-700 font-medium mb-1">Waktu Mulai:</label>
                    <input type="time" id="start_time" name="start_time" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="flex flex-col w-full mt-4 md:mt-0">
                    <label for="end_time" class="text-gray-700 font-medium mb-1">Waktu Selesai:</label>
                    <input type="time" id="end_time" name="end_time" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
            </div>

            <div class="flex flex-col">
                <label for="class_name" class="text-gray-700 font-medium mb-1">Nama Kelas:</label>
                <input type="text" id="class_name" name="class_name" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>

            <div class="flex flex-col">
                <label for="subject_name" class="text-gray-700 font-medium mb-1">Nama Mata Kuliah:</label>
                <input type="text" id="subject_name" name="subject_name" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Tambah Jadwal</button>
        </form>
    </div>
</body>
</html>
