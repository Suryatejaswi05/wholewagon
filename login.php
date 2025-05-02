<?php
session_start();
include("db.php"); 
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: admin-panel.php");
    } else {
        header("Location: homepage.php");
    }
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['email']; // Used in homepage.php

            if ($password_input === "admin@123") {
                $_SESSION['role'] = 'admin';
                header("Location: admin-panel.php");
            } else {
                $_SESSION['role'] = 'user';
                header("Location: homepage.php");
            }
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            width: 320px;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #4B0082;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 95%;
            padding: 10px;
            background-color: #4B0082;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #360062;
        }

        .message {
            color: red;
            margin: 10px 0;
        }

        a {
            color: #007bff;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Login Here</h2>
        <?php if (!empty($error)) echo "<div class='message'>$error</div>"; ?>
        <input type="email" name="email" placeholder="Email ID" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <a href="forgot-password.php">Forgot Password?</a><br><br>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</body>
</html>
