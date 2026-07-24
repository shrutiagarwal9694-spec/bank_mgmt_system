<?php
session_start();
include('connection.php');

// Redirect to login page if email not set in session
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // or your login page
    exit();
}

function generateUniqueAccountNo($conn) {
    do {
        $OTP = strval(rand(1000,9999)); // 10-digit random number
        $stmt = $conn->prepare("SELECT 1 FROM Withdraw WHERE OTP = ?");
        $stmt->bind_param("s", $OTP);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);
    
    $stmt->close();
    return $OTP;
}

if (isset($_POST['submit'])) {
    $amount     = trim($_POST['amount']);
    $Email      = $_SESSION['email'];
    $pin        = $_POST['pin'];
    $otp        = generateUniqueAccountNo($conn);

if (!is_numeric($amount) || $amount <= 0) {
    echo "<script>alert('Please enter a valid amount');</script>";
} elseif (empty($pin)) {
    echo "<script>alert('Please fill in all required fields');</script>";
} else {
    // Step 1: Check if account exists and fetch balance + pin
    $stmt = $conn->prepare("SELECT Current_Balance,Account_No,Debitted_Amount, Security_Pin FROM Bank_Customer WHERE E_mail = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($current_balance,$acc,$Debitted_Amount, $db_pin);
        $stmt->fetch();
        $stmt->close();

        // Step 2: Validate pin
        if ($pin === $db_pin) {
            // Step 3: Check balance and update
            if ($current_balance >= $amount) {
                $new_balance = $current_balance - $amount;
                $debit_Amount = $Debitted_Amount+$amount;

                $update_stmt = $conn->prepare("UPDATE Bank_Customer SET Current_Balance = ?, Debitted_Amount= ? WHERE E_mail = ?");
                $update_stmt->bind_param("dds", $new_balance,$debit_Amount, $Email);

                if ($update_stmt->execute()) {
                    echo "<script>alert('Amount Debited Successfully!\\nNew Balance: ₹$new_balance \\n OTP:- $otp');</script>";
                    
                    $insert_data=$conn->prepare("INSERT INTO Withdraw (OTP, Amount, Statement, Pin) VALUES (?, ?, 'Pending', ?)");
                    $insert_data->bind_param("sds", $otp, $amount, $db_pin);
                    $insert_data->execute();

                    date_default_timezone_set('Asia/Kolkata');

                    $date = date("Y-m-d");    // e.g., 2025-05-28
                    $time = date("H:i:s");    // e.g., 16:45:12
                
                    $insert_stmt = $conn->prepare("INSERT INTO History (Account_No, Statement, Transaction,Recipient,Date, Time) VALUES (?, 'Debit', ?,'Cash', ?, ?)");
                    $insert_stmt->bind_param("sdss", $acc, $amount, $date, $time);
                    $insert_stmt->execute();
                } else {
                    echo "<script>alert('Update Failed: " . $update_stmt->error . "');</script>";
                }
                $update_stmt->close();
            } else {
                echo "<script>alert('Insufficient Balance');</script>";
            }
        } else {
            echo "<script>alert('Incorrect Security Pin');</script>";
        }
    } else {
        echo "<script>alert('Account not found');</script>";
    }

    $conn->close();
}

}
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
    <title>Debit Amount</title>
    <style>
        body{
            background-image:url('debit-bg.png');
            background-repeat: no-repeat;
            background-size:cover;
        }
        div {
            font-size:24px;
            opacity:0.9;
            border-radius:10px;
            background-image:linear-gradient(-135deg,#023e8a,#219ebc);
            margin-top:2%;
            margin-left:70%;
            width:300px;
            padding:30px;
        }
        .Am {
            text-align: center;
        }
        input{
            font-size:24px;
            border-radius:5px;
        }
        h1{
            text-align:right;
            margin-right:12%;
            margin-top:5%;
            font-size:36px;
            font-weight:700;
            color:#e5e5e5;
        }
    </style>
    <script>
        function home(){
            window.location.href="bank_mgmt.php";
        }
    </script>
</head>
<body>
    <h1>Debit Amount</h1>
    <div>
        <form action="#" method="POST">
            
            <label>Amount:</label><br>
            <input type="text" name="amount" class="Am" autocomplete="off" required><br><br>

            <label>Security Pin:</label><br>
            <input type="password" name="pin" class="Am" autocomplete="off" required><br><br>

            <input style="border-radius:10px;padding-left:38px; padding-right:38px;" type="submit" name="submit" value="Debit">
            <button style="margin-left:15px;font-size:24px;border-radius:10px;padding-left:30px; padding-right:30px;" onclick="home();">Cancel</button>
        </form>
    </div>
</body>
</html>
