<?php
  // error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	// ini_set('display_errors' , 1);
  include "dbconfig.php";

//Hardcode student password---------------------------------------------------
  $username = 'student';
  $password = 'password';
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);              //Hashes password to default method, which as of PHP v.5.5.0 is the bcrypt algorith (60 characters); default will change, so keep DB password field at 255 characters to be safe
  $check_query = "SELECT * FROM users WHERE username='$username'";
  $check_query_run = mysqli_query($con, $check_query);

  if(mysqli_num_rows($check_query_run) == 0){                                 //Both username and password must match if true
    $query = "INSERT INTO users (username, password) VALUES ('$username','$hashed_password')";      //query that will insert username and password values into database
    $query_run = mysqli_query($con, $query);
  }

//==============================================================================

//Hardcode professor password-------------------------------------------------
  $username = 'professor';
  $password = 'password';
  $role = 'p';
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);              //Hashes password to default method, which as of PHP v.5.5.0 is the bcrypt algorith (60 characters); default will change, so keep DB password field at 255 characters to be safe
  $check_query = "SELECT * FROM users WHERE username='$username'";
  $check_query_run = mysqli_query($con, $check_query);

  if(mysqli_num_rows($check_query_run) == 0){                                 //Both username and password must match if true
    $query = "INSERT INTO users (username, password, role) VALUES ('$username','$hashed_password', '$role')";      //query that will insert username and password values into database
    $query_run = mysqli_query($con, $query);
  }

//==============================================================================

