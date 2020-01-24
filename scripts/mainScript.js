var commonPasswords = ["123456","password","12345678","1234","pussy","12345","dragon","qwerty","696969","mustang","letmein","baseball","master","michael","football","shadow","monkey","abc123","pass","6969","jordan","harley","ranger","iwantu","jennifer","hunter","2000","test","batman","trustno1","thomas","tigger","robert","access","love","buster","1234567","soccer","hockey","killer","george","sexy","andrew","charlie","superman","asshole","dallas","jessica","panties","pepper","1111","austin","william","daniel","golfer","summer","heather","hammer","yankees","joshua","maggie","biteme","enter","ashley","thunder","cowboy","silver","richard","orange","merlin","michelle","corvette","bigdog","cheese","matthew","121212","patrick","martin","freedom","ginger","blowjob","nicole","sparky","yellow","camaro","secret","dick","falcon","taylor","111111","131313","123123","bitch","hello","scooter","please","","porsche","guitar","chelsea","black","diamond","nascar","jackson","cameron","654321","computer","amanda","wizard","xxxxxxxx","money","phoenix","mickey","bailey","knight","iceman","tigers","purple","andrea","horny","dakota","aaaaaa","player","sunshine","morgan","starwars","boomer","cowboys","edward","charles","girls","booboo","coffee","xxxxxx","bulldog","ncc1701","rabbit","peanut","john","johnny","gandalf","spanky","winter","brandy","compaq","carlos","tennis","james","mike","brandon","fender","anthony","blowme","ferrari","cookie","chicken","maverick","chicago","joseph","diablo","sexsex","hardcore","666666","willie","welcome","chris","panther","yamaha","justin","banana","driver","marine","angels","fishing","david","maddog","hooters","wilson","butthead","dennis","captain","bigdick","chester","smokey","xavier","steven","viking","snoopy","blue","eagles","winner","samantha","house","miller","flower","jack","firebird","butter","united","turtle","steelers","tiffany","zxcvbn","tomcat","golf","bond007","bear","tiger","doctor","gateway","gators","angel","junior","thx1138","porno","badboy","debbie","spider","melissa","booger","1212","flyers","fish","porn","matrix","teens","scooby","jason","walter","cumshot","boston","braves","yankee","lover","barney","victor","tucker","princess","mercedes","5150","doggie","zzzzzz","gunner","horney","bubba","2112","fred","johnson","xxxxx","tits","member","boobs","donald","bigdaddy","bronco","penis","voyager","rangers","birdie","trouble","white","topgun","bigtits","bitches","green","super","qazwsx","magic","lakers","rachel","slayer","scott","2222","asdf","video","london","7777","marlboro","srinivas","internet","action","carter","jasper","monster","teresa","jeremy","11111111","bill","crystal","peter","pussies","cock","beer","rocket","theman","oliver","prince","beach","amateur","7777777","muffin","redsox","star","testing","shannon","murphy","frank","hannah","dave","eagle1","11111","mother","nathan","raiders","steve","forever","angela","viper","ou812","jake","lovers","suckit","gregory","buddy","whatever","young","nicholas","lucky","helpme","jackie","monica","midnight","college","baby","brian","mark","startrek","sierra","leather","232323","4444","beavis","bigcock","happy","sophie","ladies","naughty","giants","booty","blonde","golden","0","fire","sandra","pookie","packers","einstein","dolphins","0","chevy","winston","warrior","sammy","slut","8675309","zxcvbnm","nipples","power","victoria","asdfgh","vagina","toyota","travis","hotdog","paris","rock","xxxx","extreme","redskins","erotic","dirty","ford","freddy","arsenal","access14","wolf","nipple","iloveyou","alex","florida","eric","legend","movie","success","rosebud","jaguar","great","cool","cooper","1313","scorpio","mountain","madison","987654","brazil","lauren","japan","naked","squirt","stars","apple","alexis","aaaa","bonnie","peaches","jasmine","kevin","matt","qwertyui","danielle","beaver","4321","4128","runner","swimming","dolphin","gordon","casper","stupid","shit","saturn","gemini","apples","august","3333","canada","blazer","cumming","hunting","kitty","rainbow","112233","arthur","cream","calvin","shaved","surfer","samson","kelly","paul","mine","king","racing","5555","eagle","hentai","newyork","little","redwings","smith","sticky","cocacola","animal","broncos","private","skippy","marvin","blondes","enjoy","girl","apollo","parker","qwert","time","sydney","women","voodoo","magnum","juice","abgrtyu","777777","dreams","maxwell","music","rush2112","russia","scorpion","rebecca","tester","mistress","phantom","billy","6666","albert"];

var savingResultsLabels = document.getElementsByClassName("results-div__savings-div__result-p"); // 0: Monthly & 1: Annual

var currencySelected = "$";

