<?php

session_start();

include_once 'PostsTable.php';
include_once 'CommentsTable.php';
include_once 'FriendsTable.php';

$postsDb = new PostsTable();
$msg = $postsDb->createPostsTable();

$commentsDb = new CommentsTable();
$msg2 = $commentsDb->createCommentsTable();

$friendsDb = new FriendsTable();
$msg3 = $friendsDb->createFriendsTable();
ob_clean();



?>

<html>
<title>FaceAfeka</title>

<head>
	<link rel="shortcut icon" type="image/png" href="/pics/facephoto.jpg" />
	<link rel="stylesheet" type="text/css" href="/StyleCss/styling.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="/JS/wallPageScript.js"></script>
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


</head>

<body id="mainPageBody">

	<div class="mainHeader">
		<div class="logoName">
			<p id="logo"> <img src="/pics/facephoto.jpg" height="30" alt="London"> FaceAfeka</p>
		</div>

		<div class="loginDisp">
			<div class="loggedInUser">
				<div>Hello <?php echo ($_SESSION["name"]); ?></div>
			</div>

			<div class="logOut">
				<button class="button" type="button" name="logOut" value="Log Out" onclick="logOut()">Log out</button>
			</div>

		</div>
	</div>

	<div id="friendSearch">
		<div id="searchBar">
			<input type="text" placeholder="Search for a friend.." onkeyup="searchFriend(this.value);">
		</div>

		<div id="friendsResult">
			<!-- <div class="userName">
				Hadar Pur
			</div> -->
		</div>
	</div>


	<div id="wall">
		<div id="statusField" class="postContainer">
			<div class="statusFieldHead">
				Share your thoughts:
			</div>
			<div class="postTextArea">
				<textarea id="userPostTA" class="userStatusTA" placeholder="What's on your mind, <?php echo ($_SESSION["name"]); ?>?"></textarea>
			</div>


			<div class="postConrollers">
				<button class="button addPicBtn" type="button" name="addPictureBtn" value="Add picture">Add picture</button>
				<div id="privacyTgl">
					<label style="margin: 0px 10px;">Go private:</label>
					<input class="tgl tgl-flip" id="cb5" type="checkbox" />
					<label class="tgl-btn" data-tg-off="Nope" data-tg-on="Yeah!" for="cb5"></label>
				</div>
				<button class="button" type="button" name="publishPostBtn" value="Publish post" onclick="savePost(this);">Publish post</button>

			</div>

		</div>

		<div id="posts">
			<!-- <div class="singlePost">
				<div class="postHeader">
					<div class="statusFieldHead"> <?php echo ($_SESSION["name"]); ?>
					</div>

					<div class="privacyCheckBox">
						<label for="privacyCheckbox">Private </label>
						<input class="privateCheck cb_#" type="checkbox" name="privacyCheckbox">
					</div>
				</div>

				<div class="postTextArea">
					<div class="userStatusTA postContent">new Post hAHa</div>
				</div>

				<div class="postConrollers">
					<div class="likeBtn" onclick="like();">
						<img class="likeImg" src="/pics/like.png" />
						<div class="likeDiv" style="height: 15px; width:40px;">Like</div>
					</div>

					<div class="numOfLikesDiv">
						<label class="nolLabel nol_#">Likes: ##</label>
					</div>
				</div>

				<div class="commentsArea">
					<div class="userComment">
						<textarea class="userStatusTA" placeholder="Write here your comment"></textarea>
						<div class="commentBtn likeDiv">Comment</div>
					</div>

					<div class="postComments">
						<div class="singleComment">
							<div class="commenterName">Serjio said:
							</div>

							<div class="commentContent">nice post hadar.
							</div>
						</div>
					</div>

				</div>



			</div> -->

		</div>

	</div>


</body>

</html>
<?php
// }
//
?>