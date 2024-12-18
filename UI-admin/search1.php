<?php
include '../dbserver/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: loginadmin.php');
    exit();
}

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

$sql = "SELECT * FROM logs WHERE LOWER(activity) LIKE :query OR LOWER(user_type) LIKE :query";
$stmt = $db->prepare($sql);
$stmt->execute(['query' => '%' . strtolower($query) . '%']);
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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
            margin-left: 250px;
            padding: 20px;
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
            text-align: center;
        }

        table th {
            background-color: #f1f1f1;
            font-weight: bold;
            color: #4e342e;
        }

        table tbody tr:hover {
            background-color: #f5e1b1;
        }

        .no-results {
            text-align: center;
            font-size: 18px;
            color: #a44;
            margin-top: 20px;
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

    <div class="report-container">
        <h2>Search Results for "<?= htmlspecialchars($query) ?>"</h2>
        <?php if ($activities): ?>
            <table>
                <thead>
                    <tr>
                        <th>User Type</th>
                        <th>Activity</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><?= htmlspecialchars($activity['user_type']) ?></td>
                            <td><?= htmlspecialchars($activity['activity']) ?></td>
                            <td><?= htmlspecialchars($activity['log_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-results">No results found for "<?= htmlspecialchars($query) ?>"</p>
        <?php endif; ?>
    </div>
</body>
</html>
