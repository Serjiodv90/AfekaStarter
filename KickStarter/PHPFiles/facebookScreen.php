<?php

session_start();

include_once 'PostsTable.php';
include_once 'CommentsTable.php';
include_once 'FriendsTable.php';
include_once 'LikesTable.php';
include_once 'PostImagesTable.php';
include_once 'NotificationsTable.php';


$postsDb = new PostsTable();
$msg = $postsDb->createPostsTable();

$commentsDb = new CommentsTable();
$msg2 = $commentsDb->createCommentsTable();

$friendsDb = new FriendsTable();
$msg3 = $friendsDb->createFriendsTable();

$likesDb = new LikesTable();
$msg4 = $likesDb->createLikesTable();

$imagesDb = new PostImagesTable();
$msg5 = $imagesDb->createImagesTable();

$notificationsDb = new NotificationsTable();
$msg6 = $notificationsDb->createNotificationsTable();

ob_clean();



?>

<html>
<title>FaceAfeka</title>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


	<link rel="shortcut icon" type="image/png" href="/pics/facephoto.jpg" />
	<link rel="stylesheet" type="text/css" href="/StyleCss/styling.css">
	<script src="/JS/wallPageScript.js"></script>

</head>

<body id="mainPageBody">

	<div class="mainHeader">
		<div class="logoName">
			<p id="logo"> 
				<img src="/pics/facephoto.jpg"  height="30" alt="FaceAfeka" onclick="updateWall();"> FaceAfeka
			</p>
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
		</div>
	</div>

	<div id="flappyBird">
		<div id="flappyBirdHeader">
			<div id="flappyBirdNotification">
			</div>
			<p> Play Flappy Bird online, with your friends!!!</p>
			<button class="button" type="button" name="inviteFriends" value="Invite a friend" onclick="showAllFriends();">Invite a friend</button>
		</div>

		<div id="AllfriendsResult">
		</div>
	</div>


	<div id="wall">
		<div id="statusField" class="postContainer">
			<form class="addImageFrom" action="#" method="post" enctype="multipart/form-data">
				<div class="statusFieldHead">
					Share your thoughts:
				</div>
				<div class="postTextArea">
					<textarea name="postContent" id="userPostTA" class="userStatusTA" placeholder="What's on your mind, <?php echo ($_SESSION["name"]); ?>?"></textarea>
					<div id="postImagePreview" class="postImagePreview">
					</div>
				</div>


				<div class="postConrollers">
					<button class="button addPicBtn" type="button" name="addPictureBtn" value="Add picture" onclick="addImageButton();">Add picture</button>
					<input id="uploadImage" name="postImage[]" type="file" accept="image/*" multiple hidden>

					<div id="privacyTgl">
						<label style="margin: 0px 10px;">Go private:</label>
						<input name="privacyCheckbox" class="tgl tgl-flip" id="cb5" type="checkbox" />
						<label class="tgl-btn" data-tg-off="Nope" data-tg-on="Yeah!" for="cb5"></label>
					</div>
					<button class="button" type="submit" name="publishPostBtn" value="Publish post">Publish post</button>


				</div>

			</form>


		</div>

		<div id="posts">





			<!-- <div class="singlePost">
				<div class="postHeader">
					<div class="profile">
						<div class="profileImage">
							<img src="/pics/default_profile.jpg" />
						</div>

						<div class="statusFieldHead"> <?php echo ($_SESSION["name"]); ?>
						</div>
					</div>

					<div class="privacyCheckBox">
						<label for="privacyCheckbox">Private </label>
						<input class="privateCheck cb_#" type="checkbox" name="privacyCheckbox">
					</div>
				</div>

				<div class="postTextArea">
					<div class="userStatusTA postContent">new Post hAHa</div>
					<div class="postImage">
						<img src="/pics/postImages/221_images.jpg" onclick="enlargeImage(this);" />
						<img src="/pics/postImages/221_images.jpg" onclick="enlargeImage(this);" />
						<img src="/pics/postImages/221_images.jpg" onclick="enlargeImage(this);" />
						<img src="/pics/postImages/221_images.jpg" onclick="enlargeImage(this);" />
						<img src="/pics/postImages/221_images.jpg" onclick="enlargeImage(this);" />
						<img src="/pics/postImages/221_images.jpg" onclick="enlargeImage(this);" />

					</div>
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
							<div class="profile">
								<div class="profileImage">
									<img src="/pics/default_profile.jpg" />
								</div>
								<div class="commenterName">Serjio said:
								</div>
							</div>

							<div class="commentContent">nice post hadar.
							</div>
						</div>
					</div>

				</div>
			</div> -->







		</div>

	</div>




	<div id="myModal" class="modal">
		<span class="close">&times;</span>
		<img class="modal-content" id="img01">
		<div id="caption"></div>
	</div>
</body>

</html>
<?php
// }
//
?>