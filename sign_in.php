<?php include ("connect.php") ?><?php
    session_start();

    $email = $password = "";

    $formSubmitted = false;
    $validEmail = false;
    $emailExists = false;

    if (isset($_SESSION["accountID"])) {
        header("Location: index.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $formSubmitted = true;
        $email = strtolower(test_input($_POST["email"]));
        $password = test_input($_POST["password"]);

        //Email validation
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validEmail = true;

            // Checks whether email is already in the database
            $checkEmailsStmt = $conn -> prepare("SELECT * FROM account WHERE email = ?");
            $checkEmailsStmt -> bind_param("s", $email);
            $checkEmailsStmt -> execute();
            $result = $checkEmailsStmt -> get_result();

            if ($result -> num_rows > 0) {
                $emailExists = true;
            }
        }

        // If email is valid and exists find the hashed password and compare to user input after which you log the user in or show an error message
        if ($validEmail && $emailExists) {        

            // Add to database
            $stmt = $conn -> prepare("SELECT password, id FROM account WHERE email = ?");
            $stmt -> bind_param("s", $email);
            $stmt -> execute();
            $stmt -> bind_result($hash, $id);
            $stmt -> fetch();

            // If password matches hash then log the user in and send them to the index page
            if (password_verify($password, $hash)) {
                $_SESSION["accountID"] = $id;

                // Redirect on successful sign in
                header("Location: index.php");
            }

            $stmt -> close();

        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?><!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <link rel="stylesheet" href="styles/all.css">
        <script src="scripts/jquery-3.4.1.min.js"></script>

        <meta name="viewport" content="width=device-width">
        <title>XPND Savings - Sign In</title>
    </head>

    <body>
        <div class="header__menu">
            <a class="header__menu-a-logo" href="index.php">XPND Savings</a>
            <div class="header__menu-right">
            <a class="header__menu-a" href="index.php"><span class="header__menu-a-span">Savings Calculator</span></a>
            </div>
        </div>

        <div class="form-div--outer">
            <div class="form-div--inner">
                <form id="signInForm" class="form-div__form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-div__form__heading-div">
                        <h1 class="form-div__form__heading-div__h1">Sign In</h1>
                        <p class="form-div__form__heading-div__p">Please enter your details to sign in to your account.</p>
                    </div>

                    <hr class="form-div__form__hr">

                    <?php if ($formSubmitted && !isset($_SESSION["accountID"])) { ?>
                        <p class="form-div__form__p--error">The email/password combination does not match our records.</p>
                    <?php } ?>

                    <div class="form-div__form__input-div">
                        <label class="form-div__form__input-div__label" for="email">Email</label>
                        <input class="form-div__form__input-div__input" id="emailInput" type="text" name="email" autocomplete="email" oninput="validateEmail(this)">
                        <p class="form-div__form__input-div__error" id="emailError">Please enter an email.</p>
                    </div>

                    <div class="form-div__form__input-div">
                        <label class="form-div__form__input-div__label" for="password">Password</label>
                        <input class="form-div__form__input-div__input form-div__form__input-div__input--password" id="passwordInput" type="password" name="password" autocomplete="password" oninput="passwordEmpty(this)">
                        <button class="form-div__form__input-div__button--toggle" type="button" onclick="togglePasswordInput(this, false)" onblur="togglePasswordInput(this, true)" title="Show Password"><i class="fas fa-eye"></i></button>
                        <p class="form-div__form__input-div__error" id="passwordError">Please enter a password.</p>
                    </div>
                    
                    <div class="form-div__form__button-div--sign-in">
                        <button class="form-div__form__button-div__button" type="submit">Sign In</button>
                    </div>

                    <p class="form-div__form__p--bottom">Don't have an account? <a href="register.php" class="form-div__form__p--bottom__a">Register</a></p>
                </form>
            </div>
        </div>

        <script src="scripts/mainScript.js"></script>
    </body>
</html>