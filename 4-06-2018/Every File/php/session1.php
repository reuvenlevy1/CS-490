<?php
 //include 'https://web.njit.edu/~aem39/session2.php';
 $FileContents = file_get_contents("https://web.njit.edu/~aem39/session2.php");
 echo 'var: '.$FileContents.PHP_EOL;
  
 //session_start();
// create a session variable ;
//$_SESSION['user_name'] = "Barkchan";
?>