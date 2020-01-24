<?php
    $db_host = "localhost"; // FOR DEV ONLY
    $db_username = "root";
    $db_password = "password";
    $db_name = "xpndsavings";
    $conn = new mysqli($db_host,$db_username,$db_password,$db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>