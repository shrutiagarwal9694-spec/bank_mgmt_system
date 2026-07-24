<?php
include('connection.php');
session_start();
// Check if the form is submitted
if ($_POST['submit']) {
    // Retrieve form input
    $OTP = trim($_POST["otp"]);
    $PIN = $_POST["pin"];
    $otp = array();
    $pin = array();
    $stmt= array();

    // SQL query to select all otps and passwords
    $sql = "SELECT OTP, Pin, Statement FROM Withdraw";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Loop through each row and store OTP and password in arrays
        while ($row = $result->fetch_assoc()) {
            $otp[] = $row['OTP'];
            $pass[] = $row['Pin'];
            $stmt[] = $row['Statement'];
        }
    } else {
        echo "0 results"; // No users found in the table
    }
    
    $found = false; // Flag to track if user is found

    // Compare input with each OTP-password pair
    for ($i = 0; $i < count($otp); $i++) {
        // Check if username/OTP and password match exactly
        if ($otp[$i] === $OTP && $pass[$i] === $PIN && $stmt[$i] === "Pending") {
            $found = true;
            break; // Stop loop once found
        }
    }

    // If credentials are incorrect
    if ($found == false) {
        echo "<script>alert('Wrong OTP or PIN');</script>";
    } else {
        $update_stmt=$conn->prepare("UPDATE Withdraw SET Statement='Successful' WHERE OTP=? && Pin=?");
        $update_stmt->bind_param("ss", $OTP, $PIN);
        $update_stmt->execute();
        // If credentials are correct, redirect to dashboard
        echo "<script>alert('Withdrawal Completed Sucessfully ');</script>";
    }

    // Close the DB connection
    $conn->close();
}
?>
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
        <title>Withdraw</title>
        <style>
            body{
                font-size:30px;
                margin-top:0;
                text-align:center;
                display:flex;
                flex-direction:column;
                background-image:url('withdraw-bg.png');
                background-repeat: no-repeat;
                background-size:cover;
                color: #ddd;
            }
            div label {
                color: #ddd;
                text-shadow: 1px 1px 2px #000;
                
                }
            div{
                
                padding: 5% 5% 3% 5%;
                height:auto;
                width:350px;
                border:2px solid black;
                text-align:center;
                margin-left: 31.5% ;
                border-radius:20px;
                opacity:0.8;
                font-weight:800;
                background-image:linear-gradient(45deg,#003f88,#0077b6);     
            }
            form{
                display:flex;
                flex-direction:column;
            }
            input{
                text-align:center;
                font-size:25px;
                border-radius:10px;
                margin-bottom:15px;
            }
            #btn{
                margin-top:10px;
                padding:5px 0 5px 0;
                background-color:#d90429;
            }
            #cbtn{
                font-size:25px;
                border-radius:10px;
                margin-top:-2px;
                padding:5px 0 5px 0;
                background-color:#57cc99;
            }
        </style>
        <script>
            function cancel(){
                window.open('admin_login.php', '_self');
            }
        </script>
    </head>
    <body>
        <h1 style="margin-top:5%;">Withdraw</h1>
        <div>
        <form method="POST" action="#"> 
            <label>Enter OTP:</label>
            <input type="password" name="otp">
            <label style="">Enter Pin:</label>
            <input type="password" name="pin">
            <input type="submit" name="submit" value="Withdraw" id="btn">
            <button type="button" id="cbtn" onclick="cancel();">Cancel</button>

        </form>
        </div>
    </body>
</html>