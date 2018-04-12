<?php
  session_start();
  //Input given from Middle-end
  //if(isset($_POST['username'], $_POST['password'])){				//Runs if 'username' and 'password' was POSTED to this page
    //$username = $_POST['username'];
    //$password = $_POST['password'];

    //echo 'post username: '.$_POST['username'];
    //echo 'username variable'.$username;

    // $username = 'barkley';  --> running this in afs "php middle_end.php" successfully added Barkley and password to database.
    // $password = 'password';
     $data = $_POST;
	   //$data = array("username"=>$username, "password"=>$password);  //Associative array
     $string = http_build_query($data);
     //echo 'data: '.$string.PHP_EOL;

     //Sends data from Middle-end to Backend
     $ch1 = curl_init("https://web.njit.edu/~rl265/php/testbackup.php");
     curl_setopt($ch1, CURLOPT_POST, true);
     curl_setopt($ch1, CURLOPT_POSTFIELDS, $string);
     curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
     $answer = curl_exec($ch1);
     curl_close($ch1);

     echo $answer;

     //if(isset($_POST['username'], $_POST['password'])){
       //session_start();
       //$response = 'Everything Worked Okay!';
       //$_SESSION['response'] = $response;
       /*
       $data2 = array("response"=>$response);
       $string2 = http_build_query($data2);

       //Sends data from Middle-end to Backend
       $ch3 = curl_init("https://web.njit.edu/~aem39/test.php");
       curl_setopt($ch3, CURLOPT_POST, true);
       curl_setopt($ch3, CURLOPT_POSTFIELDS, $string2);
       curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
       curl_exec($ch3);
       curl_close($ch3);
     }
    */


/*
//Saving the above $ucid and $password data into postfields
	//MUST HAVE uuid only to log into NJIT webpages, do not use for the rest of the project
$username = $_POST['username'];
$password = $_POST['password'];

$data_njit = array(
  'ucid' => $username,
  'password' => $password,
  'uuid' => "0xACA021"
);


//Start CURL
$ch2 = curl_init();
//Commands for NJIT login
curl_setopt($ch2, CURLOPT_URL, "https://cp4.njit.edu/cp/home/login");
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($data_njit));

//Execute CURL commands above
$result = curl_exec($ch2);

//if the string "Failed" is not seen on the webpage, you've logged in correctly
if(strpos($result, "Failed") == false)
{
	$var = "Login Successful";
}
else
{
	$var = "Login Failed";
}

//End CURL
curl_close($ch2);

//print out whether you're logged in or failed; "PHP_EOL" is for making a new line in PHP and the period is to concatenate
echo $var.PHP_EOL;

  // }
*/
?>
