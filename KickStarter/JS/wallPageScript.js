
updateWall = () => {
    
    $.post("/PHPFiles/wallFeedController.php", { function: "getAllPostsOfUser" },
        function (data) {
            $("#posts").empty();
            $("#posts").append(data);
        });
};

$(document).ready(updateWall);
//     function () {

//     $.post("/PHPFiles/wallFeedController.php", { function: "getAllPostsOfUser" },
//         function (data) {
//             $("#posts").append(data);
//         });
// });

function logOut() {

    $.get("/PHPFiles/login.php?function=logOut",
        function (data) {
            data = JSON.parse(JSON.stringify(data));
            if (data["status"] == "success") {
                window.location.replace(data["page"]);
            }
        }, "json");  //ajax connection to server
}

function like(postId) {
    imageSelectorXpath = (".likeBtn.like_").concat(postId, " > .likeImg");
    image = $(imageSelectorXpath);
    imageSrc = image.attr("src");

    if (imageSrc.indexOf("liked") >= 0)   //unlike
        image.attr("src", "/pics/like.png");
    else    //like
        image.attr("src", "/pics/liked.png");
}

function savePost() {
    savePostPath = "/PHPFiles/wallFeedController.php";
    postTextAreaEl = $("#userPostTA");
    content = postTextAreaEl.val();

    privacyCheckbox = $("#cb5");
    privacyContent = privacyCheckbox.is(":checked");

    if (content && content !== "") {
        paramsToBack = { function: "insertPost", postContent: content, isPrivate: privacyContent };
        $.post(savePostPath, paramsToBack, function (data) {
            privacyCheckbox.prop('checked', false); // Unchecks it
            postTextAreaEl.val("");
           
        });
    }
}

function addComment(postId) {
    savePostPath = "/PHPFiles/wallFeedController.php";
    commentContentSelectorXpath = (".ta_").concat(postId);
    textArea = $(commentContentSelectorXpath);
    commentContent = textArea.val();
    if (commentContent && commentContent !== "") {
        paramsToBack = { function: "insertCommentToPostByPostId", postId: postId, commentContent: commentContent };
        $.post(savePostPath, paramsToBack, function (data) {
            textArea.val("");
            updateWall();
        });
    }
}

