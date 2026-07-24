<?php
    $servername = "localhost";
    $username = "kshatri1_Chauhan";
    $password = "Shrayank@Forever";
    $database="kshatri1_Chauhan";
    // Create connection
    $conn = new mysqli($servername, $username, $password,$database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //echo "<script>alert('Connected successfully');</script>";
?>