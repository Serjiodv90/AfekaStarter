<?php

session_start();
include_once 'dataBaseConstants.php';
include_once 'PostsTable.php';
include_once 'UsersTable.php';
include_once 'CommentsTable.php';
include_once 'FriendsTable.php';

$postTableConn = new PostsTable();

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
    }
}

function getCurrentUserId()
{
    return $_SESSION["id"];
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

function getAllPostsOfUser()
{
    //find all friends of user, and select post by friends' id's
    global $postTableConn;
    $usersTableConn = new UsersTable();
    $commentsTableConn = new CommentsTable();

    $result = $postTableConn->getAllpostsOfUserByDate(getCurrentUserId());
    $numOfRows = $result->num_rows;

    $dom = new DOMDocument('1.0', "utf-8");

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

            $statusDiv = $dom->createElement('div', $userName);
            $statusDivClass = $dom->createAttribute('class');
            $statusDivClass->value = "statusFieldHead";
            $statusDiv->appendChild($statusDivClass);
            $PostHeaderDiv->appendChild($statusDiv);
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
            $postFieldDiv = $dom->createElement('div', $postContent);
            $postFieldDivClass = $dom->createAttribute('class');
            $postFieldDivClass->value = "userStatusTA postContent";
            $postFieldDiv->appendChild($postFieldDivClass);
            $postContentDiv->appendChild($postFieldDiv);
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
            $likeIconSrc->value = "/pics/like.png";
            $likeIcon->appendChild($likeIconSrc);
            $likeBtnDiv->appendChild($likeIcon);
            $likeTextDiv = $dom->createElement('div', "Like");
            $likeTextDivClass = $dom->createAttribute('class');
            $likeTextDivClass->value = "likeDiv";
            $likeTextDiv->appendChild($likeTextDivClass);
            $likeBtnDiv->appendChild($likeTextDiv);
            $controllersDiv->appendChild($likeBtnDiv);
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
                    $commenterNameDiv = $dom->createElement('div', $commenterName . " said:");
                    $commenterNameDivClass = $dom->createAttribute('class');
                    $commenterNameDivClass->value = "commenterName";
                    $commenterNameDiv->appendChild($commenterNameDivClass);
                    $singleCommentDiv->appendChild($commenterNameDiv);
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
