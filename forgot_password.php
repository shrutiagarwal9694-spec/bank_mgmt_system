<?php
include('connection.php');
session_start();

$question = "";
$showQuestion = false;

if (isset($_POST['submit'])) {
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("SELECT Sec_Q, Answer FROM Bank_Customer WHERE E_mail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Save in session
        $_SESSION['email'] = $email;
        $_SESSION['question'] = $row['Sec_Q'];
        $_SESSION['answer'] = $row['Answer'];

        $question = "Que. " . $row['Sec_Q'];
        $showQuestion = true;
    } else {
        echo "<script>alert('Email not found');</script>";
    }
}

if (isset($_POST['check'])) {
    $userAnswer = trim($_POST["ans"]);

    $correctAnswer = $_SESSION['answer'] ?? '';
    $question = "Que. " . ($_SESSION['question'] ?? '');
    $showQuestion = true;

    if (strcasecmp($correctAnswer, $userAnswer) !== 0) {
        echo "<script>alert('Wrong Answer');</script>";
    } else {
        // Correct answer; redirect to change password page
        header("Location: change_Password.php");
        exit();
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
    <title>Forgot Password</title>
    <link rel="icon" type="image/webp" href="ams_Bank.webp">
    <style>
        section {
            text-align: center;
        }
        .form-box {
            opacity: 0.95;
            background-image: linear-gradient(#123245, #567678);
            width: 500px;
            border: 2px solid #000000;
            margin-top: 5%;
            font-size: 50px;
            border-radius: 10px;
            padding: 20px;
        }
        input {
            border-radius: 5px;
            font-size: 25px;
            margin-top: 5%;
            padding: 5px;
        }
        label {
            color: #dddddd;
            font-weight: 700;
        }
        h1 {
            font-size: 70px;
            color: #ffffff;
        }
        body {
            background-image: url('for-pass.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <form action="" method="POST">
        <section>
            <h1>Forgot Password</h1>

            <!-- Step 1: Email Input -->
            <?php if (!$showQuestion): ?>
                <div class="form-box">
                    <label for="email">E-mail</label><br>
                    <input id="email" type="email" name="email" placeholder="E-mail" required><br>
                    <input type="submit" name="submit" value="Get Question" style="background-color:#eeeeee;margin-top:30px;padding:10px;">
                </div>
            <?php endif; ?>

            <!-- Step 2: Security Question -->
            <?php if ($showQuestion): ?>
                <div class="form-box">
                    <label id="que"><?php echo htmlspecialchars($question); ?></label><br>
                    <input type="text" name="ans" placeholder="Answer" autocomplete="off" required><br>
                    <input type="submit" name="check" value="Submit Answer" style="background-color:#eeeeee;margin-top:30px;padding:10px;">
                </div>
            <?php endif; ?>
        </section>
    </form>
</body>
</html>
