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

// Mendapatkan hari ini dalam format "Senin", "Selasa", dst.
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

// Konversi hari saat ini ke bahasa Indonesia
$hari_sekarang = $hari_mapping[$hari_ini];

// Ambil daftar ruangan dan jadwal yang diatur oleh admin untuk hari ini
$schedules_stmt = $pdo->prepare("
    SELECT rooms.id AS room_id, rooms.name AS room_name, rooms.capacity, rooms.description,
           schedules.start_time, schedules.end_time, 
           schedules.class_name, schedules.subject_name, 
           schedules.day_of_week
    FROM rooms
    LEFT JOIN schedules ON rooms.id = schedules.room_id 
    AND schedules.day_of_week = ?
");
$schedules_stmt->execute([$hari_sekarang]);
$schedules = $schedules_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ruangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        .room-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .room-table th, .room-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .room-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .room-row {
            cursor: pointer;
        }

        .status-available {
            color: green;
        }

        .status-occupied {
            color: red;
        }

        .status-unavailable {
            color: gray;
        }

        .select-button {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .select-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Daftar Ruangan</h1>

    <table class="room-table">
        <thead>
            <tr>
                <th>Nama Ruangan</th>
                <th>Kapasitas</th>
                <th>Deskripsi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="room-table-body">
            <?php foreach ($schedules as $schedule): ?>
                <?php
                $is_occupied = ($schedule['start_time'] && $schedule['end_time']);
                $status_class = $is_occupied ? 'status-occupied' : 'status-available';
                $status_text = $is_occupied ? "Sedang Digunakan dari {$schedule['start_time']} hingga {$schedule['end_time']}" : "Tersedia";
                ?>
                <tr id="room-<?php echo htmlspecialchars($schedule['room_id']); ?>" class="room-row">
                    <td><?php echo htmlspecialchars($schedule['room_name']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['capacity']); ?></td>
                    <td><?php echo htmlspecialchars($schedule['description']); ?></td>
                    <td class="<?php echo $status_class; ?>">
                        <?php echo $status_text; ?>
                    </td>
                   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function selectRoom(roomId, roomName, button) {
            document.getElementById('room_id').value = roomId;
            document.getElementById('selected_room_name').innerText = roomName;
            document.querySelectorAll('.select-button').forEach(btn => btn.disabled = false);
            button.disabled = true;
        }
    </script>

</body>
</html>
