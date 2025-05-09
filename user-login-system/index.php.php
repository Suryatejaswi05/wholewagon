<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: auth.php"); // ✅ corrected
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .message-box {
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            color: #4B0082;
        }

        button {
            padding: 10px;
            background-color: #4B0082;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #3b0066;
        }

        a {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>Welcome to the Homepage, <?php echo $_SESSION['username']; ?>!</h2>
        <p>You're successfully logged in as a user.</p>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
