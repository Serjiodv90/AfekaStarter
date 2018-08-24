



function checkLoginData(event) {

    event.preventDefault();

    
    var email = $("#emailTxt").val();
    var pass = $("#passTxt").val();
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;


    if (email == "" || pass == "" || !(emailReg.test(email))) {
        showLoginErrorMsg();
    }

    else {
        var masToBack = {email: email, password: pass };
        $.post("/PHPFiles/login.php?function=loginVerify",  masToBack, verifyUserFromDB, "json");  //ajax connection to server
    }
}

function verifyUserFromDB(data, status) {
    data = JSON.parse(JSON.stringify(data));
    alert("in JS!");
    if (data != "wrong user") {
        var userName = data['name'];

        if (status == "success" && userName) {
            showLoggedInUser(userName);
        }
        else {
            showLoginErrorMsg();
        }
    }
    else 
    showLoginErrorMsg();

}

function showLoggedInUser(userName) {
    $(".loginForm").hide();
    $(".loginDisp").css("color", "white");
    $(".loginDisp").text("Hello " + userName);
}

function showLoginErrorMsg() {
    $(".loginErrorMsg").css("display", "block");
}