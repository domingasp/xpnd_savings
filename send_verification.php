<?php
    session_start();

    if (isset($_SESSION["accountID"])) {
        include "connect.php";

        $getCodeStmt = $conn -> prepare("SELECT * FROM account WHERE id = ? AND verified = '0'");
        $getCodeStmt -> bind_param("s", $_SESSION["accountID"]);
        $getCodeStmt -> execute();
        $number = $getCodeStmt -> get_result();
        $getCodeStmt -> close();

        if ($number -> num_rows > 0) {
            $verificationCode = substr(md5(mt_rand()), 0, 15);

            $updateDbWithNewCode = $conn -> prepare("UPDATE account SET verification_code = ? WHERE id = ?");
            $updateDbWithNewCode -> bind_param("ss", $verificationCode, $_SESSION["accountID"]);
            $updateDbWithNewCode -> execute();
            $updateDbWithNewCode -> close();

            $getCodeStmt = $conn -> prepare("SELECT email FROM account WHERE id = ?");
            $getCodeStmt -> bind_param("s", $_SESSION["accountID"]);
            $getCodeStmt -> execute();
            $getCodeStmt -> bind_result($email);
            $getCodeStmt -> fetch();

            // Send Verification email
            $verificationLink = "http://xpndsavings/verification.php?code=" .$verificationCode;

            $message = "Your Activation Code is ".$verificationCode. "";
            $to = $email;
            $subject = "Activation Code for XPNDSavings.com";
            $from = "test@xpndsavings.com"; //FAKE


            $body = "
                <html>
                    <head>
                        <title>XPND Savings Activation Code</title>
                    </head>
                    <body>
                        <p>Your activation code is " .$verificationCode. " Please click on the link <a href='{$verificationLink}' target='_blank'>Link</a> to activate your account.</p>
                    </body>
                </html>
                ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $headers .= "From: " .$from;
            mail($to, $subject, $body, $headers);

            $getCodeStmt -> close();

            $_SESSION["verificationEmailSent"] = true;
            header("Location: index.php");
        }
    }
?>