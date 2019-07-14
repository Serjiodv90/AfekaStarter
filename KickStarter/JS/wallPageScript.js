
// $(document).ready(function () {
//     $('textarea').autoResize();
// });

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
    imageSrc = $("#likeImg").attr("src");
    if(imageSrc.indexOf("liked") >= 0)   //unlike
        $("#likeImg").attr("src","/pics/like.png");
    
    else    //like
        $("#likeImg").attr("src","/pics/liked.png");
}