
updateWall = () => {

    $.post("/PHPFiles/wallFeedController.php", { function: "getAllPostsOfUser" },
        function (data) {
            $("#posts").empty();
            $("#posts").append(data);
        });
};

imageIsLoaded = (img) => {
    console.log(img);
    var postInsertImageDiv = document.createElement('div');
    postInsertImageDiv.setAttribute('class', "postInsertImage");
    var postImage = document.createElement('img');
    postImage.setAttribute('class', "closablePostImage");
    postImage.setAttribute('src', img.target.result);
    postImage.setAttribute('onclick', "enlargeImage(this);");
    postInsertImageDiv.append(postImage);
    var closeIcon = document.createElement('i');
    closeIcon.setAttribute('class', "icon fa fa-close");
    closeIcon.setAttribute('onclick', "deleteImageFromPost(" + postImage + ");");
    postInsertImageDiv.append(closeIcon);

    
    $("#postImagePreview").append(postInsertImageDiv);
};

cleanPostArea = () => {
    $("#cb5").prop('checked', false); // Unchecks it
    $("#userPostTA").val("");
    $("#uploadImage").val("");
    $("#postImage").empty();
};

$(document).ready(function (event) {

    updateWall();

    $(".addImageFrom").on('submit', function (event) {
        event.preventDefault();
        postTextAreaEl = $("#userPostTA");
        content = postTextAreaEl.val();
        var numOfImages = $("#uploadImage").get(0).files.length;

        if ((content && content !== "") || numOfImages > 0) {
            $.ajax({
                url: "/PHPFiles/PostUpload.php",
                type: "POST",
                data: new FormData(this),
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
        console.log(this.files);
        var numOfImages = $("#uploadImage").get(0).files.length;

        // $("#message").empty(); // To remove the previous error message
        var files = $("#uploadImage").get(0).files;
        // var file = this.files[0];

        for (var i = 0; i < numOfImages; i++) {
            var img = files[i];

            var imageType = img.type;
            var match = ["image/jpeg", "image/png", "image/jpg", "image/gif"];

            if (!match.includes(imageType)) {
                // $('#previewing').attr('src', 'noimage.png');
                // $("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                // return false;
                alert("wrong image type: " + imageType);
            }
            else {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(img);
            }
        }
    });
});






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
    // $("#friendsResult").html(data);
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
}

//remove friends search result on mouse click
$(document).click(function (event) {
    // event.stopPropagation();
    $target = $(event.target);
    if (!$target.closest('div#searchBar').length || !$target.is('div#searchBar'))
        emptyFriendsSerachResult();

});

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

// function addImageToPost(event) {
//     event.preventDefault();
//     addImagePath = "/PHPFiles/wallFeedController.php";
//     form = $(".addImageFrom");
//     formData = new FormData(form);

//     paramsToBack = {function: "tempStoreImage", data: formData};
//     $.post(addImagePath, paramsToBack, function (data) {
//         console.log(data);
//     });

//     return false;
// }

function enlargeImage(image) {
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    modal.style.display = "block";
    console.log(this.src);

    modalImg.src = image.src;
    captionText.innerHTML = image.alt;

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }
}

function deleteImageFromPost(img) {
    console.log(img);
}
