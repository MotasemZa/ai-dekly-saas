<?php
require_once 'config.php';
$error = '';
$success = '';
$token = $_GET['token'] ?? '';
if ($token) {
    $stmt = $pdo->prepare('SELECT id, reset_token_expires FROM users WHERE reset_token = ?');
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    if ($user && strtotime($user['reset_token_expires']) > time()) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            if (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET password=?, reset_token=NULL, reset_token_expires=NULL WHERE id=?');
                $stmt->execute([$hash, $user['id']]);
                $success = 'Password updated. <a href="login.php">Login</a>';
            }
        }
    } else {
        $error = 'Invalid or expired token';
    }
} else {
    $error = 'No token provided';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - AI Dekly SaaS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
    <style>
        body {font-family:'DM Sans',sans-serif;background:#F4F5FF;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
        .container {background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);width:300px;text-align:center;}
        input{width:100%;padding:8px;margin:8px 0;box-sizing:border-box;}
        button{width:100%;padding:10px;background-color:#673DE6;color:#fff;border:none;border-radius:4px;cursor:pointer;}
        .error{color:red;}
        .success{color:green;}
        a{color:#673DE6;text-decoration:none;}
    </style>
</head>
<body>
<div class="container">
    <h1>Reset Password</h1>
    <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
    <?php if (!$success && !$error): ?>
    <form method="post" action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit">Update Password</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
