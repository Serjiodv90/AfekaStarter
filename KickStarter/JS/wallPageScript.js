
$(document).ready(function () {
    
    

    function createPosts() {

    }






});

function logOut() {

    $.get("/PHPFiles/login.php?function=logOut",
        function (data) {
            data = JSON.parse(JSON.stringify(data));
            if (data["status"] == "success") {
                window.location.replace(data["page"]);
            }
        },"json");  //ajax connection to server
}

function like() {
    imageSrc = $(".likeImg").attr("src");
    if(imageSrc.indexOf("liked") >= 0)   //unlike
        $(".likeImg").attr("src","/pics/like.png");
    
    else    //like
        $(".likeImg").attr("src","/pics/liked.png");
}

function savePost() {
    savePostPath = "/PHPFiles/wallFeedController.php";
    postTextAreaEl = $("#userPostTA");
    content = postTextAreaEl.val();

    privacyCheckbox = $("#cb5"); 
    privacyContent = privacyCheckbox.is(":checked");
    
    if(content && content !== "") {
        paramsToBack = {function: "insertPost", postContent: content, isPrivate: privacyContent};
        $.post(savePostPath, paramsToBack, function(data) {
            privacyCheckbox.prop('checked', false); // Unchecks it
            postTextAreaEl.val("");
            console.log(data);
        });


    }

    
}