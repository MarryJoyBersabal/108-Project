<?php
include 'connect.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: UI-admin/loginadmin.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food_id = $_POST['food_id'];

    $db->exec("SET app.user_id TO $admin_id");
    $db->exec("SET app.user_type TO 'admin'");

    if (!empty($food_id)) {
        $deleteSql = "DELETE FROM fooditems WHERE food_id = :food_id";
        $stmt = $db->prepare($deleteSql);
        $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirect back to the fooditems page with a success message
            header('Location: ../UI-admin/fooditems.php?message=Food item deleted successfully');
            exit();
        } else {
            // Redirect with an error message
            header('Location: ../UI-admin/fooditems.php?error=Failed to delete food item');
            exit();
        }
    } else {
        header('Location: ../dbserver/fooditems.php?error=Invalid food ID');
        exit();
    }
} else {
    header('Location: ../dbserver/fooditems.php');
    exit();
}
?>