//Input for Login page--------------------------------------------------------//Receiving $data = array("type"=>"login", "username"=>$username, "password"=>$password);  if($_POST['type']=='login'){
  if($_POST['type']=='login'){
    $username = mysqli_real_escape_string($con, $_POST['username']);          //mysqli_real_escape_string(connection,escapestring) function - Recommended to use in industry coding, but not necessary
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $query = "SELECT password FROM users WHERE username='$username'";         //Gets password from specified $username
    $query_run = mysqli_real_query($con, $query);                             //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    $result_object = mysqli_use_result($con);                                 //Stores data in an object from mysqli_real_query function
    $result = mysqli_fetch_row($result_object);                               //Fetches the data and stores into $result as array. mysqli_fetch_row only grabs the first row of results.
    mysqli_free_result($result_object);                                       //Frees the memory associated with the result.

    if (password_verify($password, $result[0])){                              //verifies specified $password with the hashed password stored in database. Must use verify_password function for hashed passwords as hashes will change periodically.
      $var = 'Success';
      if($_POST['username']=='professor'){
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
    mysqli_close($con);                                                       //Closes database connection
  }
//==============================================================================

//Input for Creating Questions to be inputted into DB page--------------------
  if($_POST['type']=='create_questions'){                                     //1 question passed from Frontend $data = array ('problem'=>$problem, 'difficulty'=>$difficulty, 'points'=>$points, 'topic'=>$topic, 'test_case_1'=>$test_case_1,..., 'test_case_5=>'...');
    $passed_array = $_POST;                                                   //The entire contents of associative array passed.
    $key_array = array_keys($passed_array);                                   //Gets all the keys of the array.
    $test_cases = preg_grep('/test_case/', $key_array);                       //an array of elements in the passed array that matches the string 'test_case'; need the string to match within a starting and ending symbol --> works
    $test_cases_size = sizeof($test_cases);

    //have a query to add question Passed
    $query_insert = "INSERT INTO questions (problem, difficulty, points, topic) VALUES ('".$passed_array['problem']."', '".$passed_array['difficulty']."', '".$passed_array['points']."', '".$passed_array['topic']."')"; //Need to concatenate (. operator) strings ("" markers) with PHP variables or else it will break program
    $query_run = mysqli_query($con, $query_insert) or die(mysqli_error($con));

    //have another query and mysqli_fetch_array to take and store the id of the question and place it into the <id_var>
    $query_id="SELECT id FROM `questions` WHERE problem='".$passed_array['problem']."'";
    $query_run = mysqli_real_query($con, $query_id) or die(mysqli_error($con));//Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    $result_object = mysqli_use_result($con);                                 //Stores data in an object from mysqli_real_query function
    $result_id = mysqli_fetch_row($result_object);                            //Fetches the data and stores into $result as array. mysqli_fetch_row only grabs the first row of results.
    mysqli_free_result($result_object);                                       //Frees the memory associated with the result; If you don't free the result, you can't insert or use any data manipulation queries.

    for($i=1; $i<=$test_cases_size; $i++){
      $query_DB = "UPDATE `questions` SET `test_case_".$i."`='".$passed_array["test_case_$i"]."' WHERE `questions`.`id`=".$result_id[0]; // Updates the newly added question with the test cases passed
      echo $query_DB;
      $query_run = mysqli_query($con, $query_DB) or die(mysqli_error($con));
    }

    mysqli_close($con);                                                       //Closes database connection
  }
//==============================================================================

//Input for Creating Exam from Professor's page (creating content for question bank)//Receiving $data = array("type"=>"exam_questions","delete_question_id"=>$delete_question_id);
  if($_POST['type']=='exam_questions'){                                       //Building array to build the choosing of exam questions
    if(isset($_POST['delete_question_id'])){
        $delete_question_id = $_POST['delete_question_id'];
        if(!empty($delete_question_id)){
          //Deletes Questions By ID
          for($i=0; $i<sizeof($delete_question_id); $i++){
            $query = "DELETE FROM questions WHERE id IN (".$delete_question_id[$i].")";    //Deletes multiple questions by id
            $query_run = mysqli_query($con, $query);                          //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
          }

          //set autoindex back to last question id
          $query = "SELECT MAX(id) FROM questions";
          $query_run = mysqli_query($con, $query);
          $max_id = mysqli_fetch_array($query_run);                           //An array of the exam_id last created
          mysqli_free_result($query_run);

          $query = "ALTER TABLE questions AUTO_INCREMENT=".$max_id[0];
          $query_run = mysqli_query($con, $query);

        }
    }

    //Build ID Array
    $query = "SELECT id FROM questions";                                      //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $id_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Problems Array
    $query = "SELECT problem FROM questions";                                 //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $problem_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Approved Array
    $query = "SELECT approved FROM questions";                                //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $approved_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Difficulty Array
    $query = "SELECT difficulty FROM questions";                              //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $difficulty_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Points Array
    $query = "SELECT points FROM questions";                                  //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $points_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Topic Array
    $query = "SELECT topic FROM questions";                                   //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $topic_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Make associative array of arrays
    $data = array ('id'=>$id_array, 'problem'=>$problem_array, 'approved'=>$approved_array, 'difficulty'=>$difficulty_array, 'points'=>$points_array, 'topic'=>$topic_array);
    echo json_encode($data);
    mysqli_close($con);                                                       //Closes database connection
  }
//==============================================================================

//Input for Created Exam from Professor's page--------------------------------//Receiving $data = array("type"=>"exam_created", "id"=>$id_string, "question_points"=>$points_string);
  if($_POST['type']=='exam_created'){                                         //Chosen questions for exam
    $query = "UPDATE questions SET approved='n'";                             //Resets all questions approved value to 'n'
    $query_run = mysqli_query($con, $query) or die(mysqli_error($con));

    $query = "UPDATE questions SET points='0'";                               //Resets all questions points value to '0'
    $query_run = mysqli_query($con, $query);

    $id_string = $_POST['id'];                                                //questions id will contain comma separated id values in a string
    $points_string = $_POST['question_points'];                               //questions points will contain comma separated point values in a string

    //Take question information and input it into exam table
    $id_string_size = sizeof($id_string);
    for($i=0; $i<$id_string_size; $i++){
      $query = "UPDATE questions SET approved='y' WHERE id IN (".$id_string[$i].")";
      $query_run = mysqli_query($con, $query);
      $query_2 = "UPDATE questions SET points=".$points_string[$i]." WHERE id IN (".$id_string[$i].")";
      $query_run_2 = mysqli_query($con, $query_2);
    }


    $query = "INSERT INTO exams (start_time) VALUES ('".date("Y-m-d h:i:sa")."')"; //Insert start time for exam, which will auto create an unique id
    $query_run = mysqli_query($con, $query) or die(mysqli_error($con));

  }

//==============================================================================

//Input for Student's Exam from student's page--------------------------------//Receiving $data = array("type"=>"student_exam");
  if($_POST['type']=='student_exam'){                                         //Creates exam questions and related info to Student's page
    //Build ID Array
    $query = "SELECT id FROM questions WHERE approved='y'";                   //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $id_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Problems Array
    $query = "SELECT problem FROM questions WHERE approved='y'";              //Gets all data from questions table where problems are approved
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $problem_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Difficulty Array
    $query = "SELECT difficulty FROM questions WHERE approved='y'";           //Gets all data from questions table where difficulty are approved
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $difficulty_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Points Array
    $query = "SELECT points FROM questions WHERE approved='y'";               //Gets all data from questions table where points are approved
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $points_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Topic Array
    $query = "SELECT topic FROM questions WHERE approved='y'";                //Gets all data from questions table where topic are approved
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $topic_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Test_Case_1 Array
    $query = "SELECT test_case_1 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_1_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_2 Array
    $query = "SELECT test_case_2 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_2_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_3 Array
    $query = "SELECT test_case_3 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_3_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_4 Array
    $query = "SELECT test_case_4 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_4_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_5 Array
    $query = "SELECT test_case_5 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_5_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Make associative array of arrays
    $data = array ('question_id'=>$id_array,'problem'=>$problem_array,'difficulty'=>$difficulty_array,'points'=>$points_array,'topic'=>$topic_array,'test_case_1'=>$test_case_1_array,'test_case_2'=>$test_case_2_array,'test_case_3'=>$test_case_3_array,'test_case_4'=>$test_case_4_array,'test_case_5'=>$test_case_5_array);
    echo json_encode($data);
    mysqli_close($con);                                                       //Closes database connection
  }
//==============================================================================

//When student finishes exam
//Should pass through middle-end (python_test.php) and get graded and $data array should be added to with the test_case_1-5_answers and other info: $data = array ('type'=>'student_answers','username'=>$_POST['username'],'question_id'=>$_POST['question_id'],'answers'=>$student_code_array,'original_student_code'=>$original_student_code,'test_case_1_answer'=>$input_data['test_cases_answer_array'][0],'test_case_2_answer'=>$input_data['test_cases_answer_array'][1],'test_case_3_answer'=>$input_data['test_cases_answer_array'][2],'test_case_4_answer'=>$input_data['test_cases_answer_array'][3],'test_case_5_answer'=>$input_data['test_cases_answer_array'][4],'points'=>$points,'reduction_function'=>$reduction_function,'reduction_statement'=>$reduction_statement,'student_function_array'=>$student_function_array,'student_statement_array'=>$student_statement_array,'question_grade'=>$question_grade,'exam_grade'=>$exam_grade);

//Input for Student's Exam----------------------------------------------------//Receiving from frontend $data = array('type'=>'student_answers','username'=>$username,'question_id'=>$id,'answers'=>$answers,'test_case_1'=>$test_case_1,'test_case_2'=>$test_case_2,'test_case_3'=>$test_case_3,'test_case_4'=>$test_case_4,'test_case_5'=>$test_case_5,'points'=>$points);
//$username is the name of the student's username; This should match the id's in the "exam" table. $answers is the student's answers to those questions. Indexes should match up.
  if($_POST['type']=='student_answers'){                                      //Creates a new table named after username storing answers to each question
    //get all the information needed to fill "answers" table:
    $username = $_POST['username'];
    $question_id = $_POST['question_id'];
    $student_code = $_POST['answers'];
    $original_student_code = $_POST['original_student_code'];
    $test_case_1_answer = $_POST['test_case_1_answer'];
    $test_case_2_answer = $_POST['test_case_2_answer'];
    $test_case_3_answer = $_POST['test_case_3_answer'];
    $test_case_4_answer = $_POST['test_case_4_answer'];
    $test_case_5_answer = $_POST['test_case_5_answer'];
    $points = $_POST['points'];                                               //scaling factor --> (#ofCorrectTestCases)/(total#ofTestCases) --> is multiplied by "$question_points" in "python_test.php" for "$question_grade"
    $reduction_function = $_POST['reduction_function'];
    $reduction_statement = $_POST['reduction_statement'];
    $student_function = $_POST['student_function_array'];
    $student_statement = $_POST['student_statement_array'];
    $question_grade = $_POST['question_grade'];
    $exam_grade = $_POST['exam_grade'];                                       //total exam grade for 1 student --> goes into "exam_grades" table in DB

    //In for loop below, the exam_id autoincrements for every INSERT, so need to include an exam_id into the INSERT
    $query = "SELECT MAX(id) FROM exams";
    $query_run = mysqli_query($con, $query);
    $last_exam_created = mysqli_fetch_array($query_run);                      //An array of the exam_id last created
    mysqli_free_result($query_run);

    for($i=0; $i<sizeof($question_id); $i++){
      $query = "INSERT INTO answers (username, exam_id, question_id, original_student_code, student_code, test_case_1_answer, test_case_2_answer, test_case_3_answer, test_case_4_answer, test_case_5_answer, points, reduction_function, reduction_statement, student_function, student_statement, question_grade) VALUES ('$username','$last_exam_created[0]','$question_id[$i]', '$original_student_code[$i]', '$student_code[$i]', '$test_case_1_answer[$i]', '$test_case_2_answer[$i]', '$test_case_3_answer[$i]', '$test_case_4_answer[$i]', '$test_case_5_answer[$i]', '$points[$i]', '$reduction_function[$i]', '$reduction_statement[$i]', '$student_function[$i]', '$student_statement[$i]', $question_grade[$i])";
      $query_run = mysqli_query($con, $query);
    }
    $query = "INSERT INTO exam_grades (exam_id, username, exam_grade) VALUES ('$last_exam_created[0]','$username','$exam_grade')";
    $query_run = mysqli_query($con, $query);

  }
//==============================================================================

//Input for Professor's Release Exam page-------------------------------------//Receiving $data = array('type'=>'release_exam');
  if($_POST['type']=='release_exam'){                                         //This will be used by frontend to populate a grading page where the Professor can update scores per question in "questions" table
      //use queries to build arrays for all values in the "answers" table and echo back an associative array of arrays

      //Build Username Variable
      //select username from answers table where exam_id in exams table matches exam_id in answers table
      $query = "SELECT username FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      $username_array = mysqli_fetch_array($query_run);
      $username = $username_array[0];
      mysqli_free_result($query_run);

      //Build Exam_ID Variable
      $query = "SELECT exam_id FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      $exam_id_array = mysqli_fetch_array($query_run);
      $exam_id = $exam_id_array[0];
      mysqli_free_result($query_run);

      //Build Question_ID Array
      $query = "SELECT question_id FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $question_id_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Original_Student_Code Array
      $query = "SELECT original_student_code FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $original_student_code_array[] = $row[0];
      }
      mysqli_free_result($query_run);                                         //Frees the memory associated with the last result.

      //Build Student_Code Array
      $query = "SELECT student_code FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $student_code_array[] = $row[0];
      }
      mysqli_free_result($query_run);                                         //Frees the memory associated with the last result.

      //Build Test_Case_1_Answer Array
      $query = "SELECT test_case_1_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_1_answer_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_2_Answer Array
      $query = "SELECT test_case_2_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_2_answer_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_3_Answer Array
      $query = "SELECT test_case_3_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_3_answer_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_4_Answer Array
      $query = "SELECT test_case_4_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_4_answer_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_5_Answer Array
      $query = "SELECT test_case_5_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_5_answer_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Question Points Array
      $query = "SELECT points FROM questions WHERE questions.approved='y'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $question_points_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Reduction Function Array
      $query = "SELECT reduction_function FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $reduction_function_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Reduction Statement Array
      $query = "SELECT reduction_statement FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $reduction_statement_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Student Function Array
      $query = "SELECT student_function FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $student_function_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Student Statement Array
      $query = "SELECT student_statement FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $student_statement_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_1 Array
      $query = "SELECT test_case_1 FROM questions WHERE approved='y'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_1_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_2 Array
      $query = "SELECT test_case_2 FROM questions WHERE approved='y'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_2_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_3 Array
      $query = "SELECT test_case_3 FROM questions WHERE approved='y'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_3_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_4 Array
      $query = "SELECT test_case_4 FROM questions WHERE approved='y'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_4_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Test_Case_5 Array
      $query = "SELECT test_case_5 FROM questions WHERE approved='y'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $test_case_5_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Problems Array
      $query = "SELECT problem FROM questions WHERE approved='y'";            //Gets all data from questions table
      $query_run = mysqli_query($con, $query);                                //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
      while($row = mysqli_fetch_array($query_run))
      {
        $problem_array[] = $row[0];
      }
      mysqli_free_result($query_run);                                         //Frees the memory associated with the last result.

      //Build Question Grade Array
      $query = "SELECT question_grade FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $question_grade_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Topic Array
      $query = "SELECT topic FROM questions WHERE questions.approved='y'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $topic_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Build Difficulty Array
      $query = "SELECT difficulty FROM questions WHERE questions.approved='y'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run))
      {
        $difficulty_array[] = $row[0];
      }
      mysqli_free_result($query_run);

      //Make associative array of arrays
      $data = array('username'=>$username,'exam_id'=>$exam_id,'question_id'=>$question_id_array,'original_student_code_array'=>$original_student_code_array,'student_code'=>$student_code_array,'test_case_1_answer'=>$test_case_1_answer_array,'test_case_2_answer'=>$test_case_2_answer_array,'test_case_3_answer'=>$test_case_3_answer_array,'test_case_4_answer'=>$test_case_4_answer_array,'test_case_5_answer'=>$test_case_5_answer_array,'question_points'=>$question_points_array,'reduction_function'=>$reduction_function_array,'reduction_statement'=>$reduction_statement_array,'student_function'=>$student_function_array,'student_statement'=>$student_statement_array,'test_case_1'=>$test_case_1_array,'test_case_2'=>$test_case_2_array,'test_case_3'=>$test_case_3_array,'test_case_4'=>$test_case_4_array,'test_case_5'=>$test_case_5_array,'problem'=>$problem_array,'question_grade'=>$question_grade_array,'topic'=>$topic_array,'difficulty'=>$difficulty_array);
      echo json_encode($data);
      mysqli_close($con);                                                     //Closes database connection

  }
