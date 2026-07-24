<?php
session_start();
include('connection.php');

// Redirect to login page if email not set in session
if (!$_SESSION['email']=='radheykrishna.009.m@gmail.com') {
    header("Location: login.php"); // or your login page
    exit();
}

if (isset($_POST['submit'])) {
    $amount     = trim($_POST['amount']);
    $account_no = trim($_POST['account_no']);

    if (!is_numeric($amount) || $amount <= 0) {
        echo "<script>alert('Please enter a valid amount');</script>";
    } elseif (empty($account_no)) {
        echo "<script>alert('Please enter Account Number');</script>";
    } else {
        // Step 1: Check if account exists
        $stmt = $conn->prepare("SELECT Current_Balance, Creditted_Amount FROM Bank_Customer WHERE Account_No = ?");
        $stmt->bind_param("s", $account_no);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($current_balance, $Credit_amount);
            $stmt->fetch();
            $stmt->close();

            // Step 2: Update balance
            $new_balance = $current_balance + $amount;
            $creditted= $Credit_amount+$amount;

            $update_stmt = $conn->prepare("UPDATE Bank_Customer SET Current_Balance = ?, Creditted_Amount= ? WHERE Account_No = ?");
            $update_stmt->bind_param("dds", $new_balance, $creditted, $account_no);

            if ($update_stmt->execute()) {
                echo "<script>alert('Amount Credited Successfully!\\nNew Balance: ₹$new_balance');</script>";

                    date_default_timezone_set('Asia/Kolkata');
                    
                    $date = date("Y-m-d");    // e.g., 2025-05-28
                    $time = date("H:i:s");    // e.g., 16:45:12
                
                    $insert_stmt = $conn->prepare("INSERT INTO History (Account_No, Statement, Transaction,Recipient,Date, Time) VALUES (?, 'Credit', ?,'Cash', ?, ?)");
                    $insert_stmt->bind_param("sdss", $account_no, $amount, $date, $time);
                    $insert_stmt->execute();
            } else {
                echo "<script>alert('Update Failed: " . $update_stmt->error . "');</script>";
            }

            $update_stmt->close();
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
    <title>Credit Amount</title>
    <style>
        body{
            background-image:url('credit_background.jpg');
            background-repeat: no-repeat;
            background-size:cover;
        }
        #cr{
            font-size:24px;
            border-radius:10px;
            background-image:linear-gradient(-135deg,#001d3d, #0077b6);
            margin-top:2%;
            margin-left:69%;
            width:300px;
            padding:30px;
        }
        .Am {
            text-align: center;
            font-size:24px;
            border-radius:5px;
        }
        .cr{
            font-size:24px;
            border-radius:5px;
            background-color:#023047;
            
        }
        button{
            font-size:24px;
            border-radius:5px;
            background-color:#d62828;
        }
            

        h1{
            text-align:right;
            margin-right:12%;
            color:#ffffff;
            font-size:36px;
            margin-top:10%;
        }
        label{
            color:#ffffff;
        }
    </style>
    <script>
            function cancel(){
                window.open('admin_login.php', '_self');
            }
        </script>
</head>
<body>
    <h1>Credit Amount</h1>
    <div id="cr">
        <form action="#" method="POST">
            <label >Account Number:</label><br>
            <input type="text" name="account_no" autocomplete="off" class="Am" required><br><br>

            <label>Amount:</label><br>
            <input type="text" name="amount" class="Am" required><br><br>
            <div style="display:flex;flex-direction:column;">
            <input type="submit" name="submit" class=cr value="Credit"></br>
            <button type="button" id="cbtn" onclick="cancel();">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
