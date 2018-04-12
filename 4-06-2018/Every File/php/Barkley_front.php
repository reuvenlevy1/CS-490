<!DOCTYPE html>
<?php
session_start();
function login($UCID, $password){

	$UCID=$_POST["UCID"];
	$password=$_POST["password"];

	$data = array(
		'UCID' => $UCID,
		'password' => $password,
		);

	$ch = curl_init();


	//curl_setopt($ch, CURLOPT_URL, "https://web.njit.edu/~bx6/cs490/index.php");
	/// "https://web.njit.edu/~bx6/cs490/index.php");
	/// "https://web.njit.edu/~ddp34/CS490/login.php");
  curl_setopt($ch, CURLOPT_URL, "https://web.njit.edu/~uk27/barkley_middle.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

	$result = curl_exec($ch);
	///echo $result;
	curl_close($ch);

	$obj = json_decode($result,false);
	echo $obj->user->UserType;
	//->user->UserType;
	//['user'][0]['Last'];

	$_SESSION['user'] = $obj->user->UCID;
	if(strpos($result, "202") == true){
		echo "Password or username is wrong";
	}
	if ($obj->user->UserType == "INSTRUCTOR") {
		if(isset($_SESSION['user']))
		header('Location: teacherExamView.php');
	}
	else if ($obj->user->UserType == "STUDENT") {
		if (isset($_SESSION['user']))
		{
			header('Location: studentExamView.php');
		}
	}
}
?>


<html>

<head>
<div id= 'title'>
	<title>login page</title>
	<link rel="stylesheet" type="text/css" href="assets/css/indexStyle.css">
</div>
</head>

<body>
	<link href="https://fonts.googleapis.com/css?family=Sofia" rel="stylesheet">
	<h1>Secure Access Login</h1>

	<div>
		<form action="Barkley_front.php" method="POST">
			<?php
			$username = $_POST['UCID'];
			$password = $_POST['password'];
				 ?>
			<label for="UCID"><h3>UCID</h3></label>
			<input type="text" placeholder="Enter your UCID here" id=UCID
			autofocus=on autocomplete=on required name="UCID" align="center">

			<br>

			<label for="password"><h3>Password</h3></label>
			<input type=password placeholder="Enter you password here" id=password
			autocomplete=off required name="password" align="center"
			>
			<br>
			<input type="submit" name = 'submit' value="Login">
			<?php
			if (isset($_POST['submit']))
			{
				echo login($username, $password);
			}
			?>
		</form>
	</div>
	<br>
<!-- 	<span>or <a href="register.php">register here</a></span>
-->
</body>

</html>
