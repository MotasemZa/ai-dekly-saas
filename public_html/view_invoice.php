<?php
session_start();
require_once 'config.php';
require_once 'classes/Invoice.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$invoiceModel = new Invoice($pdo);
$id = intval($_GET['id'] ?? 0);
$invoice = $invoiceModel->getById($id, $_SESSION['user_id']);
if (!$invoice) {
    die('Invoice not found');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice <?php echo $invoice['id']; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
    <style>
        body{font-family:'DM Sans',sans-serif;background:#F4F5FF;margin:0;padding:20px;}
        .container{max-width:600px;margin:auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}
        table{width:100%;border-collapse:collapse;margin-bottom:10px;}
        th,td{padding:8px;text-align:left;}
        a{color:#673DE6;text-decoration:none;}
        button{padding:10px;background:#673DE6;color:#fff;border:none;border-radius:4px;cursor:pointer;}
    </style>
</head>
<body>
<div class="container">
    <h1>Invoice <?php echo $invoice['id']; ?></h1>
    <p><strong>Client:</strong> <?php echo htmlspecialchars($invoice['client_name']); ?></p>
    <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($invoice['client_address'])); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($invoice['invoice_date']); ?></p>
    <table>
        <tr><th>Description</th><th>Qty</th><th>Price</th></tr>
        <?php foreach ($invoice['items'] as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['description']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo htmlspecialchars($item['price']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><strong>Tax:</strong> <?php echo htmlspecialchars($invoice['tax']); ?></p>
    <p><strong>Notes:</strong><br><?php echo nl2br(htmlspecialchars($invoice['notes'])); ?></p>
    <p>
        <a href="download_invoice.php?id=<?php echo $invoice['id']; ?>">Download PDF</a> |
        <a href="dashboard.php">Back to Dashboard</a>
    </p>
</div>
</body>
</html>
