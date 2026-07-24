<?php
session_start();
include('connection.php');

// Redirect to login page if email not set in session
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // or your login page
    exit();
}

if (isset($_POST['submit'])) {
    $amount     = trim($_POST['amount']);
$account_no = $_POST['accno'];
$pin        = $_POST['pin'];
$email      = $_SESSION['email'];



if (!is_numeric($amount) || $amount <= 0) {
    echo "<script>alert('Please enter a valid amount');</script>";
} elseif (empty($account_no) || empty($pin)) {
    echo "<script>alert('Please fill in all required fields');</script>";
} else {
    // Step 1: Check if account exists and fetch balance + pin
    $stmt = $conn->prepare("SELECT Name,Current_Balance,Transfered_Amount, Security_Pin, Account_No FROM Bank_Customer WHERE E_mail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $query = $conn->prepare("SELECT Name,Current_Balance,Creditted_Amount FROM Bank_Customer WHERE Account_No = ?");
    $query->bind_param("s", $account_no);
    $query->execute();
    $query->store_result();

    if ($stmt->num_rows === 1 && $query->num_rows===1) {
        $stmt->bind_result($n1,$current_balance,$Transfered_Amount, $db_pin,$acc);
        $stmt->fetch();
        $stmt->close();
        $query->bind_result($n2,$CB,$CA);
        $query->fetch();
        $query->close();

        // Step 2: Validate pin
        if ($pin === $db_pin) {
            // Step 3: Check balance and update
            if ($current_balance >= $amount) {
                $new_balance = $current_balance - $amount;
                $transfer_Amount = $Transfered_Amount+$amount;
                $CRA=$CA+$amount;
                $CRB=$CB+$amount;


                $update_stmt = $conn->prepare("UPDATE Bank_Customer SET Current_Balance = ?, Transfered_Amount= ? WHERE E_mail = ?");
                $update_stmt->bind_param("dds", $new_balance,$transfer_Amount, $email);

                $update_query = $conn->prepare("UPDATE Bank_Customer SET Current_Balance = ?, Creditted_Amount= ? WHERE Account_No = ?");
                $update_query->bind_param("dds", $CRB,$CRA, $account_no);

                date_default_timezone_set('Asia/Kolkata'); // Set to Delhi time zone

                $date = date("Y-m-d");    // e.g., 2025-05-28
                $time = date("H:i:s");    // e.g., 16:45:12
                
                $insert_stmt = $conn->prepare("INSERT INTO History (Account_No, Statement, Transaction,Recipient,Date, Time) VALUES (?, 'Transfer-Debit', ?,?, ?, ?)");
                $insert_stmt->bind_param("sdsss", $acc, $amount,$n2, $date, $time);

                $insert_query = $conn->prepare("INSERT INTO History (Account_No, Statement, Transaction,Recipient, Date, Time) VALUES (?, 'Transfer-Credit', ?,?, ?, ?)");
                $insert_query->bind_param("sdsss", $account_no, $amount,$n1, $date, $time);

                if ($update_stmt->execute()  && $update_query->execute() && $insert_stmt->execute() && $insert_query->execute()) {
                    echo "<script>alert('Amount transfered Successfully!\\nNew Balance: ₹$new_balance');</script>";
                } else {
                    echo "<script>alert('Update Failed: " . $update_stmt->error . "');</script>";
                }
                $update_stmt->close();
                $update_query->close();
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
<html>
    <head>
        <link rel="icon" type="image/webp" href="ams_Bank.webp">
        <title>Transfer</title>
        <style>
            body{
                background-image:url('transfer-img.jpg');
                background-repeat: no-repeat;
                background-size:1500px, 1500px;
                display:flex;
                justify-content:center;
                align-items:center;
            }
            h2{
                font-size:36px;
                margin-left:100px;
            }
            label{
                font-size:24px;
                margin-right:30px;
                font-weight:600;
            }
            input{
                font-size:24px;
                border-radius:10px;
            }
            button{
                padding-left:40px;
                padding-right:40px;
                font-size:24px;
                margin-left:70px;
                border-radius:10px;
            }
            div{
                border-radius:20px;
                padding-left:30px;
                background-image:linear-gradient(45deg, #8ecae6,#415a77);//url('');
                width:500px;
                height:320px;
                padding-top:1px;
            }
        </style>
    </head>
    <body>
        <div>
        <form action="#" method="POST">
            <h2> Account Transfer</h2>
            <table>
                <tr><td><label>Account_No</td><td><input type="text" name="accno" autocomplete="off" required></td></tr>
                <tr><td><label>Amount</td><td><input type="text" name="amount" autocomplete="off" required></td></tr>
                <tr><td><label>PIN</td><td><input type="Password" name="pin" autocomplete="off" required></td></tr>
            </table><br>
            <tr>
  <td><input style="margin-left:30px;padding-left:33px;padding-right:33px;" type="submit" name="submit" value="Transfer"></td>
  <td><button type="button" onclick="window.location.href='bank_mgmt.php'">Cancel</button></td>
</tr>

        </form>
        </div>
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

    </body>
</html>