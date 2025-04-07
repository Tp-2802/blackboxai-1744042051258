<?php
require 'config.php';
session_start();

// Redirect if no pending 2FA authentication
if (empty($_SESSION['2fa_user_id'])) {
    header('Location: signin.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token']);
    
    if (empty($token) || strlen($token) !== 6 || !is_numeric($token)) {
        $error = 'Please enter a valid 6-digit token';
    } else {
        // Get user's 2FA secret
        $stmt = $db->prepare("SELECT twofa_secret FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['2fa_user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // In a real implementation, you would validate against Google Authenticator
        // For this demo, we'll just check if token matches a simple pattern
        if ($token === '123456') { // Replace with actual 2FA validation
            $_SESSION['user_id'] = $_SESSION['2fa_user_id'];
            unset($_SESSION['2fa_user_id']);
            header('Location: profile.php');
            exit;
        } else {
            $error = 'Invalid authentication token';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification - PHP Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Two-Factor Authentication</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="mb-6 text-center">
            <p class="text-gray-600 mb-4">Please enter the 6-digit code from your authenticator app</p>
            <!-- In a real implementation, you would show a QR code here -->
        </div>

        <form method="POST" class="space-y-4">
            <div>
                <label for="token" class="block text-sm font-medium text-gray-700">Verification Code</label>
                <input type="text" id="token" name="token" inputmode="numeric" pattern="\d{6}" maxlength="6" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border text-center text-xl tracking-widest">
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Verify
            </button>
        </form>
    </div>
</body>
</html>