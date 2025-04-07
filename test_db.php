<?php
require 'config.php';

try {
    echo "<h1 class='text-xl font-bold mb-4'>Database Connection Test</h1>";
    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>";
    echo "Successfully connected to database: " . $db->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    echo "</div>";
    
    // Check tables
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h2 class='text-lg font-semibold mt-4 mb-2'>Existing Tables:</h2><ul class='list-disc pl-5 mb-4'>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Count users
    $userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "<p class='mb-2'>Total users: <span class='font-bold'>$userCount</span></p>";
    
    // Sample users listing
    if ($userCount > 0) {
        echo "<h2 class='text-lg font-semibold mt-4 mb-2'>Sample Users:</h2>";
        $users = $db->query("SELECT username, email FROM users LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        echo "<table class='min-w-full bg-white border mb-4'><tr class='bg-gray-100'><th class='py-2 px-4 border'>Username</th><th class='py-2 px-4 border'>Email</th></tr>";
        foreach ($users as $user) {
            echo "<tr><td class='py-2 px-4 border'>".htmlspecialchars($user['username'])."</td><td class='py-2 px-4 border'>".htmlspecialchars($user['email'])."</td></tr>";
        }
        echo "</table>";
    }
    
} catch(PDOException $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>Error: " . $e->getMessage() . "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <div class="max-w-2xl mx-auto">
        <?php include 'test_db.php'; ?>
        <a href="index.php" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Return to Home</a>
    </div>
</body>
</html>