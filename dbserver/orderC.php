<?php
include '../dbserver/connect3.php';
header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['customer_id'])) {
    header('Location: ../UI-customer/logincustomer.php');
    exit();
}

// Decode JSON request
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['customer_id'], $data['items']) || empty($data['items'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid order data']);
    exit();
}

$customerId = $data['customer_id'];
$orderItems = $data['items'];


$customer_id = $_SESSION['customer_id'];

try {
    // Begin transaction
    $db->beginTransaction();

    // Insert into `orders` table and commit first

    $db->exec("SET app.user_id TO $customer_id");
    $db->exec("SET app.user_type TO 'customer'");

    $stmtOrder = $db->prepare("INSERT INTO orders (customer_id, order_date, total_amount, status) VALUES (?, NOW(), 0, 'Pending')");
    $stmtOrder->execute([$customerId]);
    $orderId = $db->lastInsertId(); // Get the last inserted order_id

    // Temporarily defer constraints to avoid foreign key timing issues
    $db->exec("SET CONSTRAINTS ALL DEFERRED");

    // Insert each item into `orderitems` table
    $totalAmount = 0;
    $stmtItem = $db->prepare("INSERT INTO orderitems (order_id, fooditem_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($orderItems as $item) {
        $foodId = $item['foodId'];
        $quantity = $item['quantity'];

        // Fetch the price of the food item
        $stmtFood = $db->prepare("SELECT price FROM fooditems WHERE food_id = ?");
        $stmtFood->execute([$foodId]);
        $food = $stmtFood->fetch(PDO::FETCH_ASSOC);
        if (!$food) {
            throw new Exception("Invalid food item ID: $foodId");
        }

        $price = $food['price'];
        $total = $price * $quantity;
        $totalAmount += $total;

        // Insert into `orderitems`
        $stmtItem->execute([$orderId, $foodId, $quantity, $total]);
    }

    // Update total amount in `orders` table
    $stmtUpdate = $db->prepare("UPDATE orders SET total_amount = ? WHERE order_id = ?");
    $stmtUpdate->execute([$totalAmount, $orderId]);

    // Commit transaction
    $db->commit();

    // Respond with success
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction if there's an error
    $db->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
