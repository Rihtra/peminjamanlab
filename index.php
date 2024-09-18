<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-gray-100 flex flex-col">
    <header class="bg-blue-600 text-white p-4 fixed top-0 w-full shadow-lg z-50">
        <div class="container mx-auto flex justify-center">
            <h1 class="text-2xl md:text-3xl font-bold">Lab Booking System</h1>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center bg-cover bg-center text-white" style="background-image: url('https://source.unsplash.com/1600x900/?lab,technology');">
        <div class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg max-w-lg mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4 text-gray-800">Welcome to Lab Booking System</h2>
            <p class="text-lg mb-6 text-gray-700">Experience seamless booking for laboratory rooms tailored to your academic and research needs.</p>
            <div class="flex flex-col gap-4">
                <a href="formlogin.php" class="bg-blue-600 text-white py-2 px-4 rounded-lg text-lg font-semibold shadow-md hover:bg-blue-700 transition-transform transform hover:scale-105">Login</a>
                <a href="register.php" class="bg-blue-600 text-white py-2 px-4 rounded-lg text-lg font-semibold shadow-md hover:bg-blue-700 transition-transform transform hover:scale-105">Register</a>
            </div>
        </div>
    </main>

    <footer class="bg-blue-600 text-white p-4 text-center">
        <p>&copy; 2024 Lab Booking System. All rights reserved.</p>
        <div class="text-xl mt-2 animate-bounce">▼</div>
    </footer>
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
