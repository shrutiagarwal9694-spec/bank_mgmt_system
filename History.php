<?php
session_start();
include('connection.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Determine if admin is viewing another customer's history
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    // Admin is viewing a customer's history
    $email = $_POST['email'];
} else {
    // Regular user
    $email = $_SESSION['email'];
}

// Prepare statement to get the Account_No
$acc = null;
$stmt = $conn->prepare("SELECT Account_No FROM Bank_Customer WHERE E_mail = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($acc);
$stmt->fetch();
$stmt->close();

// Fetch history if account exists
$historyData = [];
if ($acc !== null) {
    $stmt2 = $conn->prepare("SELECT * FROM History WHERE Account_No = ?");
    $stmt2->bind_param("s", $acc);
    $stmt2->execute();
    $result = $stmt2->get_result();

    while ($row = $result->fetch_assoc()) {
        $historyData[] = $row;
    }
    $stmt2->close();
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
    <title>Passbook</title>
    <style>
        body{
            background-image: linear-gradient(45deg, #003049, #669bbc);
            
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size:24px;
            background-color:#a2d2ff;
            opacity:0.6;
        }
        h1{
            font-size:48px;
            margin: 0;
            flex: 1;
            text-align: center;
        }
        th, td {
            border: 2px solid #333;
            padding: 8px;
            text-align: center;
        }
        /* Flex container for header + button */
        .header-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .cancel-button {
            font-size: 18px;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 6px;
            border: none;
            background-color: #f44336; /* red color */
            color: white;
            transition: background-color 0.3s ease;
        }
        .cancel-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div>
        <div class="header-container">
            <h1>Passbook</h1>
            <button class="cancel-button" onclick="window.location.href='bank_mgmt.php'">Back</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Account_No</th>
                    <th>Statement</th>
                    <th>Transaction</th>
                    <th>Recipient</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($historyData)): ?>
                    <?php foreach ($historyData as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Account_No']) ?></td>
                            <td><?= htmlspecialchars($row['Statement']) ?></td>
                            <td><?= htmlspecialchars($row['Transaction']) ?></td>
                            <td><?= htmlspecialchars($row['Recipient']) ?></td>
                            <td><?= htmlspecialchars($row['Date']) ?></td>
                            <td><?= htmlspecialchars($row['Time']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
