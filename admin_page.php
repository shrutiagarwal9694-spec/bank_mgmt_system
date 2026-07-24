<?php
session_start();
include('connection.php');

if (!($_SESSION['email'] == 'radheykrishna.009.m@gmail.com')) {
    header("Location: admin_login.php");
    exit();
}

$customerData = [];

$stmt2 = $conn->prepare("SELECT * FROM Bank_Customer");
$stmt2->execute();
$result = $stmt2->get_result();

while ($row = $result->fetch_assoc()) {
    $customerData[] = $row;
}
$stmt2->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head> 
    <link rel="icon" type="image/webp" href="ams_Bank.webp">
    <title>Account Holders</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: radial-gradient(circle at center, #5c8fbf 0%, #173b5c 65%, #102d44 100%);
            background-attachment: fixed;
            background-size: cover;
            min-height: 100vh;
            overflow-x: hidden;
            color: #f0f8ff;
        }

        .header-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: linear-gradient(90deg, #102d44, #173b5c, #102d44);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            z-index: 1000;
        }

        .cancel-button {
            position: absolute;
            left: 20px;
            background: linear-gradient(45deg, #226c9b, #17486e);
            color: #e0f1f9;
            border: none;
            padding: 8px 16px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
            transition: background 0.3s;
        }

        .cancel-button:hover {
            background: linear-gradient(45deg, #17486e, #0c2a40);
        }

        .header-container h1 {
            margin: 0;
            font-size: 30px;
            color: #f1faff;
            pointer-events: none;
            text-shadow: 1px 1px 5px #001d2d;
        }

        .table-container {
            margin-top: 80px;
            padding: 20px;
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
            background: #173b5ce6;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            color: #f0f8ff;
            font-size: 18px;
        }

        th, td {
            border: 1px solid #0f2f4f;
            padding: 12px 10px;
            text-align: center;
        }

        th {
            background: #0f2f4f;
            color: #e0f1f9;
        }

        tbody tr:nth-child(even) {
            background-color: #133c5c;
        }

        tbody tr:hover {
            background-color: #1c4e70;
        }

        input[type="submit"] {
            background: linear-gradient(135deg, #226c9b, #17486e);
            color: #f1faff;
            border: none;
            padding: 6px 14px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.6);
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #17486e, #0c2a40);
        }

        .bottom-bar {
            background: linear-gradient(90deg, #13315c, #1b4965, #264653);
            color: #e0f1f9;
            text-align: center;
            padding: 12px 20px;
            font-size: 16px;
            width: 100%;
            margin-top: 30px;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.6);
        }

        @media (max-width: 768px) {
            .header-container h1 {
                font-size: 22px;
            }

            .cancel-button {
                font-size: 14px;
                padding: 6px 12px;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
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

</head>
<body>

    <div class="header-container">
        <button class="cancel-button" onclick="window.location.href='admin_login.php';">Cancel</button>
        <h1>Account Holders</h1>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Father_Name</th>
                    <th>DOB</th>
                    <th>E_mail</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Account_No</th>
                    <th>Creditted_Amount</th>
                    <th>Debitted_Amount</th>
                    <th>Transfered_Amount</th>
                    <th>Current_Balance</th>
                    <th>History</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customerData)): ?>
                    <?php foreach ($customerData as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Father_Name']) ?></td>
                            <td><?= htmlspecialchars($row['DOB']) ?></td>
                            <td><?= htmlspecialchars($row['E_mail']) ?></td>
                            <td><?= htmlspecialchars($row['Contact']) ?></td>
                            <td><?= htmlspecialchars($row['Address']) ?></td>
                            <td><?= htmlspecialchars($row['Account_No']) ?></td>
                            <td><?= htmlspecialchars($row['Creditted_Amount']) ?></td>
                            <td><?= htmlspecialchars($row['Debitted_Amount']) ?></td>
                            <td><?= htmlspecialchars($row['Transfered_Amount']) ?></td>
                            <td><?= htmlspecialchars($row['Current_Balance']) ?></td>
                            <td>
                                <form action="History.php" method="POST">
                                    <input type="hidden" name="email" value="<?= htmlspecialchars($row['E_mail']) ?>">
                                    <input type="submit" value="View">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="12">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="bottom-bar">
        © 2025 AMS Bank | Admin Panel
    </div>


</body>
</html>
