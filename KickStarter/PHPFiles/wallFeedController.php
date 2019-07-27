<?php

// session_start();
include_once 'dataBaseConstants.php';
include_once 'PostsTable.php';
include_once 'UsersTable.php';
include_once 'CommentsTable.php';
include_once 'FriendsTable.php';
include_once 'LikesTable.php';
include_once 'PostImagesTable.php';
include_once "sessionManager.php";
include_once 'NotificationsTable.php';


$postTableConn = new PostsTable();
$postImagesFolder = "/pics/postImages";

// use different function in the php file
if (isset($_POST["function"]) and $_POST["function"] != "") {
    switch ($_POST["function"]) {
        case 'insertPost':
            insertPost();
            break;
        case 'getAllPostsOfUser':
            getAllPostsOfUser();
            break;
        case 'insertCommentToPostByPostId':
            insertCommentToPostByPostId();
            break;
        case 'searchUser':
            searchUser();
            break;
        case 'addFriendToCurrentUser':
            addFriendToCurrentUser();
            break;
        case 'togglePostPrivate':
            togglePostPrivate();
            break;
        case 'toggleLikeForPost':
            toggleLikeForPost();
            break;
        case 'tempStoreImage':
            tempStoreImage();
            break;
        case 'getAllFriendsOfCurrentUser':
            getAllFriendsOfCurrentUser();
            break;
        case 'inviteFriendToPlayFlappyBird':
            inviteFriendToPlayFlappyBird();
            break;
        case 'getAllNotificationForCurrentUser':
            getAllNotificationForCurrentUser();
            break;
        case 'removeNotification':
            removeNotification();
            break;
    }
}


function inviteFriendToPlayFlappyBird()
{
    $notificationsConn = new NotificationsTable();
    $notificationDesc = "play flappy bird";
    $friendsId = $_POST["friendId"];
    $res = $notificationsConn->addNotificationFromCurrentUserToFriendById($friendsId, $notificationDesc);

    if ($res === TRUE)
        echo ("ok");
    else
        echo ("DB Error");
}

function getAllNotificationForCurrentUser()
{

    $notificationsConn = new NotificationsTable();
    $usersTableConn = new UsersTable();
    $notifications = $notificationsConn->getAllNotificationsForCurrentUser();
    $notificationsArray = array();

    if ($notifications && $notifications->num_rows > 0) {
        while ($row = $notifications->fetch_array()) {
            $userName = $usersTableConn->getUserNameById($row["inviting_user_id"]);
            $notificationDesc = $row["description"];

            if (strcmp($notificationDesc, "play flappy bird") == 0)
                $notificationsArray[$row["id"]] = "$userName has invited you to play flappy bird";
        }
        echo json_encode($notificationsArray);
    } else {
        echo json_encode("empty");
    }
}

function removeNotification() {
    echo ("removing notification: " . $_POST["notificationId"]);
    $notificationsConn = new NotificationsTable();
    $notificationsConn->removeNotificationById($_POST["notificationId"]);

}

function getAllFriendsOfCurrentUser()
{
    $usersTableConn = new UsersTable();
    $friendsArray = $usersTableConn->getAllUserFriendsByUserId(getCurrentUserId());
    echo json_encode($friendsArray);
}

function insertPost()
{
    $postContent = $_POST["postContent"];
    $isPrivate = $_POST["isPrivate"];

    global $postTableConn;
    $isPrivate = (strcasecmp($isPrivate, 'true') == 0 ? 1 : 0);
    echo ($postTableConn->inserPost(getCurrentUserId(), $postContent, 0, $isPrivate));
}

function insertCommentToPostByPostId()
{
    $postId = $_POST["postId"];
    $userId = getCurrentUserId();
    $commentContent = $_POST["commentContent"];

    $commentsTableConn = new CommentsTable();
    echo ($commentsTableConn->inserComment($userId, $postId, $commentContent));
}

