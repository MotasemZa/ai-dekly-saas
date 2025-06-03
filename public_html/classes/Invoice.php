<?php
class Invoice {
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($userId, $data)
    {
        $this->pdo->beginTransaction();
        $stmt = $this->pdo->prepare("INSERT INTO invoices (user_id, client_name, client_address, invoice_date, tax, notes) VALUES (?,?,?,?,?,?)");
        $stmt->execute([
            $userId,
            $data['client_name'],
            $data['client_address'],
            $data['invoice_date'],
            $data['tax'],
            $data['notes']
        ]);
        $invoiceId = $this->pdo->lastInsertId();

        $itemStmt = $this->pdo->prepare("INSERT INTO invoice_items (invoice_id, description, quantity, price) VALUES (?,?,?,?)");
        foreach ($data['items'] as $item) {
            $itemStmt->execute([$invoiceId, $item['description'], $item['quantity'], $item['price']]);
        }
        $this->pdo->commit();
        return $invoiceId;
    }

    public function getAllByUser($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM invoices WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getById($id, $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM invoices WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        $invoice = $stmt->fetch();
        if (!$invoice) {
            return null;
        }
        $itemStmt = $this->pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
        $itemStmt->execute([$id]);
        $invoice['items'] = $itemStmt->fetchAll();
        return $invoice;
    }
}
?>
