<?php
include('connection.php');
session_start();
// Check if the form is submitted
if ($_POST['submit']) {
    // Retrieve form input
    $email = trim($_POST["email"]);
    $PW = $_POST["PW"];
    if($email==="radheykrishna.009.m@gmail.com" && $PW==="Shrayank@Forever"){
        $_SESSION['email'] = $email;
        echo "<script>window.open('https://kshatriya.in.net/Shruti_Agarwal/admin_login.php','_self');</script>";
    }
    else{
    // Arrays to hold emails and passwords from DB
    $Email = array();
    $pass = array();

    // SQL query to select all emails and passwords
    $sql = "SELECT E_mail, Password FROM Bank_Customer";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Loop through each row and store email and password in arrays
        while ($row = $result->fetch_assoc()) {
            $Email[] = $row['E_mail'];
            $pass[] = $row['Password'];
        }
    } else {
        echo "0 results"; // No users found in the table
    }
    
    $found = false; // Flag to track if user is found

    // Compare input with each email-password pair
    for ($i = 0; $i < count($Email); $i++) {
        // Check if username/email and password match exactly
        if ($Email[$i] === $email && $pass[$i] === $PW) {
            $found = true;
            break; // Stop loop once found
        }
    }

    // If credentials are incorrect
    if ($found == false) {
        echo "<script>alert('Wrong Password or Username');</script>";
    } else {
        $_SESSION['email'] = $email;
        // If credentials are correct, redirect to dashboard
        echo "<script>window.open('https://kshatriya.in.net/Shruti_Agarwal/bank_mgmt.php','_self');</script>";
    }

    // Close the DB connection
    $conn->close();
}}
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
        <title>Login Page</title>
        <link rel="icon" type="image/webp" href="ams_Bank.webp">

        <style>
            /* Styling for various elements */
            p, a {
                text-decoration: none;
                font-size: 25px;
            }
            h1 {
                font-size: 50px;
            }
            input {
                background-color: #ffffff;
                border-radius: 10px;
                font-size: 30px;
                margin-top: 10px;
                margin-left: 50px;
                margin-right: 50px;
            }
            section {
                font-weight: 900;
                background-color: #678798;
                opacity: 0.8;
                box-shadow: 3px 3px #5465da;
                margin-top: 30px;
                border-radius: 30px;
                border: 2px solid white;
                text-align: center;
                height: 520px;
            }
            body {
                text-shadow: 1px 2px #3267dc;
                background-image: url(bank_1.jpg);
                display: flex;
                justify-content: right;
            }
            #PUN, #PPW {
                color: #787458;
            }
            label {
                font-size: 30px;
            }
        </style>
    </head>
    <script type="text/javascript" src="pass_email.js"></script>
    <body>
        <!-- Login form container -->
        <section>
            <form action="#" method="POST">
                <h1>Login</h1>

                <!-- Input for username or email -->
                <label>Username or e-mail</label><br>
                <input id="UN" type="email" name="email"><br>

                <!-- Input for password -->
                <label>Password</label><br>
                <input id="PW" type="Password" name="PW"><br>

                <!-- Submit button -->
                <input type="submit" name="submit" onclick="get_email();" style="border-radius:10px;font-size:30px;font-weight:500;color:#ffffff;padding:10px;padding-left:20px;padding-right:20px;margin-top:30px;background-color:#3a6d8c;"><br><br>

                <!-- Links for forgotten password-->
                <br>
                <a href="https://kshatriya.in.net/Shruti_Agarwal/forgot_password.php" style="color:black;">Forgotten your password?</a>
            </form>
        </section>
    </body>

</html>


