<?php
include '../dbserver/connect.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: loginadmin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodee | Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         /* General Styles */
         body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f0e1; /* Light beige background */
            margin: 0;
            padding: 0;
            color: #4e342e; /* Dark brown text */
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #4e342e; /* Dark brown background */
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
            background-color: #f5e1b1; /* Light beige hover effect */
        }

                /* Main Content */
                .main-content {
            margin-left: 250px;
            padding: 40px;
        }

        /* Welcome Hero */
        .hero {
            background-color: #f5d7b1; /* Warm beige */
            color: #4e342e;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 40px;
        }

        .hero h1 {
            font-size: 36px;
            margin: 0;
        }

        .hero p {
            font-size: 18px;
            font-style: italic;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s ease;
            position: relative;
        }

        /* Hover effects for the cards */
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .card h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #4e342e;;
        }

        .card p {
            font-size: 16px;
            color: #6c757d;
        }

        .card .btn {
            background-color: #f5d7b1; /* Soft beige */
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .card .btn:hover {
            background-color: #e1b993; /* Slightly darker beige */
        }

        /* Custom styles for card icons */
        .card i {
            font-size: 50px;
            color: #4e342e;;
        }
           
       

        .hero-image img {
            max-width: 30%;
            height: auto;
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
        <a href="fooditems.php"><i class="fas fa-box"></i> Food Menu</a>
        <a href="usersstaff.php"><i class="fas fa-users"></i> Users</a>
        <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logoutadmin.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
            <!-- Welcome Section -->
            <div class="hero">
                <div class="hero-content">
                    <h1>Welcome to Foodee Admin Dashboard</h1>
                </div>
                <div class="hero-image">
                    <img src="../assets/foodie.png" alt="Foodee Dashboard Image">
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card card-order">
                    <i class="fas fa-box-open"></i>
                    <h2>Manage Food Items</h2>
                    <p>View, add, and update food items</p>
                    <a href="fooditems.php" class="btn">View Foods</a>
                </div>
                <div class="card card-users">
                    <i class="fas fa-users"></i>
                    <h2>Users & Staff</h2>
                    <p>Manage users and their accounts</p>
                    <a href="usersstaff.php" class="btn">View Users</a>
                </div>
                <div class="card card-reports">
                    <i class="fas fa-chart-line"></i>
                    <h2>Reports</h2>
                    <p>Generate and view detailed reports</p>
                    <a href="reports.php" class="btn">View Reports</a>
                </div>
            </div>


        </div>

</body>
</html>
