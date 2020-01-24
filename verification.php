<?php
    session_start();
    include "connect.php";

    $codeFound = false;
    $emailVerified = false;

    // Check if record exists
    $stmt = $conn -> prepare("SELECT id FROM account WHERE verification_code = ? and verified = '0'");
    $stmt -> bind_param("s", $_GET["code"]);
    $stmt -> execute();
    $result = $stmt -> get_result();

    if ($result -> num_rows > 0) {
        $codeFound = true;

        // Verify email
        $stmt = $conn -> prepare("UPDATE account SET verified = '1' WHERE verification_code = ?");
        $stmt -> bind_param("s", $_GET["code"]);

        if ($stmt -> execute()) {
            $emailVerified = true;
        }
    }

    header("Refresh: 5; URL='http://xpndsavings/index.php'");
?><!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <link rel="stylesheet" href="styles/all.css">
        <script src="scripts/jquery-3.4.1.min.js"></script>

        <meta name="viewport" content="width=device-width">
        <title>XPND Savings - Email Verification</title>
    </head>

    <body>

        <div class="header__menu">
            <a class="header__menu-a-logo" href="index.php">XPND Savings</a>
        </div>

        <div class="verification-page-div--outer">
            <div class="verification-page-div--inner">
                

            <?php if (isset($_SESSION["emailValid"])) { ?>
                <p class="verification-page-div__p">Your email has already been <span class="verification-page-div__p__span--success">verified</span>. You will be redirected to the home page in 5 seconds.</p>
            <?php } else if ($emailVerified) { ?>
                <p class="verification-page-div__p">Your email has been <span class="verification-page-div__p__span--success">successfully verified</span>! You will be redirected to the home page in 5 seconds.</p>
            <?php } else if (!$codeFound) { ?>
                <p class="verification-page-div__p">The verification code was <span class="verification-page-div__p__span--failure">not found</span>. You will be redirected to the home page in 5 seconds.</p>
            <?php } else { ?>
                <p class="verification-page-div__p">Your email <span class="verification-page-div__p__span--failure">was not verified</span>. You will be redirected to the home page in 5 seconds.</p>
            <?php } ?>
                
                    
                
                <div class="verfication-page-div__loader"></div>
                <p class="verification-page-div__p--redirection">If you are been automatically redirected <a class="verification-page-div__p--redirection--a" href="index.php">Return to Savings Calculator</a></p>
            </div>
        </div>

        <script src="scripts/mainScript.js"></script>
    </body>
</html>