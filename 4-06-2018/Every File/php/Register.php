<?php
  //include 'https://web.njit.edu/~rl265/php/config.php';    //Doesn't work for some reason
  //$con = mysqli_connect("128.235.24.119","root","root","registered","3306");
  //$con = mysqli_connect("sql2.njit.edu","rl265","margery0","rl265","3306");
  
?>

<!DOCTYPE html>                                                     <!-- Tells webpage it's written in modern html (HTML 5)-->

<html lang="en">
  <head>                                                            <!-- contains tags that helps the browser render the page (data here will not be seen) -->
    <meta charset="utf-8">                                          <!-- To render English characters; must be within first 512 characters and before <title> -->
    <title>Registration Page</title>                                <!-- Shows in browser tabs, bookmarks, search results, etc. -->
	<link rel="stylesheet" href="https://web.njit.edu/~rl265/css/style.css">   <!-- Link to CSS file -->
  </head>

  <body style="background-color:#BFBFBF">
	<div id="name-window">											                      <!-- Division: Can style specific components within this tag separately from others -->
	  <center><h1>Registration Page</h1></center>
	</div>

	<div id="main-window">
	  <!-- <form action="config.php" method="POST"> -->
    <!-- <form action="backend.php" method="POST"> -->
    <form action="backend.php" method="POST">
	    <br><label class="lbls">Username:</label>
	    <input name="username" type="text" class="inputvalues" placeholder="Type in your username" /><br><br>  <!-- The name attribute allows for the input data to be called and maniupulated in the php section -->
	    <label class="lbls">Password:</label>
	    <input name="password" type="password" class="inputvalues" placeholder="Type in your password" /><br><br>
	    <label class="lbls">Confirm Password:</label>
	    <input name="cpassword" type="password" class="inputvalues" placeholder="Retype in your password" /><br><br>
      <center><input name="reg_bttn" type="submit" style="background-color:#3498db;" class="bttns" value="REGISTER"/></center><br>
      <a href="https://web.njit.edu/~rl265/"><center><input type="button" style="background-color:Red;" class="bttns" value="BACK"/></center></a><br>
	  </form>

    <?php
      if(isset($_POST['reg_bttn'])){
        //$username = $_POST['username'];
        //$password = $_POST['password'];
        
        //$crl = curl_init();
      	//curl_setopt($crl, CURLOPT_URL, "https://web.njit.edu/~rl265/php/config.php");
      	//curl_setopt($crl, CURLOPT_POST, 1);
      	//curl_setopt($crl, CURLOPT_POSTFIELDS, "Username=$username&Password=$password");
      	//curl_setopt($crl, CURLOPT_FOLLOWLOCATION, 1);
          
      	//$c = curl_exec($crl);
      	//curl_close($crl);
       
       /*
        $data = array("username"=>$username, "password"=>$password);
        $string = http_build_query($data);
        
        //$ch = curl_init("https://web.njit.edu/~rl265/php/config.php");
        $ch = curl_init("https://web.njit.edu/~rl265/php/backend.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
        */
        
        //$username = 'rl265';
      	//$password = 'password';
        //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
      	//$check_query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
        //$check_query_run = mysqli_query($con, $query);
      	//if(mysqli_num_columns($check_query_run) == 0){                          //Both username and password must match if true
      	//  $query = "INSERT INTO users values('$username','$hashed_password')";  //query that will insert username and password values into database
     		//  $query_run = mysqli_query($con, $query);
      	//}
        
        
        //$query = "SHOW COLUMNS FROM users";
        //$query_run = mysqli_query($con, $query);
        
        //if (mysqli_connect_errno()){
  				//echo "Failed to connect to MySQL: " . mysqli_connect_error();
  			//}
  			//else{
  				//echo "YOU DID IT!" + $query_run + " hoorah";
          
  			//}
     }
	  ?>
	</div>
  </body>
</html>