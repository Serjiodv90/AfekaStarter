<?php

session_start();
include_once 'dataBaseConstants.php';
include_once 'PostsTable.php';

$postTableConn = new PostsTable();

// use different function in the php file
if (isset($_POST["function"]) and $_POST["function"] != "") {
    switch ($_POST["function"]) {
        case 'insertPost':
            insertPost();
            break;
        case 'isLogged':
            isLogged();
            break;
        case 'logOut':
            logOut();
            break;
        case 'redirectToWallPage':
            redirectToWallPage();
            break;
    }
}


function insertPost()
{
    $postContent = $_POST["postContent"];
    $isPrivate = $_POST["isPrivate"];

    global $postTableConn;
    $isPrivate = (strcasecmp($isPrivate, 'true') == 0 ? 1 : 0);
    echo($postTableConn->inserPost($_SESSION["id"], $postContent, 0, $isPrivate));

}

function getAllPostsOfUser() 
{
    //find all friends of user, and select post by friends' id's
    global $postTableConn;
    $result = $postTableConn->getAllpostsOfUserByDate($_SESSION["id"]);
    $numOfRows = $result->num_rows;
    $postsCollection = array();

    if($numOfRows > 0)
    {
        while($row = $result->fetch_array())
        {
            $htmlPostElement= 
            <<<EOT
                <div class="singlePost">
                    <div class="statusFieldHead"> <?php echo ($_SESSION['name']); ?>
                    </div>

                    <div class="postTextArea">
                        <div class="userStatusTA postContent">new Post hAHa</div>
                    </div>

                    <div class="postConrollers">
                        <div class="likeBtn" onclick="like();">
                            <img class="likeImg" src="/pics/like.png"/>	
                            <div class="likeDiv" style="height: 15px; width:40px;">Like</div>
                        </div>
                    </div>

                    <div class="commentsArea">
                        <div class="userComment">
                            <textarea class="userStatusTA"  placeholder="Write here your comment"></textarea>
                            <div class="commentBtn likeDiv">Comment</div>
                        </div>

                        <div class="postComments">
                        </div>

                    </div>
                </div>
EOT;
            
            
        }
    }
    

}
