<?php include ("connect.php") ?><?php
    session_start();

    if (isset($_SESSION["accountID"])) {
        header("Location: index.php");
    }

    $email = $password = "";

    $formSubmitted = false;
    $validEmail = false;
    $emailDoesNotExist = false;
    $validPasswordLength = false;
    $validPasswordUnequalToEmail = false;
    $validPasswordUncommon = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $commonPasswords = array("123456","password","12345678","1234","pussy","12345","dragon","qwerty","696969","mustang","letmein","baseball","master","michael","football","shadow","monkey","abc123","pass","6969","jordan","harley","ranger","iwantu","jennifer","hunter","2000","test","batman","trustno1","thomas","tigger","robert","access","love","buster","1234567","soccer","hockey","killer","george","sexy","andrew","charlie","superman","asshole","dallas","jessica","panties","pepper","1111","austin","william","daniel","golfer","summer","heather","hammer","yankees","joshua","maggie","biteme","enter","ashley","thunder","cowboy","silver","richard","orange","merlin","michelle","corvette","bigdog","cheese","matthew","121212","patrick","martin","freedom","ginger","blowjob","nicole","sparky","yellow","camaro","secret","dick","falcon","taylor","111111","131313","123123","bitch","hello","scooter","please","","porsche","guitar","chelsea","black","diamond","nascar","jackson","cameron","654321","computer","amanda","wizard","xxxxxxxx","money","phoenix","mickey","bailey","knight","iceman","tigers","purple","andrea","horny","dakota","aaaaaa","player","sunshine","morgan","starwars","boomer","cowboys","edward","charles","girls","booboo","coffee","xxxxxx","bulldog","ncc1701","rabbit","peanut","john","johnny","gandalf","spanky","winter","brandy","compaq","carlos","tennis","james","mike","brandon","fender","anthony","blowme","ferrari","cookie","chicken","maverick","chicago","joseph","diablo","sexsex","hardcore","666666","willie","welcome","chris","panther","yamaha","justin","banana","driver","marine","angels","fishing","david","maddog","hooters","wilson","butthead","dennis","captain","bigdick","chester","smokey","xavier","steven","viking","snoopy","blue","eagles","winner","samantha","house","miller","flower","jack","firebird","butter","united","turtle","steelers","tiffany","zxcvbn","tomcat","golf","bond007","bear","tiger","doctor","gateway","gators","angel","junior","thx1138","porno","badboy","debbie","spider","melissa","booger","1212","flyers","fish","porn","matrix","teens","scooby","jason","walter","cumshot","boston","braves","yankee","lover","barney","victor","tucker","princess","mercedes","5150","doggie","zzzzzz","gunner","horney","bubba","2112","fred","johnson","xxxxx","tits","member","boobs","donald","bigdaddy","bronco","penis","voyager","rangers","birdie","trouble","white","topgun","bigtits","bitches","green","super","qazwsx","magic","lakers","rachel","slayer","scott","2222","asdf","video","london","7777","marlboro","srinivas","internet","action","carter","jasper","monster","teresa","jeremy","11111111","bill","crystal","peter","pussies","cock","beer","rocket","theman","oliver","prince","beach","amateur","7777777","muffin","redsox","star","testing","shannon","murphy","frank","hannah","dave","eagle1","11111","mother","nathan","raiders","steve","forever","angela","viper","ou812","jake","lovers","suckit","gregory","buddy","whatever","young","nicholas","lucky","helpme","jackie","monica","midnight","college","baby","brian","mark","startrek","sierra","leather","232323","4444","beavis","bigcock","happy","sophie","ladies","naughty","giants","booty","blonde","golden","0","fire","sandra","pookie","packers","einstein","dolphins","0","chevy","winston","warrior","sammy","slut","8675309","zxcvbnm","nipples","power","victoria","asdfgh","vagina","toyota","travis","hotdog","paris","rock","xxxx","extreme","redskins","erotic","dirty","ford","freddy","arsenal","access14","wolf","nipple","iloveyou","alex","florida","eric","legend","movie","success","rosebud","jaguar","great","cool","cooper","1313","scorpio","mountain","madison","987654","brazil","lauren","japan","naked","squirt","stars","apple","alexis","aaaa","bonnie","peaches","jasmine","kevin","matt","qwertyui","danielle","beaver","4321","4128","runner","swimming","dolphin","gordon","casper","stupid","shit","saturn","gemini","apples","august","3333","canada","blazer","cumming","hunting","kitty","rainbow","112233","arthur","cream","calvin","shaved","surfer","samson","kelly","paul","mine","king","racing","5555","eagle","hentai","newyork","little","redwings","smith","sticky","cocacola","animal","broncos","private","skippy","marvin","blondes","enjoy","girl","apollo","parker","qwert","time","sydney","women","voodoo","magnum","juice","abgrtyu","777777","dreams","maxwell","music","rush2112","russia","scorpion","rebecca","tester","mistress","phantom","billy","6666","albert");

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

            if ($result -> num_rows === 0) {
                $emailDoesNotExist = true;
            }
        }

        // Password length is greater than or equal to 10
        if (strlen($password) >= 10) {
            $validPasswordLength = true;
        }

        // Password equal to email
        if (strtolower($password) != strtolower($email) && !in_array(strtolower($password), explode("@", strtolower($email)))) {
            $validPasswordUnequalToEmail = true;
        }

        // Password in the top 500 most common passwords
        if (!in_array(strtolower($password), $commonPasswords)) {
            $validPasswordUncommon = true;
        }

        if ($validEmail && $emailDoesNotExist && $validPasswordLength && $validPasswordUnequalToEmail && $validPasswordUncommon) {        
            // Create verification code for the email
            $verificationCode = substr(md5(mt_rand()), 0, 15);

            // Hash password ready to store in database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Add to database
            $stmt = $conn -> prepare("INSERT INTO account (email, password, verification_code) VALUES (?, ?, ?)");
            $stmt -> bind_param("sss", $email, $hashedPassword, $verificationCode);
            $stmt -> execute();

            $_SESSION["accountID"] = $stmt -> insert_id;

            $stmt -> close();

            $getEmailIdStmt = $conn -> prepare("SELECT id FROM account WHERE email = ?");
            $getEmailIdStmt -> bind_param("s", $email);
            $getEmailIdStmt -> execute();
            $getEmailIdStmt -> close();

            // Send Verification email
            $verificationLink = "http://xpndsavings/verification.php?code=" .$verificationCode;

                echo $verificationLink;

            $message = "Your Activation Code is ".$verificationCode. "";
            $to = $email;
            $subject = "Activation Code for XPNDSavings.com";
            $from = "test@xpndsavings.com"; //FAKE
            $body = "Your Activation Code is " .$verificationCode. " Please click on this link <a href='{$verificationLink}' target='_blank'>Link</a> to activate your account.";
            $headers = "From: " .$from;
            mail($to, $subject, $body, $headers);

            // Redirect on successful registration
            header("Location: index.php");
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
        <title>XPND Savings - Register</title>
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
                <form id="registrationForm" class="form-div__form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-div__form__heading-div">
                        <h1 class="form-div__form__heading-div__h1">Register</h1>
                        <p class="form-div__form__heading-div__p">Please complete this form to create an account.</p>
                    </div>

                    <hr class="form-div__form__hr">

                    <div class="form-div__form__input-div">
                        <label class="form-div__form__input-div__label" for="email">Email</label>
                        <?php if ($formSubmitted && !$emailDoesNotExist) { ?>
                            <input class="form-div__form__input-div__input form-div__form__input-div__input--error" id="emailInput" type="text" name="email" autocomplete="email" oninput="validateEmail(this)">
                            <p class="form-div__form__input-div__error" style="display: block;" id="emailError">This email address is already in use.</p>
                        <?php } else { ?>
                            <input class="form-div__form__input-div__input" id="emailInput" type="text" name="email" autocomplete="email" oninput="validateEmail(this)">
                            <p class="form-div__form__input-div__error" id="emailError">This email address is already in use.</p>
                        <?php } ?>
                    </div>

                    <div class="form-div__form__input-div">
                        <label class="form-div__form__input-div__label" for="password">Password</label>
                        <input class="form-div__form__input-div__input form-div__form__input-div__input--password" id="passwordInput" type="password" name="password"  oninput="checkPasswordContents(this)" autocomplete="password">
                        <button class="form-div__form__input-div__button--toggle" type="button" onclick="togglePasswordInput(this, false)" onblur="togglePasswordInput(this, true)" title="Show Password"><i class="fas fa-eye"></i></button>
                        <p class="form-div__form__input-div__error" id="passwordError">Please ensure the password meets the criteria below.</p>
                    </div>

                    <div class="form-div__form__requirements-div">
                        <p style="color: #323031;"><i id="atLeast8CharsCheck" class="fas fa-check"></i> At least 10 characters.</p>
                        <p style="color: #323031;"><i id="compareToEmailCheck" class="fas fa-check"></i> Different from email.</p>
                        <p style="color: #323031;"><i id="tooCommonCheck" class="fas fa-check"></i> Uncommon.</p>
                    </div>
                    
                    <p class="form-div__form__p">By creating an account you agree to our <a href="#" class="form-div__form__p__a">Terms & Privacy</a>.</p>
                    
                    <div class="form-div__form__button-div">
                        <button class="form-div__form__button-div__button" type="submit">Register</button>
                    </div>

                    <p class="form-div__form__p--bottom">Already have an account? <a href="sign_in.php" class="form-div__form__p--bottom__a">Sign in</a></p>
                </form>
            </div>
        </div>

        <script src="scripts/mainScript.js"></script>
    </body>
</html>