<?php 
include '../dbserver/connect3.php';

session_start();

if (!isset($_SESSION['customer_id'])) {
    header('Location: logincustomer.php');
}

$customer_id = $_SESSION['customer_id'];

// Prepare SQL statement to retrieve orders for the current customer
$stmt = $db->prepare("SELECT * FROM customers WHERE customer_id = :customer_id");
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();

if (!$stmt) {
    die("Query failed: " . $db->errorInfo());
}

$customerProfile = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($customerProfile) > 0) {
    foreach ($customerProfile as $customer) {
        // Displaying the customer details
        $customer["customer_id"] . "<br>";
        $customer["firstname"]. " " . $customer["lastname"] . "<br>";
        $customer["username"] . "<br>";
        $customer["phone"] . "<br>";
        $customer["email"] . "<br>";
        $customer["password_"] . "<br>";
    }
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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4e1d2;
            margin: 0;
            padding: 0;
            color: #4e342e;
        }

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

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #8b5c3b;
            text-align: center;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f5eb;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #8b5c3b;
            background-color: #fff;
        }

        button[type="submit"] {
            background-color: #8b5c3b;
            color: white;
            font-size: 16px;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #5d3f1a;
        }

    </style>
</head>
<body>
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

<div class="container">
    <h1>Edit Customer Profile</h1>
    <form action="../dbserver/profileC.php" method="POST">
        <div class="form-row">
            <div style="flex: 1;">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($customer['firstname']); ?>">
            </div>
            <div style="flex: 1;">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($customer['lastname']); ?>">
            </div>
        </div>
        <div class="form-row">
            <div style="flex: 1;">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>">
            </div>
            <div style="flex: 1;">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>">
            </div>
        </div>
        <div class="form-row">
            <div style="flex: 1;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($customer['username']); ?>">
            </div>
            <div style="flex: 1;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
        </div>
        <button type="submit" name="save">Save Changes</button>
    </form>
</div>
</body>
</html>
