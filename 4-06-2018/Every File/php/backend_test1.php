<?php
  //Build an associative array, Json_encode() it and echo/return the array to frontend_test1.php

//Input given from frontend_test1.php-------------------------------------------------------------------------------
  //if(isset($_POST['username'], $_POST['password'])){				                  //Runs if 'username' and 'password' was POSTED to this page


  if($_POST['type'] == '0'){
    login();
  }
  elseif($_POST['type'] == '1'){
    echo 'type == 1'
  }
  else {
    echo 'neither';
  }

  function login(){
    //evenetually, build an array of all the questions and try to pass this as the value for an associative array.

    $data = array ("key_name" => array("tom", "an", "bob"), "key_age" => array("1", "10", "12"));

    echo json_encode($data);    //json encode the array to turn it into a string (passable format)
  }
?>
