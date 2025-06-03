<?php
session_start();
require_once 'config.php';
require_once 'classes/Invoice.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
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
$itemsHtml = '';
foreach ($invoice['items'] as $item) {
    $itemsHtml .= '<tr><td>'.htmlspecialchars($item['description']).'</td><td>'.htmlspecialchars($item['quantity']).'</td><td>'.htmlspecialchars($item['price']).'</td></tr>';
}
$html = '<h1>Invoice '.$invoice['id'].'</h1>';
$html .= '<p><strong>Client:</strong> '.htmlspecialchars($invoice['client_name']).'<br>'; 
$html .= '<strong>Address:</strong> '.nl2br(htmlspecialchars($invoice['client_address'])).'<br>';
$html .= '<strong>Date:</strong> '.htmlspecialchars($invoice['invoice_date']).'</p>';
$html .= '<table width="100%" border="1" cellpadding="4" cellspacing="0"><tr><th>Description</th><th>Qty</th><th>Price</th></tr>'.$itemsHtml.'</table>';
$html .= '<p><strong>Tax:</strong> '.htmlspecialchars($invoice['tax']).'</p>';
$html .= '<p>'.nl2br(htmlspecialchars($invoice['notes'])).'</p>';
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream('invoice_'.$invoice['id'].'.pdf');
exit();
?>
