<?php


session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: homeadmin.php");
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
            font-size: 12px;
            width: 90%;
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
        #icon {
            margin-top: -4%;
        }
        #icon{
            margin-top: -0.1%;
        }
    </style>
    <title>Foodee Admin Login</title>
</head>
<body>
    <div class="container">
        <form action="../dbserver/loginA.php" method="POST">
            <h4>Admin Login</h4>
            <div class="form-group">
                <i class="fas fa-user"></i> 
                <label for="username"></label>
                <input type="text" class="form-control" name="email" id="username" placeholder="Email" required />
            </div>
            <div class="form-group">
                <i id="icon" class="fas fa-lock"></i> 
                <label for="password"></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
            </div>
            <button type="submit" class="btn-primary">Login</button>
            <div class="form-text">
                Be part of our organization!
            </div>
        </form>
    </div>
</body>
</html>
