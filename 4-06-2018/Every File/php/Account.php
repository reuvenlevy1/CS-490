<?php
  session_start();													//Allows for use with $_SESSION - built in global variable
?>

<!DOCTYPE html>                                                         <!-- Tells webpage it's written in modern html (HTML 5)-->

<html lang="en">
  <head>                                                                <!-- contains tags that helps the browser render the page (data here will not be seen) -->
    <meta charset="utf-8">                                              <!-- To render English characters; must be within first 512 characters and before <title> -->
    <title>Account Page</title>                                         <!-- Shows in browser tabs, bookmarks, search results, etc. -->
	<link rel="stylesheet" href="https://web.njit.edu/~rl265/css/style.css">   <!-- Link to CSS file -->
  </head>

  <body style="background-color:#BFBFBF">
	<div id="name-window">											                          <!-- Division: Can style specific components within this tag separately from others -->
	  <center><h1>Welcome to the Account Page</h1></center>
	</div>
	
	<div id="main-window">
	  <br><center><h2>You have finally made it </h2></center>
	  <?php echo $_SESSION['username'];?>
	  
    <form action="Account.php" method="POST">
      <!-- <a href="https://web.njit.edu/~rl265/"><center><input name="logout_bttn" type="button" style="background-color:Red;" class="bttns" value="LOGOUT"/></center></a><br> -->
      <center><input name="logout_bttn" type="button" style="background-color:Red;" class="bttns" value="LOGOUT"/></center><br>
    </form>
    
    <?php
      if(isset($_POST['logout_bttn'])){
        session_destroy();                                            //Closes session_start() function where user is logged into all webpages
        header('Location: https://web.njit.edu/~rl265/');
      }
    ?>
	</div>
  </body>
</html>