// Changes the currency labels next to currency input fields
function changeCurrencyLabel() {
    var selectedCurrency = document.getElementById("selectCurrency").value;
    currencySelected = selectedCurrency;

    for (let currencyLabel of document.getElementsByClassName("money-div__label-currency")) {
        currencyLabel.innerHTML = selectedCurrency;
    }

    updateSavingResults();
}

// Updates the input labels to change to either monthly or annual
function updateInputLabels(timePeriodCheckbox) {
    var timePeriod = "Monthly";
    var inputLabels = document.getElementsByClassName("money-div__label");
    
    if (timePeriodCheckbox.checked) {
        timePeriod = "Annual";
    }

    inputLabels[0].innerHTML = timePeriod + " Income (After Tax):";
    inputLabels[1].innerHTML = timePeriod + " Grocery Spend:";
    inputLabels[2].innerHTML = timePeriod + " Travel Spend:";
    inputLabels[3].innerHTML = "Other " + timePeriod + " Spend:";

    updateSavingResults();
}

function moneyInputEmpty(inputToUpdate) {
    if (parseFloat(inputToUpdate.value.replace(new RegExp(",", "g"), "")) <= 0 || inputToUpdate.value == "") {
        inputToUpdate.value = "0.00";
    }
}

// Calculates the savings results
function updateSavingResults() {
    var incomeAmount = parseFloat(document.getElementById("incomeAfterTaxInput").value.replace(new RegExp(",", "g"), "")) > 0 
                            ? parseFloat(document.getElementById("incomeAfterTaxInput").value.replace(new RegExp(",", "g"), "")) : 0;

    var grocerySpendAmount = parseFloat(document.getElementById("grocerySpendInput").value.replace(new RegExp(",", "g"), "")) > 0 
                            ? parseFloat(document.getElementById("grocerySpendInput").value.replace(new RegExp(",", "g"), "")) : 0;

    var travelSpendAmount = parseFloat(document.getElementById("travelSpendInput").value.replace(new RegExp(",", "g"), "")) > 0 
                            ? parseFloat(document.getElementById("travelSpendInput").value.replace(new RegExp(",", "g"), "")) : 0;

    var otherSpendAmount = parseFloat(document.getElementById("otherSpendInput").value.replace(new RegExp(",", "g"), "")) > 0 
                            ? parseFloat(document.getElementById("otherSpendInput").value.replace(new RegExp(",", "g"), "")) : 0;


    var monthlySavings = 0;
    var annualSavings = 0;

    if (document.getElementById("annualCheckbox").checked) {
        // Calculate annual
        annualSavings = incomeAmount - grocerySpendAmount - travelSpendAmount - otherSpendAmount;
        monthlySavings = annualSavings / 12;
    } else {
        // Calculate monthly
        monthlySavings = incomeAmount - grocerySpendAmount - travelSpendAmount - otherSpendAmount;
        annualSavings = monthlySavings * 12;
    }

    // Updates the results labels with the results and currency
    savingResultsLabels[0].innerHTML = currencySelected + "" + monthlySavings.toFixed(2);
    savingResultsLabels[1].innerHTML = currencySelected + "" + annualSavings.toFixed(2);
}

// Toggles the password visibility and alters the style of the toggle button
function togglePasswordInput(toggleButton, lostFocus) {
    var inputField = document.getElementById("passwordInput");

    if (inputField.type === "password" && !lostFocus) {
        inputField.type = "text";
        toggleButton.innerHTML = "<i class=\"fas fa-eye-slash\"></i>";
        toggleButton.style.backgroundColor = "#542344";
        toggleButton.style.color = "#FFC857";
        toggleButton.title = "Hide Password";
    } else if (inputField.type === "text" || lostFocus) {
        inputField.type = "password";
        toggleButton.innerHTML = "<i class=\"fas fa-eye\"></i>";
        toggleButton.style.backgroundColor = "transparent";
        toggleButton.style.color = "#323031";
        toggleButton.title = "Show Password";
    }
}

function validateEmail(inputField) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    var emailValid = re.test(String(inputField.value).toLowerCase());

    if (emailValid) {
        removeErrorClassFromInputs(inputField);
        $("#emailError").css("display", "none");
    }

    return emailValid;
}

function passwordEmpty(inputField) {
    if (inputField.value.length > 0) {
        removeErrorClassFromInputs(inputField);
        $("#passwordError").css("display", "none");
    }
}

