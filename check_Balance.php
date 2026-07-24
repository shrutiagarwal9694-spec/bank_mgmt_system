<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('connection.php');

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$show_balance = false;
$error = '';

// Fetch user data from the database
$query = "SELECT Name, Account_No, Current_Balance, Security_Pin FROM Bank_Customer WHERE E_mail = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>alert('Account not found.'); window.location.href='login.php';</script>";
    exit();
}
$stmt->close();

// Handle PIN form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pin'])) {
    $entered_pin = trim($_POST['pin']);
    $stored_pin = trim((string)$user['Security_Pin']);

    if ($entered_pin === $stored_pin) {
        $show_balance = true;
    } else {
        $error = "Incorrect PIN. Please try again.";
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
    <title>Check Balance</title>
    <meta charset="UTF-8">
    <style>
        body {
            background-image: url('bg-for-check-bal.png');
            background-repeat:no-repeat;
            background-size:cover;
            font-family: Arial, sans-serif;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .balance-box {
            background-color: rgba(0, 0, 0, 0.4);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px #000;
            width: 300px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .info {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .amount {
            font-size: 26px;
            font-weight: bold;
            color: #00ffcc;
        }
        input[type="password"],
        input[type="submit"] {
            text-align:center;
            padding: 10px;
            margin-top: 10px;
            width: 90%;
            border: none;
            border-radius: 5px;
        }
        .error {
            color: #ff6666;
            margin-top: 10px;
        }
        .logout {
            margin-top: 15px;
            display: block;
            color: #ffffff;
            text-decoration: underline;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="balance-box">
        <h1>Account Balance</h1>
        <div class="info">Name: <?php echo htmlspecialchars($user['Name']); ?></div>
        <div class="info">Account No: <?php echo htmlspecialchars($user['Account_No']); ?></div>

        <?php if ($show_balance): ?>
            <div class="info amount">₹<?php echo number_format($user['Current_Balance'], 2); ?></div>
        <?php else: ?>
            <form method="post" novalidate>
                <label for="pin">Enter PIN:</label><br>
                <input type="password" name="pin" id="pin" required><br>
                <input type="submit" value="Submit">
                <?php if ($error): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
            </form>
        <?php endif; ?>

        <a class="logout" href="bank_mgmt.php">Logout</a>
    </div>
</body>
</html>
