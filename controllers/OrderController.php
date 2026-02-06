<?php
session_start();

require_once '../config/database.php';
require_once '../models/Order.php';
require_once '../models/Movie.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php?action=login');
    exit();
}

class OrderController {
    private $orderModel;
    private $movieModel;

    public function __construct($database) {
        $this->orderModel = new Order($database);
        $this->movieModel = new Movie($database);
    }

    // Create a new order
    public function createOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $movieId = $_POST['movie_id'];
            $borrowerId = $_SESSION['user_id'];
            
            // Check if movie exists and is available
            $movie = $this->movieModel->getMovieById($movieId);
            if (!$movie || $movie['status'] !== 'available') {
                $error = "Der Film ist nicht verfügbar.";
                include '../views/error.php';
                return;
            }
            
            // Create the order
            $orderId = $this->orderModel->createOrder($borrowerId, $movieId);
            
            if ($orderId) {
                // Update movie status to ordered
                $updateStmt = $GLOBALS['connection']->prepare("UPDATE movies SET status = 'ordered' WHERE id = ?");
                $updateStmt->bind_param("i", $movieId);
                $updateStmt->execute();
                
                $success = "Bestellung erfolgreich erstellt!";
                header('Location: ../index.php?page=my_orders');
                exit();
            } else {
                $error = "Fehler beim Erstellen der Bestellung.";
                include '../views/error.php';
                return;
            }
        }
    }

    // Update order status
    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];
            
            // Only allow specific status updates
            if (!in_array($status, ['pending', 'sent', 'received', 'confirmed'])) {
                $error = "Ungültiger Status.";
                include '../views/error.php';
                return;
            }
            
            // Check if user is the owner of the movie in this order
            $orderCheckStmt = $GLOBALS['connection']->prepare("
                SELECT o.id, m.owner_id 
                FROM orders o 
                JOIN movies m ON o.movie_id = m.id 
                WHERE o.id = ? AND m.owner_id = ?
            ");
            $orderCheckStmt->bind_param("ii", $orderId, $_SESSION['user_id']);
            $orderCheckStmt->execute();
            $orderResult = $orderCheckStmt->get_result();
            
            if ($orderResult->num_rows === 0) {
                $error = "Keine Berechtigung, diesen Auftrag zu aktualisieren.";
                include '../views/error.php';
                return;
            }
            
            // Update the order status
            if ($this->orderModel->updateOrderStatus($orderId, $status)) {
                $success = "Auftragsstatus erfolgreich aktualisiert.";
                
                // If the status is 'received', update the movie status to 'available' for future borrowing
                if ($status === 'received') {
                    $getMovieStmt = $GLOBALS['connection']->prepare("
                        SELECT movie_id FROM orders WHERE id = ?
                    ");
                    $getMovieStmt->bind_param("i", $orderId);
                    $getMovieStmt->execute();
                    $movieId = $getMovieStmt->get_result()->fetch_assoc()['movie_id'];
                    
                    $updateMovieStmt = $GLOBALS['connection']->prepare("
                        UPDATE movies SET status = 'available' WHERE id = ?
                    ");
                    $updateMovieStmt->bind_param("i", $movieId);
                    $updateMovieStmt->execute();
                }
                
                // Redirect back to the appropriate page
                if ($status === 'sent') {
                    header('Location: ../index.php?page=my_orders');
                } else {
                    header('Location: ../index.php?page=orders_for_me');
                }
                exit();
            } else {
                $error = "Fehler beim Aktualisieren des Auftragsstatus.";
                include '../views/error.php';
                return;
            }
        }
    }

    // View user's orders (as borrower)
    public function viewMyOrders() {
        $borrowerId = $_SESSION['user_id'];
        $orders = $this->orderModel->getOrdersByBorrower($borrowerId);
        include '../views/my_orders.php';
    }

    // View orders for user (as owner)
    public function viewOrdersForMe() {
        $ownerId = $_SESSION['user_id'];
        $orders = $this->orderModel->getOrdersForOwner($ownerId);
        include '../views/orders_for_me.php';
    }
}

$orderController = new OrderController($connection);

$action = $_GET['action'] ?? '';
switch ($action) {
    case 'create':
        $orderController->createOrder();
        break;
    case 'update_status':
        $orderController->updateOrderStatus();
        break;
    case 'my_orders':
        $orderController->viewMyOrders();
        break;
    case 'orders_for_me':
        $orderController->viewOrdersForMe();
        break;
    default:
        $orderController->viewMyOrders();
        break;
}
?>