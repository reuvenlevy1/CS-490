<?php
  //Start session
  //session_start();
  //$con = mysqli_connect("sql2.njit.edu","rl265","margery0","rl265","3306");   //move this to separate .php file and include in here later
  error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors' , 1);
  include "dbconfig.php";

//Hardcode student password-----------------------------------------------------------------------------------
  $username = 'student';
  $password = 'password';
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);              //Hashes password to default method, which as of PHP v.5.5.0 is the bcrypt algorith (60 characters); default will change, so keep DB password field at 255 characters to be safe
  $check_query = "SELECT * FROM users WHERE username='$username'";
  $check_query_run = mysqli_query($con, $check_query);

  if(mysqli_num_rows($check_query_run) == 0){                                 //Both username and password must match if true
    $query = "INSERT INTO users values('$username','$hashed_password')";      //query that will insert username and password values into database
    $query_run = mysqli_query($con, $query);
  }

//------------------------------------------------------------------------------------------------------------

//Hardcode professor password---------------------------------------------------------------------------------
  $username = 'professor';
  $password = 'password';
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);              //Hashes password to default method, which as of PHP v.5.5.0 is the bcrypt algorith (60 characters); default will change, so keep DB password field at 255 characters to be safe
  $check_query = "SELECT * FROM users WHERE username='$username'";
  $check_query_run = mysqli_query($con, $check_query);

  if(mysqli_num_rows($check_query_run) == 0){                                 //Both username and password must match if true
    $query = "INSERT INTO users values('$username','$hashed_password')";      //query that will insert username and password values into database
    $query_run = mysqli_query($con, $query);
  }

//----------------------------------------------------------------------------------------------------------

//Input for Login page--------------------------------------------------------------------------------------
  if($_POST['type']=='login'){
    if(isset($_POST['username'], $_POST['password'])){				                  //Runs if 'username' and 'password' was POSTED to this page
      $username = mysqli_real_escape_string($con, $_POST['username']);          //mysqli_real_escape_string(connection,escapestring) function - Recommended to use in industry coding, but not necessary
      $password = mysqli_real_escape_string($con, $_POST['password']);

      $query = "SELECT password FROM users WHERE username='$username'";         //Gets password from specified $username
      $query_run = mysqli_real_query($con, $query);                             //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
      $result_object = mysqli_use_result($con);                                 //Stores data in an object from mysqli_real_query function
      $result = mysqli_fetch_row($result_object);                               //Fetches the data and stores into $result as array. mysqli_fetch_row only grabs the first row of results.
      //echo "password:".$result[0];

      if (password_verify($password, $result[0])){                              //verifies specified $password with the hashed password stored in database. Must use verify_password function for hashed passwords as hashes will change periodically.
        $var = 'Success';
        //echo $var;
        if($_POST['username']=='professor'){
          //echo "here";
          $role = 'professor';
        }
        else {
          $role = 'student';
        }
        $data = array('status'=>$var, 'role'=>$role);
      }
      else{
        $var = 'Failed';
        $data = array('status'=>$var);
      }

      echo json_encode($data);
      mysqli_free_result($result_object);                                       //Frees the memory associated with the result.
      mysqli_close($con);                                                       //Closes database connection
    }
  }

//Input for Creating Questions to be inputted into DB page
  if($_POST['type']=="create_questions"){                                       //Passed from Frontend $data = array ('problem'=>$problem_array, 'difficulty'=>$difficulty_array, 'topic'=>$topic_array, 'test_cases'=>$test_case_array);
    //Change Code Below to enter passed data into DB=====
    //$test_cases = $_POST['test_cases'];
    $passed_array = $_POST;                                                   //The entire contents of associative array passed.
    $key_array = array_keys($passed_array);                                   //Gets all the keys of the array.
    $test_cases = preg_grep('test_case', $key_array);                         //number of elements in array that match test_case

    //have a query to add question Passed

    //$query_insert = "INSERT INTO questions (problem, difficulty, topic) VALUES (".$passed_array['problem']",".$passed_array['difficulty']",".$passed_array['topic']")";

    //Bottom two lines break code, need to find out why!
    //$variable1 = $passed_array['problem'];
    //$query_insert = "VALUES (".$passed_array['problem'].")";
    $query_insert = "INSERT INTO questions (problem, difficulty, topic) VALUES (".$passed_array['problem'].",".$passed_array['difficulty'].",".$passed_array['topic'].")";
    $query_run = mysqli_query($con, $query_insert);

    //have another query and mysqli_fetch_array to take and store the id of the question and place it into the <id_var>
    $query_id="SELECT id FROM `questions` WHERE problem='$problem'";
    $query_run = mysqli_real_query($con, $query_id);                          //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    $result_object = mysqli_use_result($con);                                 //Stores data in an object from mysqli_real_query function
    $result_id = mysqli_fetch_row($result_object);                            //Fetches the data and stores into $result as array. mysqli_fetch_row only grabs the first row of results.


    for($i=1; $i<=$test_cases; $i++){
      //$query_DB = "UPDATE `rl265`.`questions` SET `test_case_$j` = '$test_cases[$i]' WHERE `questions`.`id`=$result_id"; // Updates the newly added question with the test cases passed
      $query_DB = "UPDATE `questions` SET `test_case_".$i."`='".$passed_array["test_case_$i"]."' WHERE `questions`.`id`=$result_id"; // Updates the newly added question with the test cases passed

      //have another query that updates topic of question with specified id --> Don't need b/c 1 question posted at a time
      //have another query that updates difficulty of question with specified id --> Don't need b/c 1 question posted at a time

    }

    mysqli_free_result($result_object);                                       //Frees the memory associated with the result.
    mysqli_close($con);                                                       //Closes database connection
  }

