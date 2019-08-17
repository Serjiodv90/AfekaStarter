
//check if the user is logged in
$(document).ready(function () {

    $("form").reset();

    $.get("/PHPFiles/createTable.php",
    function (data, status) {
        // data = JSON.parse(JSON.stringify(data));
        console.log(data);

        if (data == "true") {
            console.log("Table create successfully");
        }
        else {
            console.log("Table creation failed");
        }
    });  
    
    //find out if there is logged in user
    $.get("/PHPFiles/login.php?function=isLogged",
        function (data, status) {
            data = JSON.parse(JSON.stringify(data));

            if (data["logged"] == "true" && data["name"]) {
                showLoggedInUser(data["name"]);
            }
            else {
                showLoginForm();
            }
        }, "json");  
});


function checkLoginData(event) {

   // event.preventDefault();


    var email = $("#emailTxt").val();
    var pass = $("#passTxt").val();
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;


    if (email == "" || pass == "" || !(emailReg.test(email))) {
        showLoginErrorMsg();
        return false;
    }

    else {
        return true;
    }
}


function showLoggedInUser(userName) {
    // $.get("/PHPFiles/login.php?function=redirectToWallPage",
    // function (data, status) {
        // document.write(data);
        $(".loginDisp").attr('class', 'loggedInDisp');
        $(".loggedInUser").text("Hello " + userName);
        $(".logOut").css("display", "flex");
    // });

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
    $(".loginErrorMsg").css("display", "flex");
    // $(".loginTextBoxes").css("border", "2px solid red");
    $(".loginTextBoxes").css("background-color", "#ffb3b3");
}

