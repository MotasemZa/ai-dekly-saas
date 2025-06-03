<?php
session_start();
require_once 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $email;
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - AI Dekly SaaS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
    <style>
        body {font-family:'DM Sans',sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background-color:#F4F5FF;margin:0;}
        .login-container {background:#fff;padding:20px;box-shadow:0 2px 4px rgba(0,0,0,0.1);border-radius:8px;width:300px;text-align:center;}
        input{width:100%;padding:8px;margin:8px 0;box-sizing:border-box;}
        button{width:100%;padding:10px;background-color:#673DE6;color:#fff;border:none;border-radius:4px;cursor:pointer;}
        .error{color:red;}
        a{color:#673DE6;text-decoration:none;}
    </style>
</head>
<body>
<div class="login-container">
    <h1>Login</h1>
    <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <form method="post" action="login.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p><a href="register.php">Create account</a> | <a href="forgot_password.php">Forgot password?</a></p>
</div>
</body>
</html>
