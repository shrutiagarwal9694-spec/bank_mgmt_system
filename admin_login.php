<?php
    session_start();
    include('connection.php');

    // Corrected session check
    if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'radheykrishna.009.m@gmail.com') {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Area</title>
    <style>
        body {
            background-image: url('admin_page.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            color: #778da9;
        }

        /* Fixed header with cancel button and centered h1 */
        .header-container {
            display: flex;
            align-items: center;
            justify-content: center; /* centers h1 horizontally */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: rgba(0, 48, 73, 0.85);
            box-shadow: 0 2px 8px rgba(0,0,0,0.5);
            padding: 0 20px;
            z-index: 1000;
        }

        .cancel-button {
            position: absolute;
            left: 20px;
            background-color: #a2d2ff;
            color: #003049;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            transition: background-color 0.3s ease;
        }
        .cancel-button:hover {
            background-color: #7fb7ea;
        }

        h1 {
            font-size: 32px;
            margin: 0;
            user-select: none;
        }

        /* Center the buttons vertically and horizontally */
        main.content {
            height: 100vh;           /* full viewport height */
            display: flex;
            justify-content: center; /* horizontal center */
            align-items: center;     /* vertical center */
            text-align: center;
            color: #a0bcd1;
            padding-top: 60px; /* to prevent overlap if header is fixed */
            box-sizing: border-box;
        }

        div.buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
       
        div.buttons button {
            font-size: 32px;
            border-radius: 10px;
            background-color: #003049;
            color: #a2d2ff;
            border: none;
            padding: 12px 24px;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0,0,0,0.4);
            transition: background-color 0.3s ease;
            
            min-width: 320px;
        }
        div.buttons button:hover {
            background-color: #005f8c;
        }
    </style>
    <script>
        function goToLogin() {
            window.location.href = "login.php";
        }
        function SU(){
            window.open("sign_up.php","_self");
        }
        function CA(){
            window.open("credit_Amount.php","_self");
        }
        function WD(){
            window.open("withdraw.php","_self");
        }
        function CD(){
            window.open("admin_page.php","_self");
        }
    </script>
</head>
<body>

    <header class="header-container">
        <button class="cancel-button" onclick="goToLogin()">Exit</button>
        <h1>AMS Bank</h1>
    </header>

    <main class="content">
        <div class="buttons">
            <button onclick="SU();">Sign_Up</button>
            <button onclick="CA();">Credit Amount</button>
            <button onclick="WD();">Withdrawal</button>
            <button onclick="CD();">Customer Details</button>
        </div>
    </main>
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
