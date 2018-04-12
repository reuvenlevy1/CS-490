<?php
//For testing login to an NJIT page with your own frontend and NJIT backend
//If you haven't made your frontend yet, you can run this in the terminal with "php login.php"

//Grabbing data from frontend input
//$post = json_decode(trim(file_get_contents("php://input")), true);
//Change the ucid and password below to your real NJIT ucid and password to login successfully; Must be in string form
//$ucid = 'fail';
//$password = 'test';
//Uncomment below if you have a working frontend posting data; and remove the above $ucid and $password

//$ucid = $post['UCID'];
$ucid = $_POST['username'];
$password = $_POST['password'];
echo 'ucid: '.$ucid.PHP_EOL;


//Saving the above $ucid and $password data into postfields
$data = array(
	'user' => $ucid,
	'pass' => $password,
	//MUST HAVE uuid only to log into NJIT webpages. uuid is the same and not unique.
	'uuid' => "0xACA021",
	);

//Start CURL
$ch = curl_init();
//Commands for NJIT login
curl_setopt($ch, CURLOPT_URL, "https://cp4.njit.edu/cp/home/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

//Execute CURL commands above
$result = curl_exec($ch);

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
curl_close($ch);

//print out whether you're logged in or failed; "PHP_EOL" is for making a new line in PHP and the period is to concatenate
echo $var.PHP_EOL;

?>