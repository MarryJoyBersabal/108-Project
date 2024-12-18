<?php
include '../dbserver/connect.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: loginadmin.php');
    exit();
}

// Fetch all food items, ordered by food_id
$fooditemsSql = "SELECT * FROM fooditems ORDER BY food_id ASC";
$stmt = $db->prepare($fooditemsSql);
$stmt->execute();
$fooditems = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        .content-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .add-food-item-form {
            border-radius: 15px;
            box-shadow: 0 0px 0px rgba(0, 0, 0, 0.15);
            padding: 15px;
            max-width: 600px;
            margin: 30px auto;
            transition: transform 0.3s ease;
        }

        .add-food-item-form h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #4e342e;
        }

        .add-food-item-form label {
            display: block;
            margin-bottom: 5px;
        }

        .add-food-item-form input, .add-food-item-form select, .add-food-item-form textarea {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .add-food-item-form button {
            background-color: #4e342e;
            color: white;
            padding: 10px 10px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
        }

        .add-food-item-form button:hover {
            background-color: #f5e1b1;
        }

        /* Table styles */
        table {
            width: 100%;
            margin: 25px auto;
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

        table img {
            max-width: 100px;
            height: auto;
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
        <div class="content-container">
            <!-- Add Food Item Form -->
            <div class="add-food-item-form">
                <h2>Add New Food Item</h2>
                <form action="../addfood.php" method="POST" enctype="multipart/form-data">
                    <label for="food_name">Food Name:</label>
                    <input type="text" id="food_name" name="food_name" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>

                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required>

                    <label for="food_image">Upload Image:</label>
                    <input type="file" id="food_image" name="food_image" accept="image/*" required>

                    <button type="submit">Add Food Item</button>
                </form>
            </div>

            <!-- Food Items Table -->
            <div class="food-items-table" style="flex: 2;">
                <h1 style="text-align: center;">Food Items</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Food ID</th>
                            <th>Food Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($fooditems) {
                            foreach ($fooditems as $fooditem) {
                                echo "<tr>
                                        <td>{$fooditem['food_id']}</td>
                                        <td>{$fooditem['food_name']}</td>
                                        <td>{$fooditem['description']}</td>
                                        <td>{$fooditem['price']}</td>

                                        <td>
                                            <form action='../dbserver/deletefood.php' method='POST' style='display:inline;'>
                                                <input type='hidden' name='food_id' value='{$fooditem['food_id']}'>
                                                <button type='submit' onclick=\"return confirm('Are you sure you want to delete this item?');\">Delete</button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No food items found.</td></tr>";
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</body>
</html>
