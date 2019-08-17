 <?php
	// require_once('includes/class-query.php');
	// require_once('includes/class-insert.php');

	// if(isset($_POST['submit']) && $_POST['submit'] == 'Login')
	// {
	// $update = $query->check_if_user_exist($_POST['email']);
	// $check = $query->check_password($_POST['email'],$_POST['password']);
	// if($update == "No user found" || $check == "No user found")
	// {
	// print_r($update);
	// header('refresh:2.5;url=login.php');
	// }
	// else
	// {
	// $insert->change_login_status($update->ID);
	// header('refresh:0.5;url=feed-view.php');
	// }
	// }
	// else if(isset($_POST['submit']) && $_POST['submit'] == 'Sign up')
	// header('refresh:0.5;url=register.php');
	// else
	// {

	session_start();
	if (isset($_SESSION["logged"])) {
		unset($_SESSION["logged"]);
	}


	?>


 <html>
 <title>FaceAfeka</title>

 <head>
 	<link rel="shortcut icon" type="image/png" href="pics/facephoto.jpg" />
 	<link rel="stylesheet" type="text/css" href="StyleCss/styling.css">
 	<!-- <link rel="stylesheet" type="text/css" href="StyleCss/reg.css"> -->

 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
 	<script type="text/javascript" src="JS/registration.js"></script>
 	<script src="JS/loginScript.js"></script>

 </head>

 <body id="mainPageBody">

 	<div class="mainHeader">
 		<div class="logoName">
 			<p id="logo">FaceAfeka</p>
 		</div>

 		<div class="loginDisp">
 			<div class="loginForm">
 				<form id="loginForm" method="post" action="/PHPFiles/login.php?function=loginVerify" onsubmit="return checkLoginData(event)">
 					<!-- htmlspecialchars help to the url to be less exploitable  -->
 					<input class="loginTextBoxes" id="emailTxt" type="text" name="email" placeholder="Email" />
 					<input class="loginTextBoxes" id="passTxt" type="password" name="password" placeholder="Password" />
 					<input class="button" id="loginBtn" type="submit" name="submit" value="Login" /> <!-- onclick = "checkLoginData(event)"-->
 				</form>

 				<div class="loginErrorMsg">
 					The Email address or password is incorrect
 				</div>
 			</div>

 			<div class="loggedInUser"></div>
 		</div>

 		<!-- <div class = "logOut" >
			<button class = "button" type = "button" name = "logOut" value = "Log Out" onclick = "logOut()">Log out</button>
				
		</div> -->

 	</div>


 	<div class="container">
 		<div class="photobook">&nbsp;</div>
 		<div class="main">
 			<form class="form" method="post" onsubmit="return validateReg()" action="/PHPFiles/registration.php" enctype="multipart/form-data">
 				<h2>Register to FaceAfeka!</h2>
 				<label>Name : </label>
 				<input class="regTextBoxes" type="text" name="name" id="name">
 				<label>Email : </label>
 				<input class="regTextBoxes" type="text" name="email" id="email">
 				<label>Password :</label>
 				<input class="regTextBoxes" type="password" name="password" id="password">
 				<label>Confirm Password :</label>
 				<input class="regTextBoxes" type="password" name="cpassword" id="cpassword">
 				<button class="button addPicBtn" type="button" name="addPictureBtn" value="Add picture" onclick="addImageButton();">Add profile picture</button>
 				<input id="uploadImage" name="profileImage" type="file" accept="image/*" hidden>
 				<input class="button" id="register" type="submit" name="register" value="Register">
 			</form>
 		</div>
	 </div>
	 

	 <div class = "mainDiv">
	
	
	<div class = "buttons">
		<div class="zero">
			<button id-"zeroBtn" name = "zeroBtn" value = "zero">ZERO</button>
		</div>
		
		<div class = "add2">
			<button id-"add2" name = "add2Btn" value = "+2"> +2 </button>
		</div>
	</div>
	
	<div class = "inputs">
		<input type="text" id="value">		
	</div>
	
</div>


 </body>

 </html>
