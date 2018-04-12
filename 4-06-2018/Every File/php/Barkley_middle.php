<?php
//Start session
session_start();

//Grabbing data from frontend
$post = json_decode(trim(file_get_contents("php://input")), true);
$ucid = $post['UCID'];
$password = $post['password'];

//Saving data into postfields
$db_data = array(
	'UCID' => $ucid,
	'password' => $password,
);

//Database header
header('Content-Type: application/json');

//Start CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://web.njit.edu/~ddp34/CS490/login.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($db_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',));

//Execute CURL
$result = curl_exec($ch);

//Echo database query to frontend
//echo json_encode($result);
echo $result;
/*
echo json_encode($result);
$response = json_decode($result, false);
echo '             The usertype is '.$response->user->UserType.'             ';
*/

//End CURL
curl_close($ch);

?>
