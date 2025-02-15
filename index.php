<?php
session_start();
include './connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['admin_login'])) {
        $username = $_POST['admin_username'];
        $password = $_POST['admin_password'];

        $query = "SELECT * FROM admins WHERE username = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin && $password == $admin['PASSWORD']) {
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $admin_error = "Invalid username or password";
        }
    } elseif (isset($_POST['user_login'])) {
        $username = $_POST['user_username'];
        $password = $_POST['user_password'];

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $password == $user['PASSWORD']){
            $_SESSION['user_id'] = $user['id'];
            header('Location: user_dashboard.php');
            exit;
        } else {
            $user_error = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Municipal Corporation Management System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(120deg, #a8e063 0%, #56ab2f 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #333;
            font-size: 36px;
            margin: 0;
        }
        .container {
            display: flex;
            justify-content: space-around;
            width: 80%;
            text-align: center;
        }
        .login-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 40%;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
        }
        p {
            color: red;
        }
        .link {
            margin-top: 10px;
            display: block;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Municipal Corporation Complaint Management System</h1>
    </div>
    <div class="container">
        <div class="login-box">
            <h2>Admin Login</h2>
            <form method="POST" action="">
                <input type="text" name="admin_username" placeholder="Admin Username" required>
                <input type="password" name="admin_password" placeholder="Admin Password" required>
                <button type="submit" name="admin_login">Login</button>
                <?php if (isset($admin_error)) { echo "<p>$admin_error</p>"; } ?>
            </form>
        </div>
        <div class="login-box">
            <h2>User Login</h2>
            <form method="POST" action="">
                <input type="text" name="user_username" placeholder="User Username" required>
                <input type="password" name="user_password" placeholder="User Password" required>
                <button type="submit" name="user_login">Login</button>
                <?php if (isset($user_error)) { echo "<p>$user_error</p>"; } ?>
            </form>
            <div class="link">
                <a href="create_account.php">Create Account</a>
            </div>
        </div>
    </div>
</body>
</html>
