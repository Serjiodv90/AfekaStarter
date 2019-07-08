
//check if the user is logged in
$(document).ready(function () {

    $.get("/PHPFiles/createTable.php",
    function (data, status) {
        data = JSON.parse(JSON.stringify(data));
        console.log(data);
        // data = data["val"];

        if (data == "true") {
            alert("Table create successfully");
        }
        else {
            alert("Table creation failed");
        }
    });  
    
    //find out if there is logged in user
    $.post("/PHPFiles/login.php?function=isLogged", "",
        function (data, status) {
            data = JSON.parse(JSON.stringify(data));

            if (data["logged"] == "true" && data["name"]) {
                showLoggedInUser(data["name"]);
            }
            else {
                showLoginForm();
            }
        }, "json");  //ajax connection to server

        //get the projects list
        $.post("/PHPFiles/projects.php", "", 
            function (data, status) {
                data = JSON.parse(JSON.stringify(data));

                $('#projects').append(data);
            }, "json");

});


function checkLoginData(event) {

    event.preventDefault();


    var email = $("#emailTxt").val();
    var pass = $("#passTxt").val();
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;


    if (email == "" || pass == "" || !(emailReg.test(email))) {
        showLoginErrorMsg();
    }

    else {
        var masToBack = { email: email, password: pass };
        $.post("/PHPFiles/login.php?function=loginVerify", masToBack, verifyUserFromDB, "json");  //ajax connection to server
        location.reload();
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
//    $(".loginForm").css("display", "none");
 //   $(".loginDisp").css("color", "white");
    $(".loginDisp").attr('class', 'loggedInDisp');
    $(".loggedInUser").text("Hello " + userName);
    $(".logOut").css("display", "flex");
}

function showLoginForm() {
    //  if ($(".loginForm").is(":hidden")) {
    $(".loggedInDisp").attr('class', 'loginDisp');
    $(".logOut").css("display", "none");
 //   $(".loginDisp").text("");
 //   $(".loginDisp").append($(".loginForm").html());
 //   $(".loginForm").css("display", "flex");
    // $(".loginForm").toggle();
    // $(".loginForm:hidden").show();

    //  }

}

function showLoginErrorMsg() {
    $(".loginErrorMsg").css("display", "block");
    // $(".loginTextBoxes").css("border", "2px solid red");
    $(".loginTextBoxes").css("background-color", "#ffb3b3");
}



//REGISTRATION

function goToReg() {
    document.location.href = "/html/reg.html";
}

function logOut() {

    $.post("/PHPFiles/login.php?function=logOut", "",
        function (data) {
            data = JSON.parse(JSON.stringify(data));
            if (data == "success") {
                alert("logged Out");
                showLoginForm();
            }
        }, "json");  //ajax connection to server

    location.reload();
}