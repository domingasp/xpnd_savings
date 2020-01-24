<?php include ("connect.php") ?><?php session_start();

if (isset($_SESSION["accountID"])) {

    $checkIfVerified = $conn -> prepare("SELECT verified FROM account WHERE id = ? AND verified = '1'");
    $checkIfVerified -> bind_param("s", $_SESSION["accountID"]);
    $checkIfVerified -> execute();
    $checkIfVerified -> store_result();

    if ($checkIfVerified -> num_rows > 0) {
        $_SESSION["emailValid"] = "Yes";
    } else {
        unset($_SESSION["emailValid"]);
    }
} 
?><!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <link rel="stylesheet" href="styles/all.css">
        <script src="scripts/jquery-3.4.1.min.js"></script>

        <meta name="viewport" content="width=device-width">
        <title>XPND Savings</title>
    </head>

    <body>
        <?php if (isset($_SESSION["accountID"]) && !isset($_SESSION["emailValid"]) && !isset($_SESSION["verificationEmailSent"])) { ?>
            <div class="verification-div">
                <span class="verification-div__span">Please verify your email by using the activation link sent to you. If you did not receive this email let us <a href="send_verification.php" class="verification-div__span__a">send you another</a>.</span>
                <button type="button" onclick="document.getElementsByClassName('verification-div')[0].style.display = 'none';" class="verification-div__button--close"><span>Close</span> <i class="fas fa-times"></i></button>
            </div>
        <?php } ?>

        <div class="header__menu">
            <a class="header__menu-a-logo" href="index.php">XPND Savings</a>
            <div class="header__menu-right">
                <?php if (isset($_SESSION["accountID"])) { ?>
                    <a class="header__menu-a" href="account.php"><span class="header__menu-a-span">My Account</span></a>
                    <a class="header__menu-a" href="sign_out.php"><span class="header__menu-a-span">Sign Out</span></a>
                <?php } else { ?>
                    <a class="header__menu-a" href="register.php"><span class="header__menu-a-span">Register</span></a>
                    <a class="header__menu-a" href="sign_in.php"><span class="header__menu-a-span">Sign In</span></a>
                <?php } ?>
            </div>
        </div>

        <div class="main-row-div">
            <div class="user-input-div">
                <div class="select-currency">
                    <p class="select-currency__p">Currency:</p>
                    <select id="selectCurrency" class="select-currency__change" onchange="changeCurrencyLabel()">
                        <option value="&dollar;" selected>&dollar;</option>
                        <option value="&pound;">&pound;</option>
                        <option value="&euro;">&euro;</option>
                    </select>
                </div>
                
                <div class="time-period">
                    <p class="time-period__p">Time Period:</p>

                    <label for="annualCheckbox" class="time-period__switch-label">
                        <input type="checkbox" id="annualCheckbox" name="timePeriod" value="annual" class="time-period__switch-input" onchange="updateInputLabels(this)">
                        <div class="time-period__switch-slider"></div>
                    </label>
                </div>

                <div class="money-div">
                    <label class="money-div__label" for="incomeAfterTaxInput">Monthly Income (After Tax):</label>
                    <span class="money-div__label-currency">$</span>
                    <input value="0.00" data-type="currency" type="text" name="incomeAfterTaxInput" id="incomeAfterTaxInput" class="money-div__input money-div__input-money-in" oninput="updateSavingResults()" onblur="moneyInputEmpty(this)">
                </div>

                <div class="money-div">
                    <label class="money-div__label" for="grocerySpendInput">Monthly Grocery Spend:</label>
                    <span class="money-div__label-currency">$</span>
                    <input value="0.00" data-type="currency" type="text" name="grocerySpendInput" id="grocerySpendInput" class="money-div__input money-div__input-money-out" oninput="updateSavingResults()" onblur="moneyInputEmpty(this)">
                </div>

                <div class="money-div">
                    <label class="money-div__label" for="travelSpendInput">Monthly Travel Spend:</label>
                    <span class="money-div__label-currency">$</span>
                    <input value="0.00" data-type="currency" type="text" name="travelSpendInput" id="travelSpendInput" class="money-div__input money-div__input-money-out" oninput="updateSavingResults()" onblur="moneyInputEmpty(this)">
                </div>

                <div class="money-div">
                    <label class="money-div__label" for="otherSpendInput">Other Monthly Spend:</label>
                    <span class="money-div__label-currency">$</span>
                    <input value="0.00" data-type="currency" type="text" name="otherSpendInput" id="otherSpendInput" class="money-div__input money-div__input-money-out" oninput="updateSavingResults()" onblur="moneyInputEmpty(this)">
                </div>
            </div>

            <div class="results-div">
                <div class="results-div__savings-div">
                    <p class="results-div__savings-div__title-p">Monthly Savings:</p>
                    <p class="results-div__savings-div__result-p">$0.00</p>
                </div>

                <div class="results-div__savings-div">
                        <p class="results-div__savings-div__title-p">Annual Savings:</p>
                        <p class="results-div__savings-div__result-p">$0.00</p>
                    </div>
            </div>
        </div>

        <script src="scripts/mainScript.js"></script>
    </body>
</html>