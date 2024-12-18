<?php

include 'connect3.php';

$a = $_POST['firstname'];
$b = $_POST['lastname'];
$c = $_POST['email'];
$d = $_POST['phone_num'];
$e = $_POST['username'];
$f = $_POST['password'];

$stmt = $db->prepare("INSERT INTO CUSTOMERS (FIRSTNAME,LASTNAME, EMAIL, PHONE, USERNAME, PASSWORD_) VALUES (:a, :b, :c, :d, :e, MD5(:f))");
$stmt->bindParam(':a', $a);
$stmt->bindParam(':b', $b);
$stmt->bindParam(':c', $c);
$stmt->bindParam(':d', $d);
$stmt->bindParam(':e', $e);
$stmt->bindParam(':f', $f);

$stmt->execute();

header('Location: http://localhost/108-finals/UI-customer/homecustomer.php');