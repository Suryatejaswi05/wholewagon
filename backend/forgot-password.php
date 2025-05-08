<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['reset_email'] = $email;

        // Email sending logic
        $subject = "Your OTP for Password Reset";
        $message = "Dear user,\n\nYour OTP for resetting your password is: $otp\n\nPlease do not share this with anyone.\n\nRegards,\nYour Website Team";
        $headers = "From: noreply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('OTP sent to your email successfully.');</script>";
            header("refresh:1;url=reset-password.php");
            exit();
        } else {
            $error = "Failed to send OTP. Please try again.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Forgot Password</title></head>
<body>
    <h2>Forgot Password</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your Email" required><br><br>
        <button type="submit">Send OTP</button>
    </form>
</body>
</html>
