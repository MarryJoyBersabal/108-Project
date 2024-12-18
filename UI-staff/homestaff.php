<?php
include '../dbserver/connect2.php';

session_start();

if (!isset($_SESSION['staff_id'])) {
    header('Location: loginstaff.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodie | Staff Dashboard</title>
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

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3 style="text-align: center; color: white;">Foodie Staff</h3>
        <a href="homestaff.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="foods.php">Food Availability</a>
        <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
        <a href="editprofile.php"><i class="fas fa-user-edit"></i> Profile</a>
        <a href="logoutstaff.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Welcome Section -->
        <div class="hero">
            <div class="hero-content">
                <h1>Welcome to Foodee Staff Dashboard</h1>
            </div>
            <div class="hero-image">
                <img src="../assets/foodie.png" alt="Foodee Dashboard Image">
            </div>
        </div>

    </div>

</body>
</html>
