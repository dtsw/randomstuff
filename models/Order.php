<?php
class Order {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Create a new order
    public function createOrder($borrowerId, $movieId) {
        $status = 'pending'; // Initial status
        
        $stmt = $this->db->prepare("INSERT INTO orders (borrower_id, movie_id, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $borrowerId, $movieId, $status);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        } else {
            return false;
        }
    }

    // Update order status
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $orderId);
        
        return $stmt->execute();
    }

    // Get orders by borrower
    public function getOrdersByBorrower($borrowerId) {
        $stmt = $this->db->prepare("
            SELECT o.*, m.title as movie_title, u.username as owner_name
            FROM orders o
            LEFT JOIN movies m ON o.movie_id = m.id
            LEFT JOIN users u ON m.owner_id = u.id
            WHERE o.borrower_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $borrowerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get orders by owner
    public function getOrdersForOwner($ownerId) {
        $stmt = $this->db->prepare("
            SELECT o.*, m.title as movie_title, u.username as borrower_name
            FROM orders o
            LEFT JOIN movies m ON o.movie_id = m.id
            LEFT JOIN users u ON o.borrower_id = u.id
            WHERE m.owner_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>