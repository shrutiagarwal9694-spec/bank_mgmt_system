<?php
session_start();
include('connection.php');

// Redirect if user not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$query = "SELECT Name, DOB, Father_Name, Account_No, E_mail, Contact, Address, Photo FROM Bank_Customer WHERE E_mail='$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Default values
    $user = [
        'Name' => 'N/A',
        'DOB' => 'N/A',
        'Father_Name' => 'N/A',
        'Account_No' => 'N/A',
        'E_mail' => 'N/A',
        'Contact' => 'N/A',
        'Address' => 'N/A',
        'Photo' => 'ams_Bank.webp',
    ];
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

    <title>AMS Bank</title>
    <link rel="icon" href="ams_Bank.webp" type="image/webp">
    <style>
        /* Reset default spacing */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: Arial, sans-serif;
    background-image: url('ams.jpg');
    background-size:cover;
}

/* Header styling */
header {
    background-image: radial-gradient(#415a77, #1b263b, #1d3557);
    color: #ffffff;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 0 20px;
}

header h1 {
    font-size: 28px;
    flex: 1;
    text-align: center;
}

.logout-link {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: white;
    text-decoration: none;
    font-weight: bold;
    background-color: #003049;
    padding: 6px 12px;
    border-radius: 6px;
    transition: background-color 0.3s;
}

.logout-link:hover {
    background-color: #1d3557;
}

/* Button bar styling */
.button-bar {
    margin: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: center;
}

.button-bar button {
    background-color: #003049;
    color: white;
    font-size: 16px;
    border-radius: 10px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    border: none;
}

.icon {
    height: 20px;
    width: 20px;
    border-radius: 50%;
}

/* Flip box */
.flip_box {
    margin-top: 30px;
    margin-left: 170px;
    width: 400px;
    height: 480px;
    perspective: 1000px;
}

.inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 1s;
    transform-style: preserve-3d;
}

.flip_box:hover .inner {
    transform: rotateY(-180deg);
}

.front,
.back {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 10px;
    backface-visibility: hidden;
}

.front {
    background-image: linear-gradient(#01497c, #415a77);
    color: black;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.back {
    background-image: linear-gradient(#01497c, #415a77);
    color: white;
    transform: rotateY(-180deg);
    padding: 10px;
}

/* Profile images */
#profile {
    border-radius: 50%;
    border: 2px solid #01497f;
    height: 200px;
    width: 200px;
}

#photo {
    width: 130px;
    height: 130px;
    margin-top: 10px;
    border: 1px solid #000000;
    border-radius: 50%;
}

/* Info table */
#PI {
    border: 1px solid #000000;
    color: #ffffff;
    font-weight: 500;
    font-size: 16px;
    width: 100%;
    table-layout: fixed;
    border-spacing: 7px;
    margin-top: 15px;
}

#PI td {
    padding: 5px;
}

#PI td.f,
#PI td.s {
    border: 1px solid #000000;
    text-align: center;
}

/* Responsive styles */
@media (max-width: 1024px) {
    .flip_box {
        margin-left: 100px;
    }
}

@media (max-width: 768px) {
    header {
        flex-direction: column;
        height: auto;
        padding: 10px;
        gap: 5px;
    }

    header h1 {
        font-size: 24px;
        text-align: center;
    }

    .logout-link {
        position: static;
        transform: none;
        align-self: flex-end;
        font-size: 14px;
        padding: 6px 10px;
    }

    .button-bar {
        flex-direction: column;
        align-items: center;
    }

    .flip_box {
        width: 90%;
        margin: 30px auto;
        height: auto;
    }

    #profile {
        width: 150px;
        height: 150px;
    }

    #photo {
        width: 100px;
        height: 100px;
    }

    #PI {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    header h1 {
        font-size: 20px;
    }

    .logout-link {
        font-size: 12px;
        padding: 5px 8px;
    }

    .flip_box {
        width: 95%;
        margin-left: auto;
        margin-right: auto;
    }

    .button-bar button {
        font-size: 14px;
        padding: 8px 12px;
    }
}

    </style>

    <script>
        function check_balance() {
            window.location.href = "check_Balance.php";
        }

        function debit_amount() {
            window.location.href = "debit_Amount.php";
        }

        function Transfer() {
            window.location.href = "Transfer.php";
        }

        function history() {
            window.location.href = "History.php";
        }

        function change_pass() {
            window.location.href = "change_Password.php";
        }
    </script>
</head>
<body>

    <header>
        <div class="header-container">
            <h1>AMS BANK</h1>
            <a href="logout.php" class="logout-link">Log Out</a>
        </div>
    </header>

    <div class="button-bar">
        <button onclick="check_balance();"><img class="icon" src="check-bal.png">Check Balance</button>
        <button onclick="debit_amount();"><img class="icon" src="debit_amount.webp">Debit Amount</button>
        <button onclick="Transfer();"><img class="icon" src="check-bal.png">Transfer</button>
        <button onclick="history();"><img class="icon" src="history_icon.jpg">Passbook</button>
        <button onclick="change_pass();"><img class="icon" src="change_p_icon.jfif">Change Password</button>
    </div>

    <div class="flip_box">
        <div class="inner">
            <div class="front">
                <div>
                    <h2>Profile Card</h2>
                    <img id="profile" src="ams_Bank.webp" alt="Profile Image">
                    <h1>AMS BANK</h1>
                </div>
            </div>
            <div class="back">
                <img id="photo" src="<?php echo htmlspecialchars($user['Photo']); ?>" alt="Profile Photo">
                <table id="PI">
                    <tr><td class="f">Name:</td><td class="s"><?php echo htmlspecialchars($user['Name']); ?></td></tr>
                    <tr><td class="f">D.O.B:</td><td class="s"><?php echo htmlspecialchars($user['DOB']); ?></td></tr>
                    <tr><td class="f">Father's Name:</td><td class="s"><?php echo htmlspecialchars($user['Father_Name']); ?></td></tr>
                    <tr><td class="f">Account No.:</td><td class="s"><?php echo htmlspecialchars($user['Account_No']); ?></td></tr>
                    <tr><td class="f">E-Mail:</td><td class="s"><?php echo htmlspecialchars($user['E_mail']); ?></td></tr>
                    <tr><td class="f">Contact No.:</td><td class="s"><?php echo htmlspecialchars($user['Contact']); ?></td></tr>
                    <tr><td class="f">Address:</td><td class="s"><?php echo htmlspecialchars($user['Address']); ?></td></tr>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
