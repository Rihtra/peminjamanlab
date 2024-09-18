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

// Ambil daftar ruangan
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Ruangan Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <header class="bg-blue-600 text-white py-4 shadow-md fixed w-full top-0 left-0 z-10">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold">Admin - Kelola Ruangan Lab</h1>
        </div>
    </header>

    <div class="container mx-auto mt-24 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Daftar Ruangan Lab</h2>
        <div class="mb-6">
            <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">Tambah Ruangan</a>
            <a href="admindasbor.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition duration-300">Kembali</a>
            <a href="admin.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-300">Cek Permintaan Booking</a>
            <a href="matkul.php" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition duration-300">Penjadwalan</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold">ID</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Nama Ruangan</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Kapasitas</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Deskripsi</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): ?>
                        <tr class="border-t border-gray-200">
                            <td class="py-3 px-4 text-sm"><?php echo htmlspecialchars($room['id']); ?></td>
                            <td class="py-3 px-4 text-sm"><?php echo htmlspecialchars($room['name']); ?></td>
                            <td class="py-3 px-4 text-sm"><?php echo htmlspecialchars($room['capacity']); ?></td>
                            <td class="py-3 px-4 text-sm"><?php echo htmlspecialchars($room['description']); ?></td>
                            <td class="py-3 px-4 text-sm">
                                <div class="flex gap-2">
                                    <a href="edit.php?id=<?php echo $room['id']; ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-300">Edit</a>
                                    <a href="hapus.php?id=<?php echo $room['id']; ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-300" onclick="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
