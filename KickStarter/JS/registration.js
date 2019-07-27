
function validateReg(){
    var name = $("#name").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var cpassword = $("#cpassword").val();

    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

    if (name == '' || email == '' || password == '' || cpassword == '') {
        alert("Please fill all fields!!!!!!");
        return false;
    }
    else if ((password.length) < 8) {
        alert("Password should atleast 8 character in length!!!!!!");
        return false;
    }
    else if (!(password).match(cpassword)) {
        alert("Your passwords don't match. Try again?");
        return false;
    }
    else if(!(emailReg.test(email))) {
        alert("Wrong email address");
        return false;
    }

    return true;
}

function addImageButton() {
    $("#uploadImage").click();
}

