<?php
include 'connect3.php';

session_start(); 

$a = $_POST['username'];
$b = $_POST['password'];

// Prepare a SQL statement to select user with the given username and password
$stmt = $db->prepare("SELECT * FROM customers WHERE USERNAME = :a AND PASSWORD_ = MD5(:b)");
$stmt->bindParam(':a', $a);
$stmt->bindParam(':b', $b);
$stmt->execute();

// Fetch the first row returned by the query
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if a user was found
if ($user) {
    // User found, store user information in session
    $_SESSION['customer_id'] = $user['customer_id'];

    // Redirect to homepage
    header('Location: http://localhost/108-finals/UI-customer/homecustomer.php');
    exit(); // Make sure no other code is executed after redirection
} else {
    // User not found, redirect back to login page with an error message
    header('Location: http://localhost/108-finals/UI-customer/login.php?error=1');
    exit();
}
?>
