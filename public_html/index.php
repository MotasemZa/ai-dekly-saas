<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Dekly SaaS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
    <style>
        body {font-family:'DM Sans',sans-serif;background:#F4F5FF;margin:0;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;text-align:center;}
        h1{color:#36344D;margin-bottom:20px;}
        a{color:#673DE6;text-decoration:none;margin:0 10px;}
        .links{margin-top:20px;}
    </style>
</head>
<body>
    <?php session_start(); ?>
    <h1>Welcome to AI Dekly SaaS</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p><a href="dashboard.php">Go to Dashboard</a> | <a href="logout.php">Logout</a></p>
    <?php else: ?>
        <p>Please <a href="login.php">login</a> or <a href="register.php">create an account</a>.</p>
        <div class="links">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    <?php endif; ?>
</body>
</html>
