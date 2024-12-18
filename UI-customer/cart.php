<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header('Location: logincustomer.php');
    exit();
}

include '../dbserver/connect3.php';

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT order_id, order_date, total_amount, status FROM orders WHERE customer_id = :customer_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_items = "
    SELECT oi.order_id, oi.quantity, fi.food_name, fi.price 
    FROM orderitems oi 
    JOIN fooditems fi ON oi.fooditem_id = fi.food_id
    WHERE oi.order_id IN (
        SELECT order_id FROM orders WHERE customer_id = :customer_id
    )";
$stmt_items = $db->prepare($sql_items);
$stmt_items->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
$stmt_items->execute();
$order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

// Group order items by order_id for easier access in modals
$grouped_order_items = [];
foreach ($order_items as $item) {
    $grouped_order_items[$item['order_id']][] = $item;
}

$totalOrdersSql = "SELECT get_total_spent_by_customer(:customer_id) AS total_spent"; 
$stmt = $db->prepare($totalOrdersSql); 
$stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT); 
$stmt->execute(); 
$totalSpent = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodee | My Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Body Style */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4e1d2;
            margin: 0;
            padding: 0;
            color: #4e342e;
        }

        /* Navbar */
        nav {
            background-color: #4e342e;
            padding: 15px 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .nav-link {
            color: #fff;
            font-size: 18px;
            margin-left: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav-link:hover {
            color: #f5f0e1;
            transform: translateY(-3px);
        }

        .cart-icon, .user-icon {
            width: 32px;
            height: 32px;
            margin-left: 20px;
            color: #fff;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .cart-icon:hover, .user-icon:hover {
            color: #f5f0e1;
            transform: scale(1.1);
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #4e342e;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            right: 0;
            border-radius: 5px;
        }

        .dropdown-content a {
            color: #f5f0e1;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-weight: 600;
        }

        .dropdown-content a:hover {
            background-color: #3e2723;
            color: #fff;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Orders Table */
        .orders-container {
            padding: 30px 20px;
            max-width: 1000px;
            margin: 0 auto;
            margin-top: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #5c4033;
            text-align: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .orders-table th, .orders-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 16px;
        }

        .orders-table th {
            background-color: #f2e0c9;
            color: #5c4033;
        }

        .orders-table tr:hover {
            background-color: #f9f9f9;
        }

        .status-pending {
            color: #ff9800;
        }

        .status-completed {
            color: #4caf50;
        }

        .orders-table td button {
            background-color: #5c4033;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .orders-table td button:hover {
            background-color: #3e2c1c;
        }

   
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .modal-close {
            color: #fff;
            background-color: #f44336;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            right: 10px;
        }

    
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .orders-table td, .orders-table th {
                padding: 8px;
            }

            .orders-table {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav>
    <div class="nav-container">
        <a href="#" class="logo">Foodie</a>
        <div>
            <a class="nav-link" href="homecustomer.php">
                <i class="fas fa-home"></i> Home
            </a>
            <a class="nav-link" href="foodcustomer.php">
                <i class="fas fa-shopping-basket"></i> Buy Now
            </a>
            <a class="nav-link" href="cart.php">
                <i class="fas fa-shopping-cart"></i> Cart
            </a>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <a class="nav-link" href="#"><i class="fas fa-user"></i> Profile</a>
                <div class="dropdown-content">
                    <a href="profilecustomer.php">View Profile</a>
                    <a href="profile-edit.php">Edit Profile</a>
                    <a href="logoutcustomer.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="orders-container">
    <h1>Your overall purchase!</h1>
    <h2 style="text-align: center;">₱<?= htmlspecialchars($totalSpent['total_spent']) ?></h2>
</div>


<!-- Orders Content -->
<div class="orders-container">
    <h1>My Orders</h1>
    <?php if ($orders): ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td>₱<?= htmlspecialchars(number_format($order['total_amount'], 2)) ?></td>
                        <td class="<?= strtolower($order['status']) == 'pending' ? 'status-pending' : 'status-completed' ?>">
                            <?= htmlspecialchars(ucfirst($order['status'])) ?>
                        </td>
                        <td>
                            <button onclick="openModal('modal-<?= $order['order_id'] ?>')">View Items</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

<!-- Modals for Order Items -->
<?php foreach ($orders as $order): ?>
    <div id="modal-<?= $order['order_id'] ?>" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('modal-<?= $order['order_id'] ?>')">Close</button>
            <h2>Items in Order <?= htmlspecialchars($order['order_id']) ?></h2>
            <?php if (!empty($grouped_order_items[$order['order_id']])): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Food Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grouped_order_items[$order['order_id']] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['food_name']) ?></td>
                                <td>₱<?= htmlspecialchars(number_format($item['price'], 2)) ?></td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td>₱<?= htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No items found for this order.</p>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>

</body>
</html>
