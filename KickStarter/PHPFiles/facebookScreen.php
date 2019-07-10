<?php session_start();?>

<html>
<title>FaceAfeka</title>

<head>
<link rel="shortcut icon" type="image/png" href="/pics/facephoto.jpg"/>
<link rel="stylesheet" type="text/css" href="/StyleCss/styling.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


</head>

<body id = "mainPageBody">

	<div class="mainHeader">
		<div class="logoName" >
			<p id="logo"> <img src = "/pics/facephoto.jpg" height="30" alt="London"> FaceAfeka</p>
		</div>

		<div class="loginDisp">
			<div class = "loggedInUser"></div> 
		</div>
		
		<div class = "logOut" >
			<button class = "button" type = "button" name = "logOut" value = "Log Out" onclick = "logOut()">Log out</button>		
		</div>
	</div>


	<div class="container">
	</div>

</body>
</html>
<?php
// }
//?>