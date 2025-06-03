<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - AI Dekly SaaS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #F4F5FF;
        }
        .wrapper {
            text-align: center;
        }
        a {
            color: #673DE6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
        <p>This is a placeholder dashboard.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
