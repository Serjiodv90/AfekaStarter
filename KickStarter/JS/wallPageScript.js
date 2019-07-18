
updateWall = () => {

    $.post("/PHPFiles/wallFeedController.php", { function: "getAllPostsOfUser" },
        function (data) {
            $("#posts").empty();
            $("#posts").append(data);
        });
};

$(document).ready(updateWall);


function addFriendToCurrentUser(friendId, friendName) {
    addFriendPath = "/PHPFiles/wallFeedController.php";
    paramsToBack = { function: "addFriendToCurrentUser", friendsId: friendId };

    $.post(addFriendPath, paramsToBack, function (data) {
        emptyFriendsSerachResult();
        if (data && data === "ok") {
            var userNameDiv = document.createElement('div');
            userNameDiv.setAttribute('id', "addedFriendCongrats");
            var congratsMsg = document.createElement('p');
            congratsMsg.innerHTML = "Congrats! you and " + friendName + ",\nare friends now!";
            userNameDiv.appendChild(congratsMsg);
            $("#friendsResult").append(userNameDiv);

            $("#addedFriendCongrats").fadeOut(5000, function () { emptyFriendsSerachResult(); });
        }

    });



}


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
            updateWall();


        });
    }
}

function addComment(postId) {
    addCommentPath = "/PHPFiles/wallFeedController.php";
    commentContentSelectorXpath = (".ta_").concat(postId);
    textArea = $(commentContentSelectorXpath);
    commentContent = textArea.val();
    if (commentContent && commentContent !== "") {
        paramsToBack = { function: "insertCommentToPostByPostId", postId: postId, commentContent: commentContent };
        $.post(addCommentPath, paramsToBack, function (data) {
            textArea.val("");
            updateWall();
        });
    }
}

function insertUserNameInSearchResult(userId, userName) {
    // $("#friendsResult").html(data);
    $("#friendsResult").empty();
    var userNameDiv = document.createElement('div');
    userNameDiv.setAttribute('class', "userName u_" + userId);
    userNameDiv.setAttribute('onclick', "addFriendToCurrentUser(" + userId + ", \"" + userName + "\");");
    userNameDiv.innerHTML = userName;
    $("#friendsResult").append(userNameDiv);


}

function emptyFriendsSerachResult() {
    // $("#searchBar > input").val("");
    $("#friendsResult").empty();
}

//remove friends search result
$(document).click(function(event) { 
    $target = $(event.target);
    if(!$target.closest('#friendsResult').length || !$target.is(('#friendsResult')))
        emptyFriendsSerachResult();

  });

function searchFriend(stringToSearch) {
    searchFriendPath = "/PHPFiles/wallFeedController.php";

    if (stringToSearch && stringToSearch !== "") {
        paramsToBack = { function: "searchUser", stringToSearch: stringToSearch };
        $.post(searchFriendPath, paramsToBack, function (data) {
            data = JSON.parse(JSON.stringify(data));
            if (data && data !== "false") {

                for (var key in data) {
                    insertUserNameInSearchResult(key, data[key]);
                    console.log(data[key]);
                }
            }
            else
                emptyFriendsSerachResult();


        }, "json");
    }
    else
        $("#friendsResult").html("");




}

