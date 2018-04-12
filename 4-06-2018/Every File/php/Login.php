<?php
  //require 'https://web.njit.edu/~rl265/php/config.php';    //Doesn't work for some reason
  $con = mysqli_connect("128.235.24.119","root","root","registered","3306");
?>

<!DOCTYPE html>                                                     <!-- Tells webpage it's written in modern html (HTML 5)-->

<html lang="en">
  <head>                                                            <!-- contains tags that helps the browser render the page (data here will not be seen) -->
    <meta charset="utf-8">                                          <!-- To render English characters; must be within first 512 characters and before <title> -->
    <title>Login Page</title>                                       <!-- Shows in browser tabs, bookmarks, search results, etc. -->
	<link rel="stylesheet" href="https://web.njit.edu/~rl265/css/style.css">   <!-- Link to CSS file -->
  </head>

  <body style="background-color:#BFBFBF">
	<div id="name-window">											                      <!-- Division: Can style specific components within this tag separately from others -->
	  <center><h1>Login Page</h1></center>
	</div>
	
	<div id="main-window">
	  <form action="Login.php" method="POST">
	    <br><label class="lbls">Username:</label>
	    <input name=username type="text" class="inputvalues" placeholder="Type in your username" required/><br><br>
	    <label class="lbls">Password:</label>
	    <input name=password type="password" class="inputvalues" placeholder="Type in your password" required/><br><br>
      <center><input name="log_bttn" type="submit" style="background-color:#27ae60;" class="bttns" value="LOGIN"/></center><br>
	    <a href="https://web.njit.edu/~rl265/"><center><input type="button" style="background-color:Red;" class="bttns" value="BACK"/></center></a><br>
	  </form>
     
    <?php
		  if(isset($_POST['log_bttn'])){
			  //echo '<script type="text/javascript"> alert("Not Functioning Yet")</script>';
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $query_run = mysqli_query($con, $query);
        
        if(mysqli_num_rows($query_run) > 0){                          //Both username and password must match if true
          echo '<script type="text/javascript"> alert("Congratulations! You have successfully logged in!")</script>';
          $_SESSION['username'] = $username;                          //$_SESSION is a built in global variable that keeps the username lasting throughout the session of the browser, meaning the user will be logged in throughout the session of the browser and can be accessed throughout all the linked webpages
          header('location: https://web.njit.edu/~rl265/php/Account.php');        //"header" function redirects you
        }
        else{
          echo '<script type="text/javascript"> alert("Username or Password may be incorrect. Please try again.")</script>';
        }
		  }
	  ?>
	</div>
  </body>
</html>