function searchUser()
{
    $stringToSearch = $_POST["stringToSearch"];
    $usersTableConn = new UsersTable();
    $resultArray = null;

    if ($stringToSearch === "*")
        $resultArray = $usersTableConn->getAllUserName();
    else
        $resultArray = $usersTableConn->getUserNameBySubstring($stringToSearch);

    if (!empty($resultArray))
        echo json_encode($resultArray);
    else
        echo json_encode("false");
}

function addFriendToCurrentUser()
{
    $friendsId = $_POST["friendsId"];
    if ($friendsId !== $_SESSION["id"]) {
        $friendsTableConn = new FriendsTable();
        $msg = $friendsTableConn->insertFriendOfCurrentUser($friendsId);
        if ($msg === "ok")
            echo ("ok");
        elseif ($msg === "friendship exists") {
            echo ($msg);
        }
    } else
        echo ("same user");
}

function togglePostPrivate()
{
    global $postTableConn;
    $postId = $_POST["postId"];
    $postTableConn->togglePostPrivacy($postId);
}

function toggleLikeForPost()
{
    global $postTableConn;
    $likesTable = new LikesTable();
    $postId = $_POST["postId"];
    $isLiked = $likesTable->toggleLikeForCurrentUserToPostByPostId($postId);
    if ($isLiked === "true") {
        $postTableConn->addLikeToPostById($postId, 1);
    } elseif ($isLiked === "false") {
        $postTableConn->addLikeToPostById($postId, -1);
    }
}

function tempStoreImage()
{
    global $postImagesFolder;
    echo ("in tempStoreImage");
    echo ("formData: " . $_POST["images"]);
    echo $_FILES["postImage"]["error"];

    if (isset($_FILES["postImage"]) && $_FILES["postImage"]["error"] == 0) {
        $allowedEx = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "git" => "image/gif", "png" => "image/png");
        $fileName = $_FILES["postImage"]["name"];   //get the full name of the file including the extenssion
        $fileType = $_FILES["postImage"]["type"];
        $fileSize = $_FILES["postImage"]["size"];

        echo "File Name: " . $_FILES["photo"]["name"] . "<br>";
        echo "File Type: " . $_FILES["photo"]["type"] . "<br>";
        echo "File Size: " . ($_FILES["photo"]["size"] / 1024) . " KB<br>";
        echo "Stored in: " . $_FILES["photo"]["tmp_name"];

        //verify extenssion correct
        $ext = pathinfo($fileName, PATHINFO_EXTENSION); //extract the file extension
        if (!array_key_exists($ext, $allowedEx))
            die("Wrong image format");

        //maximize the file size to 5Mb
        $maxSize = 5 * 1024 * 1024;
        if ($fileSize > $maxSize)
            die("The file is too big");

        //verify mime type of the file
        if (in_array($fileType, $allowedEx)) {
            $fileName = getCurrentUserId() . '_' . $fileName;
            //check if the file exists before uploading it
            if (file_exists($postImagesFolder . $fileName))
                echo ("Image exists already");
            else {
                move_uploaded_file($_FILES["photo"]["tmp_name"], $postImagesFolder . $fileName);
                echo ("file was uploaded: $postImagesFolder $fileName");
            }
        } else
            echo ("MIME is not good");
    }
}

