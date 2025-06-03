<?php
session_start();
require_once 'config.php';
require_once 'classes/Invoice.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$invoiceModel = new Invoice($pdo);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = trim($_POST['client_name'] ?? '');
    $client_address = trim($_POST['client_address'] ?? '');
    $invoice_date = $_POST['invoice_date'] ?? '';
    $tax = floatval($_POST['tax'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');
    $items = [];
    if (isset($_POST['item_description']) && is_array($_POST['item_description'])) {
        for ($i = 0; $i < count($_POST['item_description']); $i++) {
            $desc = trim($_POST['item_description'][$i]);
            $qty = intval($_POST['item_qty'][$i]);
            $price = floatval($_POST['item_price'][$i]);
            if ($desc !== '' && $qty > 0) {
                $items[] = ['description' => $desc, 'quantity' => $qty, 'price' => $price];
            }
        }
    }
    if ($client_name && $invoice_date && $items) {
        $data = [
            'client_name' => $client_name,
            'client_address' => $client_address,
            'invoice_date' => $invoice_date,
            'tax' => $tax,
            'notes' => $notes,
            'items' => $items
        ];
        $invoiceId = $invoiceModel->create($_SESSION['user_id'], $data);
        header('Location: view_invoice.php?id=' . $invoiceId);
        exit();
    } else {
        $error = 'Please fill in all required fields and add at least one item.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Invoice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
    <style>
        body{font-family:'DM Sans',sans-serif;background:#F4F5FF;margin:0;padding:20px;}
        .container{max-width:600px;margin:auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}
        input,textarea{width:100%;padding:8px;margin:8px 0;box-sizing:border-box;}
        table{width:100%;border-collapse:collapse;margin-bottom:10px;}
        th,td{padding:8px;text-align:left;}
        button{padding:10px;background:#673DE6;color:#fff;border:none;border-radius:4px;cursor:pointer;}
        a{color:#673DE6;text-decoration:none;}
        .error{color:red;}
    </style>
    <script>
        function addRow(){
            const row=document.createElement('tr');
            row.innerHTML=`<td><input type="text" name="item_description[]" required></td><td><input type="number" name="item_qty[]" min="1" value="1" required></td><td><input type="number" step="0.01" name="item_price[]" value="0" required></td><td><button type="button" onclick="this.closest('tr').remove()">X</button></td>`;
            document.getElementById('items').appendChild(row);
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Create Invoice</h1>
    <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <form method="post" action="create_invoice.php">
        <input type="text" name="client_name" placeholder="Client Name" required>
        <textarea name="client_address" placeholder="Client Address"></textarea>
        <input type="date" name="invoice_date" required>
        <table>
            <thead>
                <tr><th>Description</th><th>Qty</th><th>Price</th><th></th></tr>
            </thead>
            <tbody id="items">
                <tr>
                    <td><input type="text" name="item_description[]" required></td>
                    <td><input type="number" name="item_qty[]" min="1" value="1" required></td>
                    <td><input type="number" step="0.01" name="item_price[]" value="0" required></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="button" onclick="addRow()">Add Item</button>
        <input type="number" step="0.01" name="tax" placeholder="Tax" value="0">
        <textarea name="notes" placeholder="Notes"></textarea>
        <button type="submit">Save Invoice</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>