//Input for Creating Exam from Professor's page
  if($_POST['type']=="exam_questions"){
    //Build id------------------------------------------------------------------------------------------
    $query = "SELECT id FROM questions";                                      //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $id_array[] = $row[0];
    }

    /*
    echo "first question: ".$id_array[0];                                 // echo works
    echo " second question: ".$id_array[1];
    echo " third question: ".$id_array[2];
    echo " fourth question: ".$id_array[3];
    echo " fifth question: ".$id_array[4];
    */

    //-------------------------------------------------------------------------------------------------

    //Build Problems Array-----------------------------------------------------------------------------
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.
    $query = "SELECT problem FROM questions";                                 //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $problem_array[] = $row[0];
    }

    /*
    echo "first question: ".$problem_array[0];                            // echo works
    echo " second question: ".$problem_array[1];
    echo " third question: ".$problem_array[2];
    echo " fourth question: ".$problem_array[3];
    echo " fifth question: ".$problem_array[4];
    */

    //-------------------------------------------------------------------------------------------------

    //Build Approved Array-----------------------------------------------------------------------------
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.
    $query = "SELECT approved FROM questions";                                //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $approved_array[] = $row[0];
    }

    /*
    echo "first question: ".$approved_array[0];                                 // echo works
    echo " second question: ".$approved_array[1];
    echo " third question: ".$approved_array[2];
    echo " fourth question: ".$approved_array[3];
    echo " fifth question: ".$approved_array[4];
    */

    //-------------------------------------------------------------------------------------------------

    //Build Difficulty Array---------------------------------------------------------------------------
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.
    $query = "SELECT difficulty FROM questions";                              //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $difficulty_array[] = $row[0];
    }

    /*
    echo "first question: ".$difficulty_array[0];                                 // echo works
    echo " second question: ".$difficulty_array[1];
    echo " third question: ".$difficulty_array[2];
    echo " fourth question: ".$difficulty_array[3];
    echo " fifth question: ".$difficulty_array[4];
    */

    //-------------------------------------------------------------------------------------------------

    //Build Points Array-------------------------------------------------------------------------------
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.
    $query = "SELECT points FROM questions";                                  //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $points_array[] = $row[0];
    }

    /*
    echo "first question: ".$points_array[0];                                 // echo works
    echo " second question: ".$points_array[1];
    echo " third question: ".$points_array[2];
    echo " fourth question: ".$points_array[3];
    echo " fifth question: ".$points_array[4];
    */

    //-------------------------------------------------------------------------------------------------

    //Build Topic Array--------------------------------------------------------------------------------
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.
    $query = "SELECT topic FROM questions";                                   //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $topic_array[] = $row[0];
    }

    /*
    echo "first question: ".$topic_array[0];                                 // echo works
    echo " second question: ".$topic_array[1];
    echo " third question: ".$topic_array[2];
    echo " fourth question: ".$topic_array[3];
    echo " fifth question: ".$topic_array[4];
    */

    //-------------------------------------------------------------------------------------------------

    //Make associative array of arrays
    $data = array ('status'=>$var, 'id'=>$id_array, 'problem'=>$problem_array, 'approved'=>$approved_array, 'difficulty'=>$difficulty_array, 'points'=>$points_array, 'topic'=>$topic_array);
    echo json_encode($data);
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.
    mysqli_close($con);                                                       //Closes database connection
  }

  //Input for Student's Exam
  //Check exam table in DB
?>
