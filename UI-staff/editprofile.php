<?php
include '../dbserver/connect2.php';

session_start();

if (!isset($_SESSION['staff_id'])) {
    header('Location: loginstaff.php');
}

$staff_id = $_SESSION['staff_id'];

$stmt = $db->prepare("SELECT * FROM staff WHERE staff_id = :staff_id");
$stmt->bindParam(':staff_id', $staff_id);
$stmt->execute();

if (!$stmt) {
    die("Query failed: " . $db->errorInfo());
}

$staffProfile = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($staffProfile) > 0) {
    $staff = $staffProfile[0];
} else {
    echo "No staff found with ID: $staff_id";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Foodie Staff</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f0e1;
            margin: 0;
            padding: 0;
            color: #4e342e;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #4e342e;
            padding-top: 20px;
            color: white;
            padding-left: 20px;
            transition: 0.3s;
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
            background-color: #f5e1b1;
        }

        .sidebar .menu-icon {
            display: none;
        }

        .main-content {
    display: flex;
    justify-content: center; 
    align-items: center; 
    height: 100vh; 
    margin-left: 250px; 
    padding: 20px; 
    box-sizing: border-box;
}

.form-container {
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    padding: 60px;
    max-width: 600px; 
    width: 100%;
    text-align: center;
}


        .form-container h2 {
            font-size: 28px;
            color: #4e342e;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            color: #4e342e;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .btn {
            background-color: #f5d7b1;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #e1b993;
        }

        .profile-pic-preview {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            transition: transform 0.3s ease;
        }

        .profile-pic-preview:hover {
            transform: scale(1.05);
        }

        .back-btn {
            margin-top: 20px;
            display: inline-block;
            font-size: 16px;
            color: #4e342e;
            text-decoration: none;
        }

        .back-btn:hover {
            text-decoration: underline;
        }

        .success-msg, .error-msg {
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .sidebar a {
                font-size: 16px;
            }

            .sidebar .menu-icon {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3 style="text-align: center; color: white;">Foodie Staff</h3>
        <a href="homestaff.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="foods.php">Food Availability</a>
        <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
        <a href="editprofile.php"><i class="fas fa-user-edit"></i> Profile</a>
        <a href="logoutstaff.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="form-container">
            <h2>Staff Profile</h2>
                <!-- Full Name -->
                <div class="form-group">
                    <label for="full-name">Full Name</label>
                    <input type="text" id="full-name" name="full_name" value="<?php echo htmlspecialchars($staff["firstname"]); ?> <?php echo htmlspecialchars($staff["lastname"]); ?>" required>
                </div>
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($staff["email"]); ?>" required>
                </div>
                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="username" id="username" name="username" value="<?php echo htmlspecialchars($staff["username"]); ?>">
                </div>
        </div>
    </div>

    <script>
        function previewProfilePic(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-pic-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