//==============================================================================

//Input from Professor's Release Exam Page after points have been adjusted and comments have been made----//Receiving $data = array('type'=>'points_update','username'=>$username,'exam_id'=>$exam_id,'question_grade'=>$points,'comments'=>$comments); where each variable is an array
  if($_POST['type']=='points_update'){                                        //Updates points for 1 question
    //will receive array of question_id and points per question, which will be updated in 'answers' table
    //To get a unique question, will need to use the username, exam id and question id to identify.
//array('type'=>'points_update','username'=>$username,'exam_id'=>$exam_id,'question_id'=>$question_id,'points'=>$points);
    $username = $_POST['username'];
    $exam_id = $_POST['exam_id'];

    //Build Question_ID Array
    $query = "SELECT question_id FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $question_id_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    if(isset($_POST['question_grade'])){
      $question_grade = $_POST['question_grade'];
      for($i=0; $i<sizeof($question_grade); $i++){
        if(preg_match('/^[0-9]/', $question_grade[$i])){
          //Update points for the matched username, exam_id and question_id;
          for($j=0; $j<sizeof($question_id_array); $j++){
            $query = "UPDATE answers SET question_grade='$question_grade[$j]' WHERE username='$username' AND exam_id='$exam_id' AND question_id='$question_id_array[$j]'";
            $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
          }
        }
      }
    }

    if(isset($_POST['comments'])){
      $comments = $_POST['comments'];
      if(!preg_match('/^\s/', $comments)){                                    //If it starts with a space
        for($i=0; $i<sizeof($question_id_array); $i++){                       //Comments for 1 exam and 1 student
          $query = "UPDATE exam_grades SET comments='$comments' WHERE exam_id='$exam_id' AND username='$username'";
          $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
        }
      }
      else{
        $query = "UPDATE exam_grades SET comments='No Comments.' WHERE exam_id='$exam_id' AND username='$username'";
        $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
      }
    }

    //Make query that changes the "viewable" column to 'y' for the exam_id that matches within the 'view_results' table.
    $query = "INSERT INTO view_results (exam_id,viewable) VALUES ($exam_id,'y')";
    $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
    mysqli_close($con);                                                       //Closes database connection
  }
