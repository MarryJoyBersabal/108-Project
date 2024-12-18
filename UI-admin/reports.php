<?php
include '../dbserver/connect.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: loginadmin.php');
    exit();
}

$customerOrderSummarySql = "SELECT * FROM customer_order_summary"; 
$stmt = $db->prepare($customerOrderSummarySql);
$stmt->execute();
$customer_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$available_food_itemsSql = "SELECT * FROM available_food_items"; 
$stmt = $db->prepare($available_food_itemsSql);
$stmt->execute();
$available_food = $stmt->fetchAll(PDO::FETCH_ASSOC);

$salesSummarySql = "SELECT * FROM daily_sales_summary"; 
$stmt = $db->prepare($salesSummarySql);
$stmt->execute();
$salessummary = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['refresh'])) {
    // Refresh the materialized view
    $refreshSql = "REFRESH MATERIALIZED VIEW daily_sales_summary";
    $stmt = $db->prepare($refreshSql);
    $stmt->execute();

    // Optional: Display a message or redirect after refreshing
    header('Location: ' . $_SERVER['PHP_SELF']);  // Refresh the page to avoid resubmission
    exit();
}

$logs = "SELECT * FROM GetDetailedLogs()"; 
$stmt = $db->prepare($logs);
$stmt->execute();
$logsresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | Foodie Staff</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            transition: 0.3s;
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
            margin-left: 250px;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 28px;
            color: #4e342e;
        }

        .report-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .report-table th, .report-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .report-table th {
            background-color: #f5d7b1;
            color: #4e342e;
        }

        .report-table tbody tr:hover {
            background-color: #f5e1b1;
        }

        .btn-export {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c8e68;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-export:hover {
            background-color: #55714e;
        }

        
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 style="text-align: center; color: white;">Foodie Admin</h3>
        <a href="homeadmin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="fooditems.php"><i class="fas fa-box"></i> Food items</a>
        <a href="usersstaff.php"><i class="fas fa-users"></i> Users</a>
        <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logoutadmin.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">

                <form class="nav-search-bar" action="search1.php" method="GET">
                    <input type="text" name="query" placeholder="Search logs..." class="search-input">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
        <div class="header">
            <h1>Reports</h1>
        </div>

        <div class="report-container">
            <h1>Logs</h1>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>User Type</th>
                        <th>User Fullname</th>
                        <th>Activity</th>
                        <th>Log Date</th>
                        <th>Table</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($logsresult) {
                        $counter = 1; // For numbering rows
                        foreach ($logsresult as $log) {
                            echo "<tr>
                                    <td>{$counter}</td>
                                    <td>{$log['user_id']}</td>
                                    <td>{$log['user_type']}</td>
                                    <td>{$log['user_fullname']}</td>
                                    <td>{$log['activity']}</td>
                                    <td>" . date('Y-m-d H:i:s', strtotime($log['log_date'])) . "</td>
                                    <td>{$log['at_table']}</td>
                                </tr>";
                            $counter++;
                        }
                    } else {
                        echo "<tr><td colspan='8'>No logs available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <br>

        <div class="report-container">
            <h1>Available food items</h1>   
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Food name</th>
                        <th>Description</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($available_food) {
                    foreach ($available_food as $food) {
                        echo "<tr>
                                <td>{$food['food_name']}</td>
                                <td>{$food['description']}</td>
                                <td>{$food['price']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No food available.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>


        <div class="report-container">
            <h1>Daily Sales Summary</h1>   
            <a href="?refresh=true" class="btn-export">Refresh Summary</a>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Day</th>
                        <th>Total Orders</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Iterate over the fetched data and display each row in the table
                    $counter = 1;
                    foreach ($salessummary as $row) {
                        // Format the date for better readability (e.g., YYYY-MM-DD)
                        $formattedDate = date('Y-m-d', strtotime($row['day']));
                        $totalOrders = $row['total_orders'];
                        $totalRevenue = number_format($row['total_revenue'], 2); // Format the revenue as a number with 2 decimal places
                        ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $formattedDate; ?></td>
                            <td><?php echo $totalOrders; ?></td>
                            <td><?php echo '$' . $totalRevenue; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="content-container">
        <h1 style="text-align: center;">Summary of customer orders</h1>
        <table>
            <thead>
                <tr>
                <th>First Name</th> 
                <th>Last Name</th> 
                <th>Total Orders</th> 
                <th>Total Spent</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($customer_orders) {
                    foreach ($customer_orders as $customer_order) {
                        echo "<tr>
                                <td>{$customer_order['firstname']}</td>
                                <td>{$customer_order['lastname']}</td>
                                <td>{$customer_order['total_orders']}</td>
                                <td>{$customer_order['total_spent']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No customers found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
</body>
</html>
