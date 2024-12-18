<?php


session_start();

if (isset($_SESSION['customer_id'])) {
    header("Location: homecustomer.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style type="text/css">
        body {
            background-color: #f5f5dc; 
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #4a4a4a;
        }
        .container {
            background: rgba(255, 248, 240, 0.95);
            border-radius: 15px;
            padding: 30px;
            max-width: 400px;
            margin: 5% auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        h4 {
            color: #6d4c41;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            position: relative;
            margin-bottom: 18px;
        }
        .form-group i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #b68d6a;
            font-size: 18px;
        }
        .form-control {
            background: #fffaf3;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 10px 10px 40px;
            font-size: 14px;
            width: 88%;
            color: #4a4a4a;
        }
        .form-control:focus {
            outline: none;
            border-color: #d2a679;
            box-shadow: 0 0 5px rgba(210, 166, 121, 0.5);
        }
        .btn-primary {
            background-color: #d2a679;
            border: none;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            display: block;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #b68d6a;
        }
        .form-text {
            margin-top: 15px;
            text-align: center;
            color: #5a5a5a;
            font-size: 14px;
        }
        .form-text a {
            color: #d2a679;
            text-decoration: none;
            font-weight: bold;
        }
        .form-text a:hover {
            text-decoration: underline;
        }
        .form-check {
            margin-top: 15px;
        }
        .form-check-input {
            margin-right: 10px;
        }
    </style>
    <title>Foodee</title>
</head>
<body>
    <div class="container">
        <form action="../dbserver/registerC.php" method="POST">
            <h4>Register</h4>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <label for="firstname"></label>
                <input type="text" class="form-control" name="firstname" placeholder="First Name" required />
            </div>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <label for="lastname"></label>
                <input type="text" class="form-control" name="lastname" placeholder="Last Name" required />
            </div>
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <label for="email"></label>
                <input type="email" class="form-control" name="email" placeholder="Email" />
            </div>
            <div class="form-group">
                <i class="fas fa-phone"></i>
                <label for="phone_num"></label>
                <input type="text" class="form-control" name="phone_num" placeholder="Phone Number" required />
            </div>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <label for="username"></label>
                <input type="text" class="form-control" name="username" placeholder="Username" required />
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <label for="password"></label>
                <input type="password" class="form-control" name="password" placeholder="Password" required />
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <label for="checkpass"></label>
                <input type="password" class="form-control" name="checkpass" placeholder="Confirm Password" required />
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkcon" required />
                <label class="form-check-label" for="checkcon">
                    I accept the <a href="#">Terms and Conditions</a>
                </label>
            </div>
            <button type="submit" class="btn-primary">Register</button>
            <div class="form-text">
                Already have an account? <a href="http://localhost/108-finals/UI-customer/logincustomer.php">Login</a>
            </div>
        </form>
    </div>
</body>
</html>
