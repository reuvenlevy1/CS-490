<?php
  //Start session
  //session_start();
  //$con = mysqli_connect("sql2.njit.edu","rl265","margery0","rl265","3306");   //move this to separate .php file and include in here later
  include "dbconfig.php";

//Hardcode fake password for backend----------------------------------------------------------------------
  $username = 'rl265';
  $password = 'password';
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);              //Hashes password to default method, which as of PHP v.5.5.0 is the bcrypt algorith (60 characters); default will change, so keep DB password field at 255 characters to be safe
  $check_query = "SELECT * FROM users WHERE username='$username'";
  $check_query_run = mysqli_query($con, $check_query);

  if(mysqli_num_rows($check_query_run) == 0){                                 //Both username and password must match if true
    $query = "INSERT INTO users values('$username','$hashed_password')";      //query that will insert username and password values into database
    $query_run = mysqli_query($con, $query);
  }

//----------------------------------------------------------------------------------------------------------

//Input given from Middle-end-------------------------------------------------------------------------------
  if(isset($_POST['username'], $_POST['password'])){				                  //Runs if 'username' and 'password' was POSTED to this page
    $username = mysqli_real_escape_string($con, $_POST['username']);          //mysqli_real_escape_string(connection,escapestring) function - Recommended to use in industry coding, but not necessary
    $password = mysqli_real_escape_string($con, $_POST['password']);
    //echo 'username: '.$username.' password: '.$password.PHP_EOL;
    //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
    //$query = "INSERT INTO users VALUES('$username','$hashed_password')";
    //$query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
    $query = "SELECT password FROM users WHERE username='$username'";         //Gets password from specied $username
    //$query_run = mysqli_query($con, $query);                        //Runs query in database
    $query_run = mysqli_real_query($con, $query);                             //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    $result_object = mysqli_use_result($con);                                 //Stores data in an object from mysqli_real_query function
    $result = mysqli_fetch_row($result_object);                               //Fetches the data and stores into $result as array
    //echo 'result: '.$result[0].PHP_EOL;                             //Worked - Prints the hashed password in DB

    if (password_verify($password, $result[0])) {                             //verifies specified $password with the hashed password stored in database. Must use verify_password function for hashed passwords as hashes will change periodically.
      $var = 'Login Successful Backend!';
    }
    else{
      $var = 'Login Failed Backend!';
    }
    echo json_encode($var);
    mysqli_free_result($result_object);                                     //Frees the memory associated with the result.
  }
?>
