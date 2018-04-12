<?php
  //include 'https://web.njit.edu/~rl265/php/dbconfig.php'; //--> Doesn't work, need to somehow specify the directory path.
  $con = mysqli_connect("sql2.njit.edu","rl265","margery0","rl265","3306"); //Connect to database: (Hostname, Username, Password, DBname, Port)
  
  //echo 'Made it to Backend!';
  //Hardcoded - creates a custom password for the username that has been passed
  $username = 'rl265'; //Use UCID given in assignment
  $custom_password = 'password123';
  $hashed_password = password_hash($custom_password, PASSWORD_DEFAULT);	//hashes password
  $check_query = "SELECT * FROM users WHERE username='$username'";// AND password='$hashed_password'";
  $check_query_run = mysqli_query($check_query);
  //echo mysqli_num_rows($check_query_run).PHP_EOL;
  echo 'mysqli_num_rows: '.mysqli_num_rows($check_query_run);
  if(mysqli_num_rows($check_query_run) == 0){                             //If username or password have not been entered yet
    echo 'in if ';
    $query = "INSERT INTO users VALUES('$username','$hashed_password')";  //query that will insert username and password values into database
    $query_run = mysqli_query($con, $query);
  }
  
    
  /*
  //Input given from Middle-end
  if(isset($_POST['username'], $_POST['password'])){				//Runs if 'username' and 'password' was POSTED to this page
     $username = $con->real_escape_string($_POST['username']);		//Another way to use mysqli_real_escape_string(connection,escapestring) function - Recommended to use in industry coding, but not necessary
     $password = $con->real_escape_string($_POST['password']);
     //echo 'username: '.$username.' password: '.$password.PHP_EOL;
     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
     $query = "INSERT INTO users VALUES('$username','$hashed_password')";
     $con->query($query);											//Pointer way of using mysqli_query(connection,query) function
     //echo "username: ".$username." password: ".$password;			//Testing output and variable values
 
   }
   
   */
   

  
  /*
  //if in this loop, this will send a response to middle-end to confirm if correct.   
  $request = 'Passed!';
  $data = array("request"=>$request);
  $string = http_build_query($data);
  
  $ch = curl_init("https://web.njit.edu/~uk27/middle.php");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_exec($ch);
  curl_close($ch);
  */


?>