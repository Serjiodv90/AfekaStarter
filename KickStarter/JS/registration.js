$(document).ready(function () {
    $("#register").click(function () {
        var name = $("#name").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var cpassword = $("#cpassword").val();

        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

        if (name == '' || email == '' || password == '' || cpassword == '') {
            alert("Please fill all fields!!!!!!");
        }
        else if ((password.length) < 8) {
            alert("Password should atleast 8 character in length!!!!!!");
        }
        else if (!(password).match(cpassword)) {
            alert("Your passwords don't match. Try again?");
        }
        else if(!(emailReg.test(email))) {
            alert("Wrong email address");
        }
        else {
            var masToBack = {'email': email, 'password': password, 'name': name };
            $.post("/PHPFiles/registration.php", masToBack, function (data) {
                // data = JSON.parse(JSON.stringify(data));
                if (data == "ok") {
                    $("form")[0].reset();
                    document.location.href  = "/index.php";
                }
            });
        }
    });
});