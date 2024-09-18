<?php
session_start();
if ($_SESSION['role'] !== 'ketua') {
    header('Location: index.php');
    exit;
}

$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Ambil informasi ruangan, jadwal kelas yang diinput admin, dan booking mahasiswa
$stmt = $pdo->prepare("
    SELECT r.name AS room_name, r.capacity, r.description, 
           s.class_name, s.subject_name, s.day_of_week, s.start_time, s.end_time,
           b.student_name, b.start_time AS booking_start, b.end_time AS booking_end, b.status
    FROM rooms r
    LEFT JOIN schedules s ON r.id = s.room_id
    LEFT JOIN bookings b ON r.id = b.room_id
    ORDER BY r.name, s.day_of_week, b.status
");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketua Lab Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 2rem;
        }
        .container {
            margin: 20px auto;
            max-width: 1500px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .room-header {
            font-size: 1.2rem;
            margin-top: 30px;
            color: #333;
        }
    </style>
</head>
<body>

<header>Dashboard Ketua Lab</header>

<div class="container">
    <h1>Data Ruangan, Jadwal, dan Pemesanan</h1>
    <?php if (count($records) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nama Ruangan</th>
                    <th>Kapasitas</th>
                    <th>Deskripsi</th>
                    <th>Kelas (Admin)</th>
                    <th>Mata Kuliah (Admin)</th>
                    <th>Hari (Admin)</th>
                    <th>Waktu Mulai (Admin)</th>
                    <th>Waktu Selesai (Admin)</th>
                    <th>Nama Mahasiswa</th>
                    <th>Waktu Mulai (Mahasiswa)</th>
                    <th>Waktu Selesai (Mahasiswa)</th>
                    <th>Status Pemesanan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['room_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['capacity']); ?></td>
                        <td><?php echo htmlspecialchars($record['description']); ?></td>
                        <td><?php echo htmlspecialchars($record['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['day_of_week']); ?></td>
                        <td><?php echo htmlspecialchars($record['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($record['end_time']); ?></td>
                        <td><?php echo htmlspecialchars($record['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($record['booking_start']); ?></td>
                        <td><?php echo htmlspecialchars($record['booking_end']); ?></td>
                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada data yang tersedia.</p>
    <?php endif; ?>
</div>

</body>
</html>
