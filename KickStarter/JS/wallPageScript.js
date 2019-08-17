
var imagesToUploadArray = [];
var flappyBirdUrl = 'http://localhost:5000';


updateWall = () => {

    $.post("/PHPFiles/wallFeedController.php", { function: "getAllPostsOfUser" },
        function (data) {
            $("#posts").empty();
            $("#posts").append(data);
            checkForNotifications();
        });
};



previewPostImages = () => {


    $("#postImagePreview").empty();

    var numOfImages = imagesToUploadArray.length;
    var files = imagesToUploadArray;
    for (var i = 0; i < numOfImages; i++) {
        var img = files[i];
        var imageName = img.name;
        var imageType = img.type;
        var match = ["image/jpeg", "image/png", "image/jpg", "image/gif"];

        if (!match.includes(imageType)) {

            alert("wrong image type: " + imageType);
        }
        else {
            var postInsertImageDiv = document.createElement('div');
            postInsertImageDiv.setAttribute('class', "postInsertImage");
            var postImage = document.createElement('img');
            postImage.setAttribute('class', "closablePostImage");
            postImage.setAttribute('src', URL.createObjectURL(img));
            postImage.setAttribute('onclick', "enlargeImage(this);");
            postInsertImageDiv.append(postImage);
            var closeIcon = document.createElement('i');
            closeIcon.setAttribute('class', "icon fa fa-close");
            closeIcon.setAttribute('onclick', "deleteImageFromPost('" + imageName + "');");
            postInsertImageDiv.append(closeIcon);


            $("#postImagePreview").append(postInsertImageDiv);

        }
    }

};

cleanPostArea = () => {
    $("#cb5").prop('checked', false); // Unchecks it
    $("#userPostTA").val("");
    $("#uploadImage").val("");
    $("#postImage").empty();
    $("#postImagePreview").empty();
    imagesToUploadArray = [];
};

$(document).ready(function (event) {

    // updateWall();

    setInterval(updateWall, 60*1000);

    $(".addImageFrom").on('submit', function (event) {
        event.preventDefault();
        postTextAreaEl = $("#userPostTA");
        content = postTextAreaEl.val();
        var numOfImages = $("#uploadImage").get(0).files.length;

        if ((content && content !== "") || numOfImages > 0) {
            const formData = new FormData();
            formData.append('postContent', content);
            if ($("#cb5").is(":checked"))
                formData.append('privacyCheckbox', 1);
            else
                formData.append('privacyCheckbox', 0);

            if (imagesToUploadArray.length <= 0)
                formData.append('postImage[]', "");
            else {
                for (var i = 0; i < imagesToUploadArray.length; i++) {
                    formData.append('postImage[]', imagesToUploadArray[i]);
                }
            }

            $.ajax({
                url: "/PHPFiles/PostUpload.php",
                type: "POST",
                data: formData,//new FormData(this),
                contentType: false,
                cache: false,
                processData: false,

                success: function (data) {
                    console.log(data);
                    cleanPostArea();
                    updateWall();
                }
            });
        }
    });


    $("#uploadImage").change(function (e) {

        var numOfImages = $("#uploadImage").get(0).files.length;
        var files = Array.from($("#uploadImage").get(0).files);

        for (var i = 0; i < numOfImages; i++) {
            if (!imagesToUploadArray.includes(files[i]))
                imagesToUploadArray.push(files[i]);
        }
        previewPostImages();
    });


});





//invokes the openning of the file choosing window
function addImageButton() {
    $("#uploadImage").click();
}

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

            updateWall();
        }

    });
}