function getAllPostsOfUser()
{
    //find all friends of user, and select post by friends' id's
    global $postTableConn;
    $usersTableConn = new UsersTable();
    $commentsTableConn = new CommentsTable();
    $likesTable = new LikesTable();
    $imagesTable = new PostImagesTable();

    $result = $postTableConn->getAllpostsOfUserByDate(getCurrentUserId());
    $numOfRows = $result->num_rows;


    $dom = new DOMDocument('1.0', "utf-8");
    $count = 0;
    if ($numOfRows > 0) {
        while ($row = $result->fetch_array()) {
            $postID = $row["id"];

            //wrapping div of the whole post
            $postDiv = $dom->createElement('div');
            $postDivClass = $dom->createAttribute('class');
            $postDivClass->value = "singlePost post_$postID";
            $postDiv->appendChild($postDivClass);

            //publisher's name, according to DB
            $userName = $usersTableConn->getUserNameById($row["user_id"]);
            $PostHeaderDiv = $dom->createElement('div');
            $PostHeaderDivClass = $dom->createAttribute('class');
            $PostHeaderDivClass->value = "postHeader";
            $PostHeaderDiv->appendChild($PostHeaderDivClass);

            $profileDiv = $dom->createElement('div');
            $profileDivClass = $dom->createAttribute('class');
            $profileDivClass->value = "profile";
            $profileDiv->appendChild($profileDivClass);
            $profileImageDiv = $dom->createElement('div');
            $profileImageDivClass = $dom->createAttribute('class');
            $profileImageDivClass->value = "profileImage";
            $profileImageDiv->appendChild($profileImageDivClass);
            $profileImageElement = $dom->createElement('img');
            $profileImageElementSrc = $dom->createAttribute('src');
            $imagePath = $usersTableConn->getProfileImagePathByUserId($row["user_id"]);
            if($imagePath && $imagePath !== "")
                $profileImageElementSrc->value = $imagePath;
            else 
                $profileImageElementSrc->value = "/pics/default_profile.jpg";
            
            $profileImageElement->appendChild($profileImageElementSrc);
            $profileImageDiv->appendChild($profileImageElement);
            $profileDiv->appendChild($profileImageDiv);
            
            $statusDiv = $dom->createElement('div', $userName);
            $statusDivClass = $dom->createAttribute('class');
            $statusDivClass->value = "statusFieldHead";
            $statusDiv->appendChild($statusDivClass);
            $profileDiv->appendChild($statusDiv);

            $PostHeaderDiv->appendChild($profileDiv);
            //display checkbox only if the current user is the post owner
            if (strcmp($row["user_id"], getCurrentUserId()) === 0) {
                //privacy checkbox div
                $privacyCheckBoxDiv = $dom->createElement('div');
                $privacyCheckBoxDivClass = $dom->createAttribute('class');
                $privacyCheckBoxDivClass->value = "privacyCheckBox";
                $privacyCheckBoxDiv->appendChild($privacyCheckBoxDivClass);
                $privateLabel = $dom->createElement('label', "Private");
                $privateLabelFor = $dom->createAttribute('for');
                $privateLabelFor->value = "privacyCheckbox";
                $privateLabel->appendChild($privateLabelFor);
                $privacyCheckBoxDiv->appendChild($privateLabel);
                $privateCheckBox = $dom->createElement('input');
                $privateCheckBoxClass = $dom->createAttribute('class');
                $privateCheckBoxClass->value = "privateCheck cb_$postID";
                $privateCheckBox->appendChild($privateCheckBoxClass);
                $privateCheckBoxType = $dom->createAttribute('type');
                $privateCheckBoxType->value = "checkbox";
                $privateCheckBox->appendChild($privateCheckBoxType);
                $privateCheckBoxName = $dom->createAttribute('name');
                $privateCheckBoxName->value = "privacyCheckbox";
                $privateCheckBox->appendChild($privateCheckBoxName);
                $privateCheckBoxFunc = $dom->createAttribute('onchange');
                $privateCheckBoxFunc->value = "togglePrivacy($postID);";
                $privateCheckBox->appendChild($privateCheckBoxFunc);
                //check if current post of the current user and its privacy
                if ($row["private"] == 1) {
                    $checkBoxChecked = $dom->createAttribute('checked');
                    $privateCheckBox->appendChild($checkBoxChecked);
                }
                $privacyCheckBoxDiv->appendChild($privateCheckBox);
                $PostHeaderDiv->appendChild($privacyCheckBoxDiv);
            }

            $postDiv->appendChild($PostHeaderDiv);

            //the post content, from DB
            $postContent = $row["post_content"];
            $postContentDiv = $dom->createElement('div');
            $postContentDivClass = $dom->createAttribute('class');
            $postContentDivClass->value = "postTextArea";
            $postContentDiv->appendChild($postContentDivClass);
            if ($postContent != "") {
                $postFieldDiv = $dom->createElement('div', $postContent);
                $postFieldDivClass = $dom->createAttribute('class');
                $postFieldDivClass->value = "userStatusTA postContent";
                $postFieldDiv->appendChild($postFieldDivClass);
                $postContentDiv->appendChild($postFieldDiv);
            }
            //post images
            if ($row["num_of_images"] > 0) {
                $imagesRes = $imagesTable->getImagesForPostByPostId($postID);

                $postImagesDiv = $dom->createElement('div');
                $postImagesDivClass = $dom->createAttribute('class');
                $postImagesDivClass->value = "postImage";
                $postImagesDiv->appendChild($postImagesDivClass);

                while ($imageRow = $imagesRes->fetch_array()) {
                    $imgEl = $dom->createElement('img');
                    $imgElSrc = $dom->createAttribute('src');
                    $imgElSrc->value = $imageRow["image_name"];
                    $imgEl->appendChild($imgElSrc);
                    $imgElClickFunc = $dom->createAttribute('onclick');
                    $imgElClickFunc->value = "enlargeImage(this);";
                    $imgEl->appendChild($imgElClickFunc);
                    $postImagesDiv->appendChild($imgEl);
                }
                $postContentDiv->appendChild($postImagesDiv);
            }



            $postDiv->appendChild($postContentDiv);

            //like button's div
            $controllersDiv = $dom->createElement('div');
            $controllersDivClass = $dom->createAttribute('class');
            $controllersDivClass->value = "postConrollers";
            $controllersDiv->appendChild($controllersDivClass);
            $likeBtnDiv = $dom->createElement('div');
            $likeBtnDivClass = $dom->createAttribute('class');
            $likeBtnDivClass->value = "likeBtn like_$postID";
            $likeBtnDiv->appendChild($likeBtnDivClass);
            $likeBtnFunc = $dom->createAttribute('onclick');
            $likeBtnFunc->value = "like($postID);";
            $likeBtnDiv->appendChild($likeBtnFunc);
            $likeIcon = $dom->createElement('img');
            $likeIconClass = $dom->createAttribute('class');
            $likeIconClass->value = "likeImg";
            $likeIcon->appendChild($likeIconClass);
            $likeIconSrc = $dom->createAttribute('src');

            //check weather the user liked this post
            if ($likesTable->checkCurrentUserLikePostByPostId($postID) === TRUE)
                $likeIconSrc->value = "/pics/liked.png";
            else
                $likeIconSrc->value = "/pics/like.png";
            $likeIcon->appendChild($likeIconSrc);
            $likeBtnDiv->appendChild($likeIcon);
            $likeTextDiv = $dom->createElement('div', "Like");
            $likeTextDivClass = $dom->createAttribute('class');
            $likeTextDivClass->value = "likeDiv";
            $likeTextDiv->appendChild($likeTextDivClass);
            $likeBtnDiv->appendChild($likeTextDiv);
            $controllersDiv->appendChild($likeBtnDiv);

            //amount of likes
            $numOfLikeForPost = $row["num_of_likes"];
            if ($numOfLikeForPost > 0) {
                $numOfLikesDiv = $dom->createElement('div');
                $numOfLikesDivClass = $dom->createAttribute('class');
                $numOfLikesDivClass->value = "numOfLikesDiv";
                $numOfLikesDiv->appendChild($numOfLikesDivClass);

                $numOfLikesLabel = $dom->createElement('label', "Likes: $numOfLikeForPost");
                $numOfLikesLabelClass = $dom->createAttribute('class');
                $numOfLikesLabelClass->value = "nolLabel nol_$postID";
                $numOfLikesLabel->appendChild($numOfLikesLabelClass);
                $numOfLikesDiv->appendChild($numOfLikesLabel);

                $controllersDiv->appendChild($numOfLikesDiv);
            }

            $postDiv->appendChild($controllersDiv);

            //the whole post comments area
            $commentsDiv = $dom->createElement('div');
            $commentsDivClass = $dom->createAttribute('class');
            $commentsDivClass->value = "commentsArea";
            $commentsDiv->appendChild($commentsDivClass);
            //current user's comment area
            $userCommentDiv = $dom->createElement('div');
            $userCommentDivClass = $dom->createAttribute('class');
            $userCommentDivClass->value = "userComment";
            $userCommentDiv->appendChild($userCommentDivClass);
            $textArea = $dom->createElement('textarea');
            $textAreaClass = $dom->createAttribute('class');
            $textAreaClass->value = "userStatusTA ta_$postID";
            $textArea->appendChild($textAreaClass);
            $textAreaPlaceHolder = $dom->createAttribute('placeholder');
            $textAreaPlaceHolder->value = "Write here your comment";
            $textArea->appendChild($textAreaPlaceHolder);
            $userCommentDiv->appendChild($textArea);
            $commentBtnDiv = $dom->createElement('div', "comment");
            $commentBtnDivClass = $dom->createAttribute('class');
            $commentBtnDivClass->value = "commentBtn likeDiv comBtn_$postID";
            $commentBtnDiv->appendChild($commentBtnDivClass);
            $commentBtnDivFunc = $dom->createAttribute('onclick');
            $commentBtnDivFunc->value = "addComment($postID);";
            $commentBtnDiv->appendChild($commentBtnDivFunc);
            $userCommentDiv->appendChild($commentBtnDiv);
            $commentsDiv->appendChild($userCommentDiv);
            //the rest of the comments for the post
            $allCommentsDiv = $dom->createElement('div');
            $allCommentsDivClass = $dom->createAttribute('class');
            $allCommentsDivClass->value = "postComments";
            $allCommentsDiv->appendChild($allCommentsDivClass);

            //add comments to post, by postId
            $commentsForPost = $commentsTableConn->getAllCommentsOfPostByPostIdByDate($postID);
            if ($commentsForPost->num_rows > 0) {

                while ($row = $commentsForPost->fetch_array()) {

                    $commenterName = $usersTableConn->getUserNameById($row["user_id"]);
                    $commentContent = $row["comment_content"];

                    $singleCommentDiv = $dom->createElement('div');
                    $singleCommentDivClass = $dom->createAttribute('class');
                    $singleCommentDivClass->value = "singleComment";
                    $singleCommentDiv->appendChild($singleCommentDivClass);

                    $profileDiv = $dom->createElement('div');
                    $profileDivClass = $dom->createAttribute('class');
                    $profileDivClass->value = "profile";
                    $profileDiv->appendChild($profileDivClass);
                    $profileImageDiv = $dom->createElement('div');
                    $profileImageDivClass = $dom->createAttribute('class');
                    $profileImageDivClass->value = "profileImage";
                    $profileImageDiv->appendChild($profileImageDivClass);
                    $profileImageElement = $dom->createElement('img');
                    $profileImageElementSrc = $dom->createAttribute('src');
                    $imagePath = $usersTableConn->getProfileImagePathByUserId($row["user_id"]);
                    if($imagePath && $imagePath !== "")
                        $profileImageElementSrc->value = $imagePath;
                    else 
                        $profileImageElementSrc->value = "/pics/default_profile.jpg";
                    
                    $profileImageElement->appendChild($profileImageElementSrc);
                    $profileImageDiv->appendChild($profileImageElement);
                    $profileDiv->appendChild($profileImageDiv);

                    $commenterNameDiv = $dom->createElement('div', $commenterName . " said:");
                    $commenterNameDivClass = $dom->createAttribute('class');
                    $commenterNameDivClass->value = "commenterName";
                    $commenterNameDiv->appendChild($commenterNameDivClass);
                    $profileDiv->appendChild($commenterNameDiv);
                    $singleCommentDiv->appendChild($profileDiv);
                    
                    $commentContentDiv = $dom->createElement('div', $commentContent);
                    $commentContentDivClass = $dom->createAttribute('class');
                    $commentContentDivClass->value = "commentContent";
                    $commentContentDiv->appendChild($commentContentDivClass);
                    $singleCommentDiv->appendChild($commentContentDiv);

                    $allCommentsDiv->appendChild($singleCommentDiv);
                }
            }

            $commentsDiv->appendChild($allCommentsDiv);
            $postDiv->appendChild($commentsDiv);
            $dom->appendChild($postDiv);
        }
    }
    echo ($dom->saveHTML());
}
