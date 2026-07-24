<?php
session_start();
include('connection.php');

// Redirect to login  page if email not set in session
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // or your login  page
    exit();
}

$message = "";

if (isset($_POST['submit_password'])) {
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if ($newPass !== $confirmPass) {
        $message = "Passwords do not match.";
    } elseif (strlen($newPass) < 6) {
        $message = "Password must be at least 6 characters long.";
    } else {
        
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("UPDATE Bank_Customer SET Password = ? WHERE E_mail = ?");
        $stmt->bind_param("ss", $newPass, $email);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // Clear session and redirect after success
            session_destroy();
            echo "<script>alert('Password changed successfully! Please login with your new password.'); window.location.href='login.php';</script>";
            exit();
        } else {
            $message = "Failed to update password. Please try again.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<script>
    // Disable right-click
    document.addEventListener('contextmenu', event => event.preventDefault());

    // Disable certain keypresses (F12, Ctrl+U, etc.)
    document.addEventListener('keydown', function (event) {
        if (
            event.key === "F12" ||
            (event.ctrlKey && event.key.toLowerCase() === "u") ||
            (event.ctrlKey && event.key.toLowerCase() === "s") ||
            (event.ctrlKey && event.key.toLowerCase() === "p") ||
            event.key === "Tab"
        ) {
            event.preventDefault();
        }
    });

    // Prevent mousewheel zoom
    window.addEventListener('wheel', function(e) {
        if (e.ctrlKey) e.preventDefault();
    }, { passive: false });
</script>
    <link rel="icon" type="image/webp" href="ams_Bank.webp">
    <title>Change Password</title>
    <link rel="icon" type="image/webp" href="ams_Bank.webp">
    <style>
        body {
            background-image: url('change_Pass.jpg');
            background-size: cover;
            display: flex;
            justify-content: center;
            padding-top: 50px;
            font-family: Arial, sans-serif;
        }
        .form-box {
            background: rgba(20, 20, 20, 0.8);
            padding: 30px;
            width: 400px;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            box-shadow: 0 0 10px black;
        }
        h1 {
            text-align: center;
            font-size: 48px;
            margin-bottom: 20px;
        }
        label {
            font-size: 18px;
            display: block;
            margin-top: 20px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 8px;
            border-radius: 5px;
            border: none;
        }
        input[type="submit"], button {
            margin-top: 25px;
            padding: 12px 25px;
            font-size: 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            width: 100%;
        }
        button {
            background-color: #f44336;
            color: white;
            width: 100%;
            margin-top: 10px;
        }
        .error {
            color: #ff5555;
            margin-top: 15px;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h1>Change Password</h1>

        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required autocomplete="new-password">

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required autocomplete="new-password">

            <input type="submit" name="submit_password" value="Change Password">
        </form>

        <button onclick="window.location.href='login.php';">Cancel</button>
    </div>
</body>
</html>
