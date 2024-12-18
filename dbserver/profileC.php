<?php
include 'connect3.php';

session_start();

if (!isset($_SESSION['customer_id'])) {
    header('Location: logincustomer.php');
    exit();
}

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone_num = $_POST['phone'];
$email = $_POST['email'];
$username = $_POST['username'];
$password_ = $_POST['password'];
$customer_id = $_SESSION['customer_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    try {
        // Begin a transaction
        $db->beginTransaction();

        // Delete orders associated with the customer
        $sql_orders = "DELETE FROM orders WHERE customer_id = :customer_id";
        $stmt_orders = $db->prepare($sql_orders);
        $stmt_orders->bindParam(':customer_id', $customer_id);
        $stmt_orders->execute();

        // Delete the customer
        $sql_customer = "DELETE FROM customers WHERE customer_id = :customer_id";
        $stmt_customer = $db->prepare($sql_customer);
        $stmt_customer->bindParam(':customer_id', $customer_id);
        $stmt_customer->execute();

        // Commit the transaction
        $db->commit();

        echo "Customer and associated orders deleted successfully";

        // Destroy the session after deleting the account
        session_destroy();

        // Redirect to the login page
        header("Location:  http://localhost/108-finals/UI-customer/logincustomer.php");
    } catch (PDOException $e) {
        // Rollback the transaction in case of error
        $db->rollBack();
        echo "Error deleting customer: " . $e->getMessage();
    }
}

$query = "SELECT * FROM customers WHERE customer_id = $customer_id";
$result = $db->query($query);
$row = $result->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    // Check and update each field individually
    try {
        if ($firstname != $row['firstname']) {
            $update_query = "UPDATE customers SET firstname = '$firstname' WHERE customer_id = $customer_id";
            $stmt = $db->prepare($update_query);
            $stmt->execute();
        }
        if ($lastname != $row['lastname']) {
            $update_query = "UPDATE customers SET lastname = '$lastname' WHERE customer_id = $customer_id";
            $stmt = $db->prepare($update_query);
            $stmt->execute();
        }
        if ($phone_num != $row['phone']) {
            $update_query = "UPDATE customers SET phone = '$phone_num' WHERE customer_id = $customer_id";
            $stmt = $db->prepare($update_query);
            $stmt->execute();
        }
        if ($email != $row['email']) {
            $update_query = "UPDATE customers SET email = '$email' WHERE customer_id = $customer_id";
            $stmt = $db->prepare($update_query);
            $stmt->execute();
        }
        if ($username != $row['username']) {
            $update_query = "UPDATE customers SET username = '$username' WHERE customer_id = $customer_id";
            $stmt = $db->prepare($update_query);
            $stmt->execute();
        }

        if (empty($password_)) {
            // password field is empty or not updated
            //  show an error message or taking a default action
            echo "Password is not updated";
        } else {
            // user has input a password or updated it
            // Sanitize the input and hash the password securely
            $hashed_password = md5($password_); // Note: MD5 is not a secure hash function, consider using stronger alternatives like bcrypt

            // Now you can safely use $hashed_password in your SQL query
            $update_query = "UPDATE customers SET password_ = '$hashed_password' WHERE customer_id = $customer_id";
            $stmt = $db->prepare($update_query);
            $stmt->execute();
        }

        header("Location:  http://localhost/108-finals/UI-customer/profilecustomer.php");
    } catch (PDOException $e) {
        // Rollback the transaction in case of error
        $db->rollBack();
        echo "Error updating customer: " . $e->getMessage();
    }
}
