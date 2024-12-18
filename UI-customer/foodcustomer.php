<?php
include '../dbserver/connect3.php';

session_start();

if (!isset($_SESSION['customer_id'])) {
    header('Location: logincustomer.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General body style */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4e1d2;
            margin: 0;
            padding: 0;
            color: #4e342e; 
        }

        /* Navbar styling */
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
            color: #f5f0e1; 
            transform: scale(1.1); 
        }

        /* Dropdown menu */
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

        /* Welcome section */
        .welcome-container {
            padding: 50px 20px;
            text-align: center;
            background-color: #ffffff; 
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-family: 'Montserrat', sans-serif; 
            font-size: 36px;
            color: #6d4c41;
            font-weight: 700;
        }

        h3 {
            font-size: 22px;
            color: #4e342e;
            font-weight: 600;
        }

        .food-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
            gap: 20px;
            padding: 20px;
        }

        .food-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .food-card:hover {
            transform: translateY(-5px); 
            background-color: #f3f1e6; 
        }

        .food-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .food-card p {
            color: #6d4c41; 
            margin: 8px 0;
            line-height: 1.5; 
            font-size: 16px;
        }

        .food-card .price {
            font-weight: bold;
            color: #8d6e63; 
            margin: 10px 0;
        }

        .food-card .availability {
            color: #388e3c; 
            font-size: 14px;
        }

        .food-card button {
            background-color: #8d6e63; 
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .food-card button:hover {
            background-color: #6d4c41; 
            transform: scale(1.05); 
        }

        footer {
            background-color: #f5f0e1;
            padding: 20px;
            text-align: center;
        }

        footer p {
            color: #4e342e;
            font-size: 14px;
        }
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
        .order-list {
            margin-top: 20px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .nav-search-bar {
    display: inline-flex;
    align-items: center;
    margin: 0 10px;
    background-color: #f5f0e1; 
    border: 1px solid #ddd; 
    border-radius: 8px; 
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
}

.nav-search-bar:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
} 

.search-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 16px;
    padding: 5px;
    background: transparent;
    color: #4e342e; 
}

.search-btn {
    background-color: #6d4c41; 
    border: none;
    border-radius: 8px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.search-btn:hover {
    background-color: #4e342e; 
    transform: scale(1.1); 
}

.search-btn i {
    margin: 0;
}
#icon{
            margin-top: -4%;
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
            <form class="nav-search-bar" action="search1.php" method="GET">
                <input type="text" name="query" placeholder="Search delicious food..." class="search-input">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>

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

    <div class="food-container">
        <?php
        if ($foods) {
            foreach ($foods as $food) {
                echo '<div class="food-card">';
                echo '<img src="../' . htmlspecialchars($food['image']) . '" alt="' . htmlspecialchars($food['food_name']) . '">';
                echo '<h3>' . htmlspecialchars($food['food_name']) . '</h3>';
                echo '<p>' . htmlspecialchars($food['description']) . '</p>';
                echo '<p class="price">₱' . htmlspecialchars($food['price']) . '</p>';
                // Check availability explicitly
                if ($food['is_available']) {
                    echo '<p class="availability">AVAILABLE</p>';
                } else {
                    echo '<p class="availability">NOT AVAILABLE</p>';
                }
                echo '<div>';
                echo '<label for="quantity-' . $food['food_id'] . '">Quantity: </label>';
                echo '<input type="number" id="quantity-' . $food['food_id'] . '" min="1" value="1" style="width: 50px;">';
                echo '</div>';
                echo '<button onclick="addToOrder(' . $food['food_id'] . ', \'' . htmlspecialchars($food['food_name']) . '\')">Add to Order</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No food items available.</p>';
        }
        ?>
    </div>

    <button onclick="openModal()" style="position: fixed; bottom: 20px; right: 20px; background-color: #d4af7f; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
        View Order
    </button>

    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Your Order</h2>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>     
            <div id="orderList" class="order-list">
                <!-- Order items here -->
            </div>
            <input type="hidden" id="customerId" value="<?= htmlspecialchars($_SESSION['customer_id']) ?>">
            <button onclick="submitOrder()" style="margin-top: 20px; background-color: #d4af7f; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Submit Order
            </button>
        </div>
    </div>

    <script src="../js/buyfood.js"></script>
</body>
</html>

<?php $pdo = null; ?>