function togglePrivacy(postId) {
    togglePrivacyPath = "/PHPFiles/wallFeedController.php";
    paramsToBack = { function: "togglePostPrivate", postId: postId };

    $.post(togglePrivacyPath, paramsToBack, function (data) {
        updateWall();
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

    likeTogglePath = "/PHPFiles/wallFeedController.php";
    paramsToBack = { function: "toggleLikeForPost", postId: postId };
    $.post(likeTogglePath, paramsToBack, function (data) {
        updateWall();
    });
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

function insertUserNameInSearchResult(usersDic) {//userId, userName) {
    $("#friendsResult").empty();
    for (userId in usersDic) {
        var userName = usersDic[userId];
        var userNameDiv = document.createElement('div');
        userNameDiv.setAttribute('class', "userName u_" + userId);
        userNameDiv.setAttribute('onclick', "addFriendToCurrentUser(" + userId + ", \"" + userName + "\");");
        userNameDiv.innerHTML = userName;
        $("#friendsResult").append(userNameDiv);
    }


}

function emptyFriendsSerachResult() {
    $("#searchBar > input").val("");
    $("#friendsResult").empty();
    $("#AllfriendsResult").empty();
}

//remove friends search result on mouse click
$(document).click(function (event) {
    // event.stopPropagation();
    $target = $(event.target);
    if (!$target.closest('div#searchBar').length || !$target.is('div#searchBar'))
        emptyFriendsSerachResult();

});


function enlargeImage(image) {
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    modal.style.display = "block";

    modalImg.src = image.src;
    captionText.innerHTML = image.alt;

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }
}

function deleteImageFromPost(imageToRemove) {
    var files = imagesToUploadArray;//Array.from($("#uploadImage").get(0).files);
    var numOfImages = imagesToUploadArray.length;//$("#uploadImage").get(0).files.length;

    for (var i = 0; i < numOfImages; i++) {
        var img = files[i];
        var imageName = img.name;
        if (imageName.localeCompare(imageToRemove) == 0) {  //image to remove
            imagesToUploadArray.splice(i, 1);    //delete 1 element in i-th index
            break;
        }
    }

    previewPostImages();


}

checkIfThereAreNotificationsDisplayed = () => {
    return $(".flappyBirdNotification").length;
}

deleteAllNotifications = () => {

    $(".flappyBirdNotification").each(function () {
        var notificationIdNum = $(this).attr('class').match('\\d+')[0];
        deleteNotificationFromDb(notificationIdNum);
    });

    $(".flappyBirdNotification").remove();
    $("#cleanNotificationsBtn").remove();
}

checkForNotifications = () => {

    $.post("/PHPFiles/wallFeedController.php", { function: "getAllNotificationForCurrentUser" },
        function (data) {
            data = JSON.parse(JSON.stringify(data));
            if (data != "empty") {
                for (indx in data) {
                    var notificationsDiv = document.createElement('div');
                    notificationsDiv.setAttribute('class', "flappyBirdNotification fbn_" + indx);
                    notificationsDiv.setAttribute('onclick', 'notificationClicked("' + indx + '");');
                    var notificationMsg = document.createElement('p');
                    notificationMsg.innerHTML = data[indx];
                    notificationsDiv.append(notificationMsg);
                    $("#flappyBirdHeader").prepend(notificationsDiv);
                }
                if (checkIfThereAreNotificationsDisplayed() > 0) {
                    var cleanNotificationP = document.createElement('p');
                    cleanNotificationP.setAttribute('id', 'cleanNotificationsBtn');
                    cleanNotificationP.innerHTML = "Clean All Notifications";
                    cleanNotificationP.setAttribute('onclick', 'deleteAllNotifications(); ');
                    $("#flappyBirdHeader").prepend(cleanNotificationP);
                }
            }
        }, "json");
}



function deleteNotificationFromDb(notificationId) {
    removeNotificationPath = "/PHPFiles/wallFeedController.php";
    paramsToBack = { function: "removeNotification", notificationId: notificationId };

    $.post(removeNotificationPath, paramsToBack, function (data) {
        console.log(data);
    });
}

function notificationClicked(notificationId, isRedirect) {
    $(".fbn_" + notificationId).remove();
    deleteNotificationFromDb(notificationId);
    if (checkIfThereAreNotificationsDisplayed() <= 0)
        $("#cleanNotificationsBtn").remove();


    if (!isRedirect || isRedirect.localeCompare("true"))
        redirectToFlappyBird();

}

function redirectToFlappyBird() {
    emptyFriendsSerachResult();
    window.open(flappyBirdUrl, '_blank');
}

function sendInvitationForFlappy(friendId, friendName) {
    addFriendPath = "/PHPFiles/wallFeedController.php";
    paramsToBack = { function: "inviteFriendToPlayFlappyBird", friendId: friendId };

    $.post(addFriendPath, paramsToBack, function (data) {
        emptyFriendsSerachResult();
        if (data && data === "ok") {
            var userNameDiv = document.createElement('div');
            userNameDiv.setAttribute('id', "addedFriendCongrats");
            var congratsMsg = document.createElement('p');
            congratsMsg.innerHTML = "You have invited " + friendName + "to play flappy bird!";
            userNameDiv.appendChild(congratsMsg);
            $("#AllfriendsResult").append(userNameDiv);

            $("#addedFriendCongrats").fadeOut(5000, function () {
                emptyFriendsSerachResult();
                redirectToFlappyBird();

            });

            updateWall();
        }

    });

}

function searchFriend(stringToSearch) {
    searchFriendPath = "/PHPFiles/wallFeedController.php";

    if (stringToSearch && stringToSearch !== "") {
        paramsToBack = { function: "searchUser", stringToSearch: stringToSearch };
        $.post(searchFriendPath, paramsToBack, function (data) {
            data = JSON.parse(JSON.stringify(data));
            if (data && data !== "false")
                insertUserNameInSearchResult(data);
            else
                emptyFriendsSerachResult();

        }, "json");
    }
    else
        $("#friendsResult").html("");
}


function showAllFriends() {
    $("#AllfriendsResult").empty();
    searchFriendPath = "/PHPFiles/wallFeedController.php";
    paramsToBack = { function: "getAllFriendsOfCurrentUser" };
    $.post(searchFriendPath, paramsToBack, function (data) {
        data = JSON.parse(JSON.stringify(data));
        if (data) {
            for (userId in data) {
                var userName = data[userId];
                var userNameDiv = document.createElement('div');
                userNameDiv.setAttribute('class', "userName u_" + userId);
                userNameDiv.setAttribute('onclick', "sendInvitationForFlappy(" + userId + ", \"" + userName + "\");");
                userNameDiv.innerHTML = userName;
                $("#AllfriendsResult").append(userNameDiv);
            }
        }


    }, "json");
}