// Checks the contents of the current password input and verifies that the password meets the requirements
function checkPasswordContents(inputField) {
    var emailInput = document.getElementById("emailInput").value.toLowerCase().split("@");
    var currentInput = inputField.value;

    var minimumCharactersSpan = document.getElementById("atLeast8CharsCheck");
    var tooCommonSpan = document.getElementById("tooCommonCheck");
    var equalToEmailSpan = document.getElementById("compareToEmailCheck");

    var correctLength = false;
    var notEqualToEmail = false;
    var uncommon = false;

    // Password length is greater than or equal to 10
    if (currentInput.length >= 10) {
        minimumCharactersSpan.style.color = "#14cc60";
        correctLength = true;
    } else {
        minimumCharactersSpan.style.color = "#323031";
    }

    // Password equal to email
    if (currentInput.toLowerCase() != document.getElementById("emailInput").value.toLowerCase() && !emailInput.includes(currentInput.toLowerCase())) {
        equalToEmailSpan.style.color = "#14cc60";
        notEqualToEmail = true;
    } else {
        equalToEmailSpan.style.color = "#323031";
    }

    // Password in the top 500 most common passwords
    if (!commonPasswords.includes(currentInput.toLowerCase())) {
        tooCommonSpan.style.color = "#14cc60";
        uncommon = true;
    } else {
        tooCommonSpan.style.color = "#323031";
    }

    if (correctLength && notEqualToEmail && uncommon) {
        removeErrorClassFromInputs(inputField);
        $("#passwordError").css("display", "none");
    }

    return correctLength && notEqualToEmail && uncommon;
}

$("#registrationForm").submit(function (e) {
    var emailValid = validateEmail($("#emailInput")[0]);
    var passwordValid = checkPasswordContents($("#passwordInput")[0]);
    // If either inputs are invalid then prevent the form from submitting
    if (!(emailValid && passwordValid)) {
        e.preventDefault();

        // Show error messages
        if (!emailValid) {
            var emailError = document.getElementById("emailError");

            if (document.getElementById("emailInput").value == null || document.getElementById("emailInput").value == "") {
                emailError.innerHTML = "Please enter an email.";
            } else {
                emailError.innerHTML = "Please enter a valid email.";
            }
            
            emailError.style.display = "block";

            $("#emailInput").addClass("form-div__form__input-div__input--error");
        }

        // Show error messages
        if (!passwordValid) {
            var passwordError = document.getElementById("passwordError");

            if (document.getElementById("passwordInput").value == null || document.getElementById("passwordInput").value == "") {
                passwordError.innerHTML = "Please enter a password.";
            } else {
                passwordError.innerHTML = "Please ensure the password meets the criteria below.";
            }

            passwordError.style.display = "block";

            $("#passwordInput").addClass("form-div__form__input-div__input--error");

            if (!emailValid) {
                document.getElementById("emailInput").focus();
            } else {
                document.getElementById("passwordInput").focus();
            }
        }

    }
});

$("#signInForm").submit(function (e) {
    var emailValid = validateEmail($("#emailInput")[0]);
    var passwordValid = false;

    if (document.getElementById("passwordInput").value == null || document.getElementById("passwordInput").value == "") {
        passwordValid = false;
    } else {
        passwordValid = true;
    }

    // If either inputs are invalid then prevent the form from submitting
    if (!(emailValid && passwordValid)) {
        e.preventDefault();

        // Show error messages
        if (!emailValid) {
            var emailError = document.getElementById("emailError");

            if (document.getElementById("emailInput").value == null || document.getElementById("emailInput").value == "") {
                emailError.innerHTML = "Please enter an email.";
            } else {
                emailError.innerHTML = "Please enter a valid email.";
            }
            
            emailError.style.display = "block";

            $("#emailInput").addClass("form-div__form__input-div__input--error");
        }

        if (!passwordValid) {
            // Show error messages
            var passwordError = document.getElementById("passwordError");
            passwordError.innerHTML = "Please enter a password.";
            passwordValid = false;

            passwordError.style.display = "block";

            $("#passwordInput").addClass("form-div__form__input-div__input--error");
        }

        

        if (!emailValid) {
            document.getElementById("emailInput").focus();
        } else {
            document.getElementById("passwordInput").focus();
        }

    }
});

function removeErrorClassFromInputs(inputField) {
    if (inputField.classList.contains("form-div__form__input-div__input--error")) {
        $(inputField).removeClass("form-div__form__input-div__input--error");
    }
}

// ##################################################### Currency Input ##################################################### //
$("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
});


function formatNumber(n) {
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
    var input_val = input.val();
  
    if (input_val === "") {
        return;
    }
  
    var original_len = input_val.length;

    var caret_pos = input.prop("selectionStart");
    
    if (input_val.indexOf(".") >= 0) {

        var decimal_pos = input_val.indexOf(".");

        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        left_side = formatNumber(left_side);
        right_side = formatNumber(right_side);

        if (blur === "blur") {
            right_side += "00";
        }

        right_side = right_side.substring(0, 2);
        input_val = left_side + "." + right_side;

    } else {
        input_val = formatNumber(input_val);
        input_val = input_val;
    
        if (blur === "blur") {
            input_val += ".00";
        }
    }
  
    input.val(input_val);

    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
}

// ##################################################### END Currency Input ##################################################### //