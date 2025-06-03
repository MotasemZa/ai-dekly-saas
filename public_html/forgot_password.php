<?php
require_once 'config.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            // Generate a secure token even if random_bytes is unavailable
            if (function_exists('random_bytes')) {
                $token = bin2hex(random_bytes(16));
            } elseif (function_exists('openssl_random_pseudo_bytes')) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
            } else {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $token = '';
                for ($i = 0; $i < 32; $i++) {
                    $token .= $characters[mt_rand(0, strlen($characters) - 1)];
                }
            }
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour
            $stmt = $pdo->prepare('UPDATE users SET reset_token=?, reset_token_expires=? WHERE id=?');
            $stmt->execute([$token, $expires, $user['id']]);
            $link = "reset_password.php?token=$token";
            $success = "Password reset link: <a href='$link'>$link</a>";
        } else {
            $success = 'If that email exists, a reset link has been generated.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - AI Dekly SaaS</title>
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
    <h1>Forgot Password</h1>
    <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
    <form method="post" action="forgot_password.php">
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Send Reset Link</button>
    </form>
    <p><a href="login.php">Back to login</a></p>
</div>
</body>
</html>