//==============================================================================

//Input from Student's Page to view results                                   //Receiving $data = array('type'=>'view_results');
    if($_POST['type']=='view_results'){                                       //Sends full results of username, questions, student's code, answers, and scores per question

    //query to view_results "exam_id" and "viewable" and store into a variable.

    //Build Username Variable
    //select username from answers table where exam_id in exams table matches exam_id in answers table
    $query = "SELECT username FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    $username_array = mysqli_fetch_array($query_run);
    $username = $username_array[0];
    mysqli_free_result($query_run);

    //Build Exam_ID Variable
    $query = "SELECT exam_id FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    $exam_id_array = mysqli_fetch_array($query_run);
    $exam_id = $exam_id_array[0];
    mysqli_free_result($query_run);

    //Build Question_ID Array
    $query = "SELECT question_id FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $question_id_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Original_Student_Code Array
    $query = "SELECT original_student_code FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $original_student_code_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Student_Code Array
    $query = "SELECT student_code FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $student_code_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Test_Case_1_Answer Array
    $query = "SELECT test_case_1_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_1_answer_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_2_Answer Array
    $query = "SELECT test_case_2_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_2_answer_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_3_Answer Array
    $query = "SELECT test_case_3_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_3_answer_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_4_Answer Array
    $query = "SELECT test_case_4_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_4_answer_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_5_Answer Array
    $query = "SELECT test_case_5_answer FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_5_answer_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Question Points Array
    $query = "SELECT points FROM questions WHERE questions.approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $question_points_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Reduction Function Array
    $query = "SELECT reduction_function FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $reduction_function_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Reduction Statement Array
    $query = "SELECT reduction_statement FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $reduction_statement_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Student Function Array
    $query = "SELECT student_function FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $student_function_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Student Statement Array
    $query = "SELECT student_statement FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $student_statement_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_1 Array
    $query = "SELECT test_case_1 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_1_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_2 Array
    $query = "SELECT test_case_2 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_2_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_3 Array
    $query = "SELECT test_case_3 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_3_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_4 Array
    $query = "SELECT test_case_4 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_4_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Test_Case_5 Array
    $query = "SELECT test_case_5 FROM questions WHERE approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $test_case_5_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Problems Array
    $query = "SELECT problem FROM questions WHERE approved='y'";              //Gets all data from questions table
    $query_run = mysqli_query($con, $query);                                  //Executes a single query against the database whose result can then be retrieved using mysqli_store_result()
    while($row = mysqli_fetch_array($query_run))
    {
      $problem_array[] = $row[0];
    }
    mysqli_free_result($query_run);                                           //Frees the memory associated with the last result.

    //Build Question Grade Array
    $query = "SELECT question_grade FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $question_grade_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Comments Array
    $query = "SELECT comments FROM exam_grades WHERE exam_id=(SELECT MAX(id) FROM exams) AND username='$username'";
    $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
    $comments_array = mysqli_fetch_array($query_run);
    $comments = $comments_array[0];
    mysqli_free_result($query_run);

    $query = "SELECT username FROM answers WHERE answers.exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    $username_array = mysqli_fetch_array($query_run);
    $username = $username_array[0];
    mysqli_free_result($query_run);

    //Build Topic Array
    $query = "SELECT topic FROM questions WHERE questions.approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $topic_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Difficulty Array
    $query = "SELECT difficulty FROM questions WHERE questions.approved='y'";
    $query_run = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($query_run))
    {
      $difficulty_array[] = $row[0];
    }
    mysqli_free_result($query_run);

    //Build Viewable Variable
    $query = "SELECT viewable FROM view_results WHERE exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    $viewable_array = mysqli_fetch_array($query_run);
    $viewable = $viewable_array[0];
    mysqli_free_result($query_run);

    //Build Exam_Grade Variable                                               //Original Exam Grade before the Professor modified points
    $query = "SELECT exam_grade FROM exam_grades WHERE exam_id=(SELECT MAX(id) FROM exams)";
    $query_run = mysqli_query($con, $query);
    $exam_grade_array = mysqli_fetch_array($query_run);
    $exam_grade = $exam_grade_array[0];
    mysqli_free_result($query_run);

    //Make associative array of arrays
    $data = array('username'=>$username,'exam_id'=>$exam_id,'question_id'=>$question_id_array,'original_student_code_array'=>$original_student_code_array,'student_code'=>$student_code_array,'test_case_1_answer'=>$test_case_1_answer_array,'test_case_2_answer'=>$test_case_2_answer_array,'test_case_3_answer'=>$test_case_3_answer_array,'test_case_4_answer'=>$test_case_4_answer_array,'test_case_5_answer'=>$test_case_5_answer_array,'question_points'=>$question_points_array,'reduction_function'=>$reduction_function_array,'reduction_statement'=>$reduction_statement_array,'student_function'=>$student_function_array,'student_statement'=>$student_statement_array,'test_case_1'=>$test_case_1_array,'test_case_2'=>$test_case_2_array,'test_case_3'=>$test_case_3_array,'test_case_4'=>$test_case_4_array,'test_case_5'=>$test_case_5_array,'problem'=>$problem_array,'question_grade'=>$question_grade_array,'comments'=>$comments,'topic'=>$topic_array,'difficulty'=>$difficulty_array,'viewable'=>$viewable,'exam_grade'=>$exam_grade);
    echo json_encode($data);
    mysqli_close($con);                                                       //Closes database connection

  }

?>
