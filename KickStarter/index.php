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
	


?>


<html>
<title>Afeka Starter</title>
<head>
<link rel="stylesheet" type="text/css" href="StyleCss/styling.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src = "JS/loginScript.js"/></script>

</head>

<body>

	<div class="mainHeader">
		<div class="logoName">
			<p id="logo">Afeka-Starter</p>
		</div>

		<div class="loginDisp">
			

			<div class="loginForm">
				<form   method = "post" >	<!-- htmlspecialchars help to the url to be less exploitable  -->
					<input class="loginTextBoxes" id = "emailTxt" type = "text" name = "email" placeholder = "Email"/> 
					<input class="loginTextBoxes"  id = "passTxt" type = "password" name = "password" placeholder = "Password" /> 
					<input class="button" id = "loginBtn" type = "button" name = "submit" value = "Login" onclick = "checkLoginData(event)"/> 
					<input type = "submit" class="button" name = "submit" value = "Sign in" />
				</form>

				<div class="loginErrorMsg">
				The Email address or password is incorrect
				</div>
			</div>


		</div>
	</div>


	<!--	<iframe src=""></iframe> -->


	<!--  creation of users table
			CREATE TABLE `afeka-starter`.`users` ( `id` INT NOT NULL AUTO_INCREMENT , `password` VARCHAR NOT NULL , `email` VARCHAR NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM;
		-->

</body>
</html>
<?php
// }
// ?>