<?php
include '../dbserver/connect2.php';

session_start();

if (!isset($_SESSION['staff_id'])) {
    header('Location: loginstaff.php');
    exit();
}

$staff_id = $_SESSION['staff_id'];

// Update order status if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    $db->exec("SET app.user_id TO $staff_id");
    $db->exec("SET app.user_type TO 'staff'");

    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->execute([$newStatus, $orderId]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch pending orders
$sqlPending = "SELECT * FROM orders WHERE status = 'Pending'";
$stmtPending = $db->prepare($sqlPending);
$stmtPending->execute();
$pendingOrders = $stmtPending->fetchAll(PDO::FETCH_ASSOC);

// Fetch completed orders
$sqlCompleted = "SELECT * FROM orders WHERE status IN ('Completed', 'Delivered')";
$stmtCompleted = $db->prepare($sqlCompleted);
$stmtCompleted->execute();
$completedOrders = $stmtCompleted->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f0e1;
            margin: 0;
            padding: 0;
            color: #4e342e;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #4e342e;
            padding-top: 20px;
            color: white;
            padding-left: 20px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
            margin-bottom: 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 20px;
        }

        .sidebar a:hover {
            background-color: #f5e1b1;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .orders-table th, .orders-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .orders-table th {
            background-color: #4e342e;
            color: white;
        }

        .orders-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status-select {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .update-btn {
            background-color: #4e342e;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .update-btn:hover {
            background-color: #f5e1b1;
        }

        .section-title {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 style="text-align: center; color: white;">Foodie Staff</h3>
        <a href="homestaff.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="foods.php">Food Availability</a>
        <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
        <a href="editprofile.php"><i class="fas fa-user-edit"></i> Profile</a>
        <a href="logoutstaff.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1>Pending Orders</h1>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($pendingOrders) {
                    foreach ($pendingOrders as $order) {
                        echo "<tr>
                                <td>{$order['order_id']}</td>
                                <td>{$order['total_amount']}</td>
                                <td>{$order['status']}</td>
                                <td>
                                    <form action='' method='POST'>
                                        <input type='hidden' name='order_id' value='{$order['order_id']}'>
                                        <select name='status' class='status-select'>
                                            <option value='Pending' " . ($order['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                            <option value='Completed' " . ($order['status'] == 'Completed' ? 'selected' : '') . ">Completed</option>
                                            <option value='Delivered' " . ($order['status'] == 'Delivered' ? 'selected' : '') . ">Delivered</option>
                                        </select>
                                        <button type='submit' class='update-btn'>Update</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No pending orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h1 class="section-title">Completed Orders</h1>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($completedOrders) {
                    foreach ($completedOrders as $order) {
                        echo "<tr>
                                <td>{$order['order_id']}</td>
                                <td>{$order['total_amount']}</td>
                                <td>{$order['status']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No completed orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
