<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

// Ambil riwayat pemesanan berdasarkan nama mahasiswa
$student_name = $_GET['student_name'] ?? ''; // Dapatkan nama mahasiswa dari URL atau input form
$stmt = $pdo->prepare("SELECT bookings.*, rooms.name AS room_name FROM bookings
                       JOIN rooms ON bookings.room_id = rooms.id
                       WHERE student_name = ?");
$stmt->execute([$student_name]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan Lab - Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            padding: 1rem;
            text-align: center;
            color: white;
        }

        .container {
            width: 80%;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        form input, form button {
            padding: 0.75rem;
            margin: 0.5rem;
            font-size: 1rem;
        }

        form input {
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        a {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 0.75rem 1.5rem;
            margin-top: 1rem;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #45a049;
        }

        .no-records {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
            padding: 1.5rem;
        }
    </style>
</head>
<body>

    <header>
        <h2>Riwayat Pemesanan Ruangan Lab</h2>
    </header>

    <div class="container">
        <?php if (empty($student_name)): ?>
            <form method="get" action="riwayat.php">
                <label for="student_name">Masukkan Nama Mahasiswa:</label>
                <input type="text" id="student_name" name="student_name" required>
                <button type="submit">Lihat Riwayat</button>
            </form>
        <?php else: ?>
            <h2>Riwayat Pemesanan untuk: <?php echo htmlspecialchars($student_name); ?></h2>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Ruangan</th>
                        <th>Tanggal</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($bookings) > 0): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['start_time']); ?></td>
                                <td><?php echo htmlspecialchars($booking['end_time']); ?></td>
                                <td><?php echo htmlspecialchars($booking['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="no-records">Tidak ada riwayat pemesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div style="text-align: center;">
                <a href="mahasiswa.php">Kembali ke Form Pemesanan</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
