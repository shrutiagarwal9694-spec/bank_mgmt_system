<?php
session_start();
include('connection.php');

// Redirect to login page if email not set in session
if (!$_SESSION['email']=='radheykrishna.009.m@gmail.com') {
    header("Location: login.php"); // or your login page
    exit();
}
function generateUniqueAccountNo($conn) {
    do {
        $accountNo = strval(rand(1000000000, 9999999999)); // 10-digit random number
        $stmt = $conn->prepare("SELECT 1 FROM Bank_Customer WHERE Account_No = ?");
        $stmt->bind_param("s", $accountNo);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);
    
    $stmt->close();
    return $accountNo;
}

if (isset($_POST['submit'])) {
    // Collect and sanitize inputs (as already done)
    $name     = trim($_POST['name']);
    $f_name   = trim($_POST['f_name']);
    $dob      = $_POST['DOB'];
    $email    = strtolower(trim($_POST['email']));
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $question = $_POST['Question'];
    $answer   = trim($_POST['answer']);
    $pw       = $_POST['PW'];
    $cpw      = $_POST['CPW'];
    $pin      = $_POST['PIN'];

    // Handle image
    $targetDir = "uploads/";
    $photoName = basename($_FILES["photo"]["name"]);
    $targetFilePath = $targetDir . uniqid() . "_" . $photoName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (empty($question)) {
        echo "<script>alert('Please select a security question');</script>";
    } elseif ($pw !== $cpw) {
        echo "<script>alert('Passwords do not match');</script>";
    } elseif (!in_array($fileType, $allowedTypes)) {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
    } elseif (!move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
        echo "<script>alert('Photo upload failed.');</script>";
    } else {
        // Generate account number
        $accountNo = generateUniqueAccountNo($conn);

        $stmt = $conn->prepare("INSERT INTO Bank_Customer (Name, Father_Name, DOB, E_mail, Security_Pin, Contact, Address, Sec_Q, Answer, Password, Account_No, Photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", $name, $f_name, $dob, $email, $pin, $phone, $address, $question, $answer, $pw, $accountNo, $targetFilePath);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!\\nYour Account Number is: $accountNo'); window.location.href='https://kshatriya.in.net/Shruti_Agarwal/login.php';</script>";
        } else {
            echo "<script>alert('Registration failed.');</script>";
        }

        $stmt->close();
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
    <title>Sign-Up Page</title>
    <style>
        .in{
            width:180px;
        }
        div{
            border-radius:20px;
            background-image:linear-gradient(135deg,#003566,#0077b6);
            color:#ffffff;
            margin-left:830px;
            margin-top:35px;
            padding-bottom:50px;
            padding-left:25px;
            opacity:0.8;
        }
        body{
            font-size:24px;
            background-image:url('sign-up-image.webp');
            background-repeat:no-repeat;
            background-size:cover;
            
        }
        .btn{
            margin-left:40px;
            font-size:24px;
            margin-top:25px;
            border-radius:10px;
            color:#ffffff;
            padding-left:15px;
            padding-right:15px;
        }
    </style>
    <script>
            function cancel(){
                window.open('admin_login.php', '_self');
            }
        </script>
</head>
<body>
    <div><form action="#" method="POST" enctype="multipart/form-data">

    <h1 style="color:#ffffff; margin-left:90px;">Sign-Up</h1>
    <table>
        <tr><td><label>Name:</label></td><td><input class="in" type="text" name="name" autocomplete="off" required></td></tr>
        <tr><td><label>Father's Name:</label></td><td><input class="in" type="text" name="f_name" autocomplete="off" required></td></tr>
        <tr><td><label>D.O.B.:</label></td><td><input style="width:180px;" type="date" name="DOB" autocomplete="off" required></td></tr>
        <tr><td><label>Upload Photo:</label></td><td><input type="file" name="photo" accept="image/*" required></td></tr>
        <tr><td><label>Email:</label></td><td><input class="in" type="email" name="email" autocomplete="off" required></td></tr>
        <tr><td><label>Contact:</label></td><td><input class="in" type="tel" name="phone" autocomplete="off" maxlength="10" required /></td></tr>
        <tr><td><label>Address:</label></td><td><input class="in" type="text" name="address" autocomplete="off" required></td></tr>
        <tr><td><label>Question:</label></td><td><select style="width:180px;" name="Question">
            <option value="">--Select--</option>
            <option value="What is your Childhood Nickname ?">What is your Childhood Nickname ?</option>
            <option value="What is your birth place ?">What is your birth place ?</option>
            <option value="What is the name of your pet ?">What is the name of your pet ?</option>
            <option value="What is your favourite color ?">What is your favourite color ?</option>
        </select></td></tr>
        <tr><td><label>Answer:</label></td><td><input class="in" type="text" name="answer" autocomplete="off" required></td></tr>
        <tr><td><label>Password:</label></td><td><input class="in" type="password" name="PW" autocomplete="off" required></td></tr>
        <tr><td><label>Confirm Password:</label></td><td><input class="in" type="password" name="CPW" autocomplete="off" required></td></tr>
        <tr><td><label>Security PIN:</label></td><td><input class="in" type="password" name="PIN" autocomplete="off" required></td></tr>
        <tr><td><input style="background-color:#001845;" class="btn" type="submit" name="submit"></td><td><button type="button" class="btn" onclick="cancel();" style="background-color:#8d0801;">Cancel</button></td></tr>

    </form>
    </div>
</body>
</html>
