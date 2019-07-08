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
<title>FaceAfeka</title>

<head>
<link rel="stylesheet" type="text/css" href="StyleCss/styling.css">
<!-- <link rel="stylesheet" type="text/css" href="StyleCss/reg.css"> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="JS/registration.js"></script>
<script src = "JS/loginScript.js"></script>

</head>

<body id = "mainPageBody">

	<div class="mainHeader">
		<div class="logoName">
			<p id="logo">FaceAfeka</p>
		</div>

		<div class="loginDisp">
			<div class="loginForm">
				<form  id = "loginForm" method = "post" >	<!-- htmlspecialchars help to the url to be less exploitable  -->
					<input class="loginTextBoxes" id = "emailTxt" type = "text" name = "email" placeholder = "Email"/> 
					<input class="loginTextBoxes"  id = "passTxt" type = "password" name = "password" placeholder = "Password" /> 
					<input class="button" id = "loginBtn" type = "button" name = "submit" value = "Login" onclick = "checkLoginData(event)"/> 
				</form>

				<div class="loginErrorMsg">
				The Email address or password is incorrect
				</div>
			</div>

			<div class = "loggedInUser"></div> 
		</div>
		
		<div class = "logOut" >
			<button class = "button" type = "button" name = "logOut" value = "Log Out" onclick = "logOut()">Log out</button>
				
		</div>

	</div>


	<div class="container">
		<div class = "photobook">&nbsp;</div>
        <div class="main">
            <form class="form" method="post" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <h2>Register to Afeka-Starter!</h2>
                <label>Name : </label>  
                <input class = "regTextBoxes" type="text" name="dname" id="name">
                <label>Email : </label> 
                <input class = "regTextBoxes" type="text" name="demail" id="email">
                <label>Password :</label>
                <input class = "regTextBoxes" type="password" name="password" id="password">
                <label>Confirm Password :</label>
                <input class = "regTextBoxes" type="password" name="cpassword" id="cpassword">
                <input  class="button" id = "register" type="button" name="register" value="Register">
			</form>
		</div>
	</div>

</body>
</html>
<?php
// }
//?>