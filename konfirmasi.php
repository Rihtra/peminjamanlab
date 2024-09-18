<?php
session_start();
$host = 'localhost';
$dbname = 'lab_booking';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    // Update status booking
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    // Jika status approved, generate laporan
    if ($status === 'approved') {
      // Correct SQL query without unnecessary JOIN on 'students'
$stmt = $pdo->prepare("SELECT bookings.*, rooms.name AS room_name
FROM bookings
JOIN rooms ON bookings.room_id = rooms.id
WHERE bookings.id = ?");
$stmt->execute([$id]);
$booking = $stmt->fetch();


        // Simpan laporan ke session untuk diambil oleh mahasiswa
        $_SESSION['laporan'] = [
            'student_name' => $booking['student_name'],
            'room_name' => $booking['room_name'],
            'booking_date' => $booking['booking_date'],
            'start_time' => $booking['start_time'],
            'end_time' => $booking['end_time']
        ];
    }

    // Redirect kembali ke halaman admin
    $_SESSION['message'] = "Booking $status!";
    header('Location: admindasbor.php');
    exit;
}
?>
