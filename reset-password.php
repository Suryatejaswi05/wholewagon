<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new_password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif ($entered_otp != $_SESSION['otp']) {
        $error = "Invalid OTP.";
    } else {
        $email = $_SESSION['reset_email'];
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        if ($stmt->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['reset_email']);
            echo "<script>alert('Password reset successful!');</script>";
            header("refresh:1;url=login.php");
            exit();
        } else {
            $error = "Failed to update password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
    <h2>Reset Password</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required><br><br>
        <input type="password" name="new_password" placeholder="New Password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
