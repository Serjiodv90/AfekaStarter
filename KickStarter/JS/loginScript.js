
//check if the user is logged in
$(document).ready(function () {

    $.get("/PHPFiles/createTable.php",
    function (data, status) {
        // data = JSON.parse(JSON.stringify(data));
        console.log(data);

        if (data == "true") {
            alert("Table create successfully");
        }
        else {
            alert("Table creation failed");
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
        }, "json");  //ajax connection to server

        // //get the projects list
        // $.get("/PHPFiles/projects.php",
        //     function (data, status) {
        //         data = JSON.parse(JSON.stringify(data));

        //         $('#projects').append(data);
        //     }, "json");

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
        var masToBack = { email: email, password: pass };
        $.post("/PHPFiles/login.php?function=loginVerify", masToBack, verifyUserFromDB, "json");  //ajax connection to server
        // location.reload();
    }
}

function verifyUserFromDB(data, status) {
    data = JSON.parse(JSON.stringify(data));
  //  alert("in JS!");
    console.log(data);

    if (data != "wrong user") {
        var userName = data["name"];

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