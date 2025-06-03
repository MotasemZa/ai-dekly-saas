<?php
require_once 'config.php';
session_start();
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
            $stmt->execute([$email, $hash]);
            $success = 'Account created successfully. <a href="login.php">Login</a>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - AI Dekly SaaS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
    <style>
        body {font-family:'DM Sans',sans-serif;background:#F4F5FF;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
        .register-container {background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);width:300px;text-align:center;}
        input{width:100%;padding:8px;margin:8px 0;box-sizing:border-box;}
        button{width:100%;padding:10px;background-color:#673DE6;color:#fff;border:none;border-radius:4px;cursor:pointer;}
        .error{color:red;}
        .success{color:green;}
        a{color:#673DE6;text-decoration:none;}
    </style>
</head>
<body>
<div class="register-container">
    <h1>Create Account</h1>
    <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
    <form method="post" action="register.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p><a href="login.php">Back to login</a></p>
</div>
</body>
</html>
