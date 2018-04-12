<?php
  session_start();
  // create a session variable ;
  //$_SESSION['user_name'] = "Barkchan";    //Sessions work for file in the same server
  //$FileContents = file_get_contents("https://web.njit.edu/~aem39/session2.php");
   //echo 'Previous value is '.$_SESSION['user_name'].PHP_EOL;
   //echo 'Updated value is '.$FileContents.PHP_EOL;

   /*
   //--------------------------------------------------------------------
   $_SESSION['response_db'] = "0";
   $_SESSION['response_njit'] = "0";
   $FileContents = file_get_contents("https://web.njit.edu/~rl265/php/backend.php");
   echo 'Previous value is '.$_SESSION['response_db'].PHP_EOL;
   echo 'Database Says '.$FileContents.PHP_EOL;
   //get data from backend
   //filecontent1
   $FileContents1 = file_get_contents("https://web.njit.edu/~uk27/middle.php");
   echo 'Previous value is '.$_SESSION['response_db'].PHP_EOL;
   echo 'NJIT SAYS '.$FileContents1.PHP_EOL;
   //get data from njit



   //if(isset($_SESSION['response_njit'])){
     //echo 'NJIT SAYING '.$_SESSION['response_njit'];
   //}
   //if(isset($_SESSION['response_db'])){
    //echo 'DATABASE SAYING '.$_SESSION['response_db'];
   //}
   //--------------------------------------------------------------------
   */



?>

<!DOCTYPE html>                                                     <!-- Tells webpage it's written in modern html (HTML 5)-->

<html lang="en">
  <head>                                                            <!-- contains tags that helps the browser render the page (data here will not be seen) -->
    <meta charset="utf-8">                                          <!-- To render English characters; must be within first 512 characters and before <title> -->
    <title>Frontend Page</title>                                <!-- Shows in browser tabs, bookmarks, search results, etc. -->
	<link rel="stylesheet" href="https://web.njit.edu/~rl265/css/style.css">   <!-- Link to CSS file -->
  </head>

  <body style="background-color:#BFBFBF">
	<div id="name-window">											                      <!-- Division: Can style specific components within this tag separately from others -->
	  <center><h1>Frontend Login Page</h1></center>
	</div>

	<div id="main-window">
	  <!--<form action="config.php" method="POST">-->
    <form action="frontend.php" method="POST">
    <!--<form action="https://web.njit.edu/~uk27/middle.php" method="POST">-->
    <!--<form action="https://web.njit.edu/~aem39/session2.php" method="POST">-->

	    <br><label class="lbls">Username:</label>
	    <input name="username" type="text" class="inputvalues" placeholder="Type in your username" /><br><br>  <!-- The name attribute allows for the input data to be called and maniupulated in the php section -->
	    <label class="lbls">Password:</label>
	    <input name="password" type="password" class="inputvalues" placeholder="Type in your password" /><br><br>
      <center><input name="log_bttn" type="submit" style="background-color:#3498db;" class="bttns" value="LOGIN"/></center><br>
      <a href="https://web.njit.edu/~rl265/"><center><input type="button" style="background-color:Red;" class="bttns" value="BACK"/></center></a><br>
	  </form>

    <?php
      if(isset($_POST['log_bttn'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $data = array("username"=>$username, "password"=>$password);
        $string = http_build_query($data);

        $ch = curl_init("https://web.njit.edu/~rl265/php/middle_end.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($ch);
        curl_close($ch);
        echo json_decode($answer);
      }
	  ?>
	</div>
  </body>
</html>
