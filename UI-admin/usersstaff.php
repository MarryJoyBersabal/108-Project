<?php
include '../dbserver/connect.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: loginadmin.php');
    exit();
}

// Fetch all customers and staff
$sql = "SELECT * FROM staff"; 
$stmt = $db->prepare($sql);
$stmt->execute();
$staff_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$customersql = "SELECT * FROM customers"; 
$stmt = $db->prepare($customersql);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Foodie Staff</title>
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

        .table-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .user-table th {
            background-color: #f5d7b1;
            color: #4e342e;
        }

        .user-table tbody tr:hover {
            background-color: #f5e1b1;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn-edit, .btn-delete {
            padding: 8px 12px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-edit {
            background-color: #6c8e68;
        }

        .btn-edit:hover {
            background-color: #55714e;
        }

        .btn-delete {
            background-color: #d9534f;
        }

        .btn-delete:hover {
            background-color: #b52d28;
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
        <a href="fooditems.php"><i class="fas fa-box"></i> Food Items</a>
        <a href="usersstaff.php"><i class="fas fa-users"></i> Users</a>
        <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logoutadmin.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Manage Users</h2>
        </div>
        <div class="table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Phone|Email</th>
                        <th>Registered date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($customers) {
                    foreach ($customers as $customer) {
                        echo "<tr>
                                <td>{$customer['customer_id']}</td>
                                <td>{$customer['firstname']} {$customer['lastname']}</td>
                                <td>{$customer['phone']} {$customer['email']}</td>
                                <td>{$customer['registered_date']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No customers found.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        
        <div class="content-container">
        <h1 style="text-align: center;">Staff</h1>
        <table>
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($staff_list) {
                    foreach ($staff_list as $staff) {
                        echo "<tr>
                                <td>{$staff['staff_id']}</td>
                                <td>{$staff['firstname']} {$staff['lastname']}</td>
                                <td>{$staff['email']}</td>
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
