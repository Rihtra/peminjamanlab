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

// Proses penambahan ruangan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO rooms (name, capacity, description) VALUES (?, ?, ?)");
    $stmt->execute([$name, $capacity, $description]);

    header('Location: kelolaadmin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ruangan</title>
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
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 1rem;
            text-align: center;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <header>Tambah Ruangan Lab</header>

    <div class="container">
        <h1>Tambah Ruangan</h1>
        <form action="tambah.php" method="post">
            <label for="name">Nama Ruangan:</label>
            <input type="text" name="name" id="name" required>

            <label for="capacity">Kapasitas:</label>
            <input type="number" name="capacity" id="capacity" required>

            <label for="description">Deskripsi:</label>
            <textarea name="description" id="description" rows="4"></textarea>

            <button type="submit">Simpan</button>
        </form>
        <a href="kelolaadmin.php">Kembali ke Daftar Ruangan</a>
    </div>

</body>
</html>
