<?php
include '../dbserver/connect3.php';

session_start();

if (!isset($_SESSION['customer_id'])) {
    header('Location: logincustomer.php');
}

$customer_id = $_SESSION['customer_id'];

$stmt = $db->prepare("SELECT * FROM customers WHERE customer_id = :customer_id");
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();

if (!$stmt) {
    die("Query failed: " . $db->errorInfo());
}

$customerProfile = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($customerProfile) > 0) {
    $customer = $customerProfile[0];
} else {
    echo "No customer found with ID: $customer_id";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5ebe0;
            margin: 0;
            padding: 0;
            color: #4e342e;
        }

        nav {
            position: sticky;
            top: 0;
            background-color: #4e342e;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .logo {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-link {
            color: #fff;
            font-size: 16px;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: #3e2723;
            color: #fff;
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

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(to bottom, #fff, #fce4d7);
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            position: relative;
        }

        .profile-pic-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #4e342e;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .profile-pic-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-header h1 {
            font-size: 28px;
            color: #5a4027;
        }

        .profile-header p {
            font-size: 16px;
            color: #6d4c41;
        }

        .profile-section {
            margin-bottom: 20px;
            animation: fadeIn 0.8s ease-in-out;
        }

        .profile-section h2 {
            font-size: 20px;
            color: #5a4027;
            margin-bottom: 10px;
        }

        .profile-section div {
            font-size: 16px;
            color: #333;
            padding: 10px;
            background-color: #fafafa;
            border-radius: 5px;
        }

        hr {
            border: 0;
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-header h1 {
                font-size: 24px;
            }

            .nav-links {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .container {
                padding: 15px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <nav>
        <a href="#" class="logo">Foodie</a>
        <div class="nav-links">
            <a class="nav-link" href="homecustomer.php">
                <i class="fas fa-home"></i> Home
            </a>
            <a class="nav-link" href="foodcustomer.php">
                <i class="fas fa-shopping-basket"></i> Buy Now
            </a>
            <a class="nav-link" href="cart.php">
                <i class="fas fa-shopping-cart"></i> Cart
            </a>
            <div class="dropdown">
                <a class="nav-link" href="#"><i class="fas fa-user"></i> Profile</a>
                <div class="dropdown-content">
                    <a href="profilecustomer.php">View Profile</a>
                    <a href="profile-edit.php">Edit Profile</a>
                    <a href="logoutcustomer.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="profile-header">
            <div class="profile-pic-container">
                <img src="../assets/eden.jpg" alt="Profile Picture">
            </div>
            <div>
                <h1><?php echo htmlspecialchars($customer["firstname"] . " " . $customer["lastname"]); ?></h1>
                <p>Welcome back to Foodie! Here's your profile.</p>
            </div>
        </div>

        <div class="profile-section">
            <h2>Full Name</h2>
            <div><?php echo htmlspecialchars($customer["firstname"] . " " . $customer["lastname"]); ?></div>
        </div>
        <hr>
        <div class="profile-section">
            <h2>Username</h2>
            <div><?php echo htmlspecialchars($customer["username"]); ?></div>
        </div>
        <hr>
        <div class="profile-section">
            <h2>Phone Number</h2>
            <div><?php echo htmlspecialchars($customer["phone"]); ?></div>
        </div>
        <hr>
        <div class="profile-section">
            <h2>Email Address</h2>
            <div><?php echo htmlspecialchars($customer["email"]); ?></div>
        </div>
    </div>
</body>
</html>
