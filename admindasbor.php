<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            padding: 1rem 2rem;
            text-align: center;
            font-size: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container {
            max-width: 1000px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 0.75rem 2rem;
            margin: 0.5rem;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .logout-btn {
            background-color: #dc3545;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .card {
            display: inline-block;
            background-color: #ffffff;
            padding: 1.5rem;
            margin: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 300px;
        }

        .card h2 {
            margin-top: 0;
            color: #007bff;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .card p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .card a {
            display: block;
            background-color: #007bff;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        .card a:hover {
            background-color: #0056b3;
        }

        .card p {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <header>Admin Dashboard</header>

    <div class="container">
        <h1>Selamat datang, <?php echo htmlspecialchars($_SESSION['user']); ?> (Admin)</h1>
        
        <!-- Kartu navigasi untuk admin -->
        <div class="card">
            <h2>Kelola Booking</h2>
            <p>Manage all the room bookings and review pending requests.</p>
            <a href="admin.php">Kelola Booking</a>
        </div>
        <div class="card">
            <h2>Kelola Ruangan</h2>
            <p>Update room details and manage room availability.</p>
            <a href="kelolaadmin.php">Kelola Ruangan</a>
        </div>
        <div class="card">
            <h2>Logout</h2>
            <p>Logout from the admin dashboard.</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

</body>
</html>
