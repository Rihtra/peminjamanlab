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

// Ambil pemesanan yang belum dikonfirmasi (status pending)
$stmt = $pdo->query("SELECT bookings.*, rooms.name AS room_name 
                     FROM bookings
                     JOIN rooms ON bookings.room_id = rooms.id
                     WHERE status = 'pending'");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Booking Lab</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 1.5rem;
            text-align: center;
            font-size: 1.8rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #333;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
        }

        .nav {
            margin-bottom: 2rem;
            text-align: center;
        }

        .nav a {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 0.75rem 1.5rem;
            margin: 0.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.2rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-links a {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1rem;
            margin: 0.2rem;
            transition: background-color 0.3s ease;
        }

        .approve-btn {
            background-color: #28a745;
        }

        .approve-btn:hover {
            background-color: #218838;
        }

        .reject-btn {
            background-color: #dc3545;
        }

        .reject-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['message'])): ?>
        <script>
            alert('<?php echo $_SESSION['message']; ?>');
        </script>
        <?php unset($_SESSION['message']); // Hapus pesan setelah ditampilkan ?>
    <?php endif; ?>

    <header>Admin - Konfirmasi Booking Lab</header>

    <div class="container">
        <h1>Konfirmasi Booking Lab</h1>
        <div class="nav">
            <a href="admindasbor.php">Kembali</a>
            <a href="kelolaadmin.php">Kelola Ruangan</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Mahasiswa</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($booking['end_time']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        <td class="action-links">
                            <a href="konfirmasi.php?id=<?php echo $booking['id']; ?>&status=approved" class="approve-btn">Terima</a>
                            <a href="konfirmasi.php?id=<?php echo $booking['id']; ?>&status=rejected" class="reject-btn" onclick="return confirm('Apakah Anda yakin ingin menolak booking ini?');">Tolak</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
