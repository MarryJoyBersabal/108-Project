<?php
include 'dbserver/connect.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: loginadmin.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

$db->exec("SET app.user_id TO $admin_id");
$db->exec("SET app.user_type TO 'admin'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $food_name = $_POST['food_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $is_available = true; // Assuming the new item is available by default

    // Handle image upload
    if (isset($_FILES['food_image'])) {
        $file = $_FILES['food_image'];

        $fileName = $_FILES['food_image']['name'];
        $fileTmpName = $_FILES['food_image']['tmp_name'];
        $fileSize = $_FILES['food_image']['size'];
        $fileError = $_FILES['food_image']['error'];
        $fileType = $_FILES['food_image']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000) { // 1MB limit
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = 'assets/' . $fileNameNew;
                    if (move_uploaded_file($fileTmpName, $fileDestination)) {
                        // Insert data into the database
                        $stmt = $db->prepare("INSERT INTO fooditems (food_name, description, price, is_available, image) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$food_name, $description, $price, $is_available, $fileDestination]);

                        // Redirect to the admin page
                        header('Location: http://localhost/108-finals/UI-admin/fooditems.php');
                        exit();
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                } else {
                    echo "Your file is too big.";
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type for food image.";
        }
    }
} else {
    header('Location: http://localhost/108-finals/UI-admin/fooditems.php');
    exit();
}
?>
