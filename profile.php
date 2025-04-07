<?php
require 'config.php';
session_start();

// Redirect if not logged in
if (empty($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

// Fetch user details
$stmt = $db->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - PHP Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">User Profile</h1>
        
        <div class="mb-4">
            <p class="text-gray-700"><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p class="text-gray-700"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        </div>

        <div class="flex justify-between">
            <a href="index.php" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Home</a>
            <a href="logout.php" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Logout</a>
        </div>
    </div>
</body>
</html>