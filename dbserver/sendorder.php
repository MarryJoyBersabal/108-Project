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

try {
    // Begin transaction
    $db->beginTransaction();

    $db->exec("SET app.user_id TO $customer_id");
    $db->exec("SET app.user_type TO 'customer'");

    // Insert into `orders` table
    $stmt = $db->prepare("INSERT INTO orders (customer_id, order_date, total_amount, status) VALUES (?, NOW(), ?, 'Pending')");

    $total_amount = 0;
    foreach ($orderItems as $item) {
        $total_amount += $item['quantity'] * $item['price']; // Ensure price is accurate
    }

    $stmt->execute([$customerId, $total_amount]);

    $orderId = $db->lastInsertId(); // Get the last inserted order_id

    // Insert each item into `orderitems` table
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

        // Insert into `orderitems`
        $stmtItem->execute([$orderId, $foodId, $quantity, $price]);
    }

    // Log changes (asynchronous or after transaction)
    $stmtLog = $db->prepare("INSERT INTO logs (user_id, user_type, activity, log_date, at_table) VALUES (:a, 'customer', 'INSERT', NOW(), 'orders')");
    $stmtLog->bindParam(':a', $customerId);
    $stmtLog->execute();

    // Commit transaction
    $db->commit();

    // Respond with success
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction if there's an error
    $db->rollBack();
    error_log("Transaction error: " . $e->getMessage()); // Log the error message
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
