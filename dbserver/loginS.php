<?php

include 'connect2.php';

session_start(); 

$a = $_POST['email'];
$b = $_POST['password'];

// Prepare a SQL statement to select user with the given username and password
$stmt = $db->prepare("SELECT * FROM staff WHERE email = :a AND PASSWORD_ = :b");
$stmt->bindParam(':a', $a);
$stmt->bindParam(':b', $b);
$stmt->execute();

// Fetch the first row returned by the query
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if a user was found
if ($user) {
// User found, store user information in session
    $_SESSION['staff_id'] = $user['staff_id']; // Assuming 'id' is the column in your 'users' table that uniquely identifies each user

// Redirect to homepage
    header('Location: http://localhost/108-finals/UI-staff/homestaff.php');
    exit(); // Make sure no other code is executed after redirection
} else {
// User not found, redirect back to login page with an error message
    header('Location: http://localhost/108-finals/UI-staff/login.php?error=1');
    //alert() na function sa js
    exit();
}
