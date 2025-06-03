<?php
session_start();
require_once 'config.php';
require_once 'classes/Invoice.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$invoiceModel = new Invoice($pdo);
$invoices = $invoiceModel->getAllByUser($_SESSION['user_id']);
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
        <p><a href="create_invoice.php">Create New Invoice</a></p>
        <?php if ($invoices): ?>
            <table style="width:100%;max-width:600px;margin:auto;border-collapse:collapse;">
                <tr><th align="left">Client</th><th>Date</th><th></th></tr>
                <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inv['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($inv['invoice_date']); ?></td>
                        <td><a href="view_invoice.php?id=<?php echo $inv['id']; ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No invoices yet.</p>
        <?php endif; ?>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
