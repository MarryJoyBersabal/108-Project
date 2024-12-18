<?php
include '../dbserver/connect3.php';

session_start();

// Allow access if the user is either a customer or an admin
if (!isset($_SESSION['customer_id']) && !isset($_SESSION['admin_id'])) {
    header('Location: logincustomer.php'); // Redirect to the appropriate login page
    exit();
}

$sql = "SELECT * FROM fooditems"; 
$stmt = $db->prepare($sql);
$stmt->execute();
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodee</title>
    <!-- Link to Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General body style */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4e1d2; /* Soft nude background */
            margin: 0;
            padding: 0;
            color: #4e342e; /* Dark brown text */
        }

        /* Navbar styling */
        nav {
            background-color: #4e342e; /* Dark brown background */
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
            color: #fff; /* White text */
            font-size: 18px;
            margin-left: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav-link:hover {
            color: #f5f0e1; /* Light beige hover effect */
            transform: translateY(-3px); /* Slight lift on hover */
        }

        /* Cart and profile icons */
        .cart-icon,
        .user-icon {
            width: 32px;
            height: 32px;
            margin-left: 20px;
            color: #fff;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .cart-icon:hover,
        .user-icon:hover {
            color: #f5f0e1; /* Light beige icon color on hover */
            transform: scale(1.1); /* Zoom in on hover */
        }

        /* Dropdown menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #4e342e; /* Dark brown background */
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            right: 0;
            border-radius: 5px;
        }

        .dropdown-content a {
            color: #f5f0e1; /* Light beige text */
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-weight: 600;
        }

        .dropdown-content a:hover {
            background-color: #3e2723; /* Slightly darker brown on hover */
            color: #fff;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Welcome section */
        .welcome-container {
            padding: 50px 20px;
            text-align: center;
            background-color: #ffffff; /* White background for text */
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-family: 'Montserrat', sans-serif; /* More modern font for headings */
            font-size: 36px;
            color: #6d4c41;
            font-weight: 700;
        }

        h3 {
            font-size: 22px;
            color: #4e342e;
            font-weight: 600;
        }

        /* Two-column layout for Foodie text and Image */
        .two-column-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 40px 20px;
        }

        .left-column {
            flex: 1;
            padding: 20px;
            text-align: left;
        }

        .left-column h2 {
            font-size: 32px;
            color: #6d4c41;
            font-weight: 700;
        }

        .left-column p {
            font-size: 18px;
            color: #4e342e;
            margin-top: 15px;
        }

        .right-column {
            flex: 1;
            text-align: center;
        }

        .right-column img {
            width: 70%;  /* Make the image smaller */
            height: auto;
            border-radius: 8px;
        }

        /* Style for the Order Now Button */
        .order-now-btn {
            display: inline-block;
            margin-top: 20px;
        }

        .order-now-btn button {
            background-color: #6d4c41; /* Dark brown background */
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .order-now-btn button:hover {
            background-color: #4e342e; /* Darker brown when hovered */
            transform: translateY(-3px); /* Slight lift effect */
        }

        /* New container for 4-image cards */
        .four-photo-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 40px 20px;
        }

        .photo-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .photo-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .photo-card h3 {
            margin-top: 15px;
            color: #6d4c41;
            font-size: 20px;
            font-weight: 700;
        }

        .photo-card p {
            margin-top: 10px;
            color: #4e342e;
        }

    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <!-- Logo or Brand Name -->
            <a href="#" class="logo">Foodie</a>

            <!-- Navigation Links -->
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

                <!-- Dropdown for Profile -->
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

    <!-- Two-column layout for Foodie text and Image -->
    <div class="two-column-container">
        <div class="left-column">
            <img src="../assets/foodie.png" alt="Foodie Logo" class="foodie-logo">
            <h2>Embark on a Flavor Journey with Foodie</h2>
            <p>Explore the finest culinary creations, indulge in gourmet delights, and savor every bite of our handpicked food treasures!</p>
            <a href="foodcustomer.php" class="order-now-btn">
                <button>Order Now</button>
            </a>
        </div>

        <div class="right-column">
            <img src="../assets/ad1.png" alt="Foodie Image">
        </div>
    </div>

    <!-- New 4-Photo Card Section -->
    <div class="four-photo-container">
        <div class="photo-card">
            <img src="../assets/bg4.png" alt="Food 1">
            <h3>Delicious Burger</h3>
            <p>Perfectly grilled with fresh ingredients.</p>
        </div>
        <div class="photo-card">
            <img src="../assets/bf4.png" alt="Food 2">
            <h3>Hotcakes and Sausage</h3>
            <p>Fluffy hotcakes and savory sausage—breakfast perfection!</p>
        </div>
        <div class="photo-card">
            <img src="../assets/bvg1.png" alt="Food 3">
            <h3>Drinks & Mixes</h3>
            <p>Brewed for cool moments.</p>
        </div>
        <div class="photo-card">
            <img src="../assets/cf2.png" alt="Food 4">
            <h3>coffee drinks </h3>
            <p>Smooth, creamy, and full of love</p>
        </div>
    </div>
</body>
</html>
