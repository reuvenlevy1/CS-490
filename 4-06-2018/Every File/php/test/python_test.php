<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//ini_set('display_errors' , 1);
//Receiving from frontend: $data = array('type'=>'student_answers','username'=>$username,'question_id'=>$id,'answers'=>$answers,'test_case_1'=>$test_case_1,'test_case_2'=>$test_case_2,'test_case_3'=>$test_case_3,'test_case_4'=>$test_case_4,'test_case_5'=>$test_case_5,'question_points'=>$question_points);
//if($_POST['type']=='student_answers'){
  //eventually put everything into a for loop to automate process for each coded question and test cases.

  $questions = array('Write a function addTwo() that takes the arguments x and y and returns their sum.','Write a function subTwo() that takes the arguments x and y and returns their difference.','Write a function multTwo() that takes the arguments x and y and returns their product.');
/*
  $answers = array('def addTwo(x,y):
  print(x+y)', 'def gettothechoppa(x,y):
  print(x-y)', 'def multTwo(x,y):
  return(x*y)', 'def divTwo(x,y):
  print(x/y)');
*/
  $answers = array('def addingishardlmao(x,y):
  return(x+y)', 'def subTwo(x,y):
  print(x-y)', 'def multTWO(x,y):
  print(x*y)');
  $test_case_1 = array('addTwo(5,2)   7',   'subTwo(5,2)   3',  'multTwo(5,2)   10');
  $test_case_2 = array('addTwo(-1,5)   4',  'subTwo(2,5)   -3', 'multTwo(2,0)   0');
  $test_case_3 = array('addTwo(3,3)   6',   'subTwo(3,3)   0',  'multTwo(3,3)   9');
  $test_case_4 = array('addTwo(-3,-3)   -6','subTwo(-4,1)   -5','multTwo(-2,4)   -8');
  $test_case_5 = array('',                  'subTwo(4,-1)   5', 'multTwo(-1,-3)   3');
  // $question_points = array('25','25','25','25');
  $question_points = array('40','20','40');
  $data = array('type'=>'student_answers','questions'=>$questions,'answers'=>$answers,'test_case_1'=>$test_case_1,'test_case_2'=>$test_case_2,'test_case_3'=>$test_case_3,'test_case_4'=>$test_case_4,'test_case_5'=>$test_case_5,'question_points'=>$question_points);

  $questions_array = $data['questions'];
  $student_code_array = $data['answers'];
  $question_points = $data['question_points'];                                //points assigned PER question - array

  $test_case_1_array = $data['test_case_1'];
  $test_case_2_array = $data['test_case_2'];
  $test_case_3_array = $data['test_case_3'];
  $test_case_4_array = $data['test_case_4'];
  $test_case_5_array = $data['test_case_5'];

/*
  $questions_array = $_POST['questions'];
  $student_code_array = $_POST['answers'];
  $question_points = $_POST['points'];                               //The amount of points a question is worth provided by Professor on make-exam page, PER question

  $test_case_1_array = $_POST['test_case_1'];
  $test_case_2_array = $_POST['test_case_2'];
  $test_case_3_array = $_POST['test_case_3'];
  $test_case_4_array = $_POST['test_case_4'];
  $test_case_5_array = $_POST['test_case_5'];
*/
  $test_cases_array = array($test_case_1_array, $test_case_2_array, $test_case_3_array, $test_case_4_array, $test_case_5_array); //Array of arrays

  $test_case_explode_array = [];                                              //Only need one, can just overwrite values

  $test_case_1_answer = [];
  $test_case_2_answer = [];
  $test_case_3_answer = [];
  $test_case_4_answer = [];
  $test_case_5_answer = [];

  $test_cases_answer_array = array($test_case_1_answer, $test_case_2_answer, $test_case_3_answer, $test_case_4_answer, $test_case_5_answer); // Array of Arrays

  $student_function_array = [];                                               //array of student function name per question; This is the exploded part of the function name + '('
  $student_statement_array = [];                                              //array of student closing statement ('return' or 'print') per question

  $input_data = array('test_cases_array'=>$test_cases_array,'test_cases_answer_array'=>$test_cases_answer_array);  //An Associative array of arrays of arrays
  $points = [];                                                               //will hold number of points per question: (# of correct testcases)/(total # of testcases)
  $reduction_function = [];                                                   //will hold number of misspelled function name point reductions per question: (1/total # of testcases)*25; --> 25 is (1/4) amount of points for a question x 100%;
  $reduction_statement = [];
  $question_grade = [];                                                       //will hold the complete grade PER question: $question_grade = question_points*points

  // echo "test case 4 for question 1:\n".$data['answers'][1]."\n\n\n\n";
  //echo "test_case_total:".test_case_total(3, $input_data['test_cases_array'])."\n\n\n";
  //echo "size: ".sizeof($input_data['test_cases_array'])."\n\n";
  for($x=0;$x<sizeof($input_data['test_cases_array']);$x++){                  //size of test_cases_array (should be 5 for 5 test cases)
    $count=0;                                                                 //tracks number of correct test cases PER question
    //echo "x:$x\n";
    for($y=0;$y<sizeof($input_data['test_cases_array']);$y++){                //size of test_cases_array (should be 5 for 5 test cases) --> This is necessary so when you do test_cases_array[$y][$x] it will not stop prematurely, due to $y not incrementing as much as $x will
      //echo "y:$y\n\n";
    //for($y=0;$y<sizeof($input_data['test_cases_array'][$x]);$y++){          //size of $test_case_#_array
      //echo "test case $y size: ".sizeof($input_data['test_cases_array'][$y])."\n\n";
      //print("test case #$y for question #$x: ".$input_data['test_cases_array'][$y][$x]."\n\n\n");

      if(empty($input_data['test_cases_array'][$y])){                         //if there is no test case values; This will always be the case as long as test cases are created in numerical order
        break;                                                                //breaks to the for loop that will increment $x
      }
      //echo "test case 4 question 0 ".$input_data['test_cases_array'][4][0]."\n\n";
      //FIX THIS FOR NEXT PHASE OF PROJECT: make it stop counting y values if there is no question 5 etc.

      // if(empty($input_data['test_cases_array'][$y][$x])){                    //works for the empty test case showing in here
      //   echo "empty value --> question $x| Test case $y =";
      //   print_r($input_data['test_cases_array'][$y][$x]);
      //   echo "\n\n";
      // }

      //if(sizeof($input_data['test_cases_array'][$y][$x])==0 ){                //To avoid 'Index Out Of Bounds' Error. (size of the value of test_case_#_array) --> Takes first value of every test_case_# array (so takes all test cases[1-5] PER question)
      if(empty($input_data['test_cases_array'][$y][$x])){
        //print("test case $y for question $x: ".$input_data['test_cases_array'][$y][$x]."\n\n\n");
        if($y != sizeof($input_data['test_cases_array'][$y])-1){              //A check to make sure that $y doesn't increment if the last test case (test case 5) actually had a 0 value. If so, the count would go out-of-bounds
          continue;
        }
      }
      //if(sizeof($input_data['test_cases_array'][$y][$x])!=0){               //----doesn't work----To avoid 'Index Out Of Bounds' Error. (size of the value of test_case_#_array) --> Takes first value of every test_case_# array (so takes all test cases[1-5] PER question)
      else{
        $tmp_id_array = explode(" ",$input_data['test_cases_array'][$y][$x]); //Takes values separated by commas in string and puts into an array; Every test case will be split into an _explode_array of size 2, [0]=testcase [1]=intended output
        //take out values that are equal to null
        $j = 0;                                                               //index of _explode_array
        //array will have only real values, no null values.
        //echo("|");
        //print_r(count($tmp_id_array));
        //echo("|");
        for($i=0; $i<sizeof($tmp_id_array); $i++){                            //will separate empty and null values from array
          if($tmp_id_array[$i] != " " && !is_null($tmp_id_array[$i]) && preg_match('/^[A-Za-z0-9`~!@#$%^&*()-_=+\\|\/.,<>?]/', $tmp_id_array[$i])){ //Every test case will be split into an _explode_array of size 2, [0]=testcase [1]=intended output
            $test_case_explode_array[$j] = trim($tmp_id_array[$i]);
            //echo("|");
            //echo gettype($test_case_explode_array[$i]);
            //echo $test_case_explode_array[$j];
            //echo("| ");
            $j++;
          }
        }

        //echo " student answer before: ".$student_code_array[$x];
        //save the function name that the student types in and include in an if statement, that if it is wrong, replace it with the correct one, take points off. If the student doesn't get it wrong, store the value anyway.
        if($y == 0){                                                          //will run for the first test case for EVERY question
          preg_match("/def [A-Za-z0-9]*\(/", $student_code_array[$x], $student_func_name_with_def); //takes everything in 'def' through '(' in student code array; $student_func_name_with_def[0]='def' + function name student typed + '('
          preg_match("/[A-Za-z0-9]*\(/", $student_func_name_with_def[0], $student_func_name);       //takes out the 'def' part of $student_func_name_with_def and only leaves the function name including '('; $student_func_name[0]= function name student typed + '('
          preg_match("/[A-Za-z0-9]*\(/", $test_case_explode_array[0], $correct_func_name);          //takes first part of the test case function name including '(' inputted from $test_case_explode_array[0] and places it into array $correct_func_name
          //echo "\n\n\n";
          //echo " |student_code_array[$x]:".$student_code_array[$x]."| \n\n";
          //echo " |student_func_name_with_def:".$student_func_name_with_def[0]."| \n";
          //echo " |correct_func_name:".$correct_func_name[0]."| \n\n\n";
          //echo "\n\n\n";
          $student_function_array[$x] = $student_func_name[0];                //stores student's function name + '(' per question

          if($student_func_name[0] != $correct_func_name[0]){
            $student_code_array[$x] = preg_replace("/def [A-Za-z0-9(,)]*\(/", 'def '.$correct_func_name[0], $student_code_array[$x]); //replaces everything in 'def' through '('; $correct_func_name[0]=what to replace; $student_code_array[$x]=input string to search in
            //echo " |student_code_array[$x]:".$student_code_array[$x]."| \n\n\n";
            //echo "correct_func_name = ".$correct_func_name[0]." student_code_array[0] = ".$student_code_array[$x];
            $total_test_cases = test_case_total($x, $input_data['test_cases_array']);
            $reduction_function[$x] = (1/$total_test_cases)*25;               //(1/total # of testcases)*25; --> 25 is (1/4) amount of points for a question x 100%l point reduction for misspelled function name.
            //echo " 1 / ".$total_test_cases." ";
          }
          else{
            $reduction_function[$x] = 0;
          }

          preg_match("/return|print/", $questions_array[$x], $correct_statement);     //checks question for 'return' or 'print' and stores into $correct_statement array
          //echo "\n\nquestion: ".$questions_array[$x]."\n\n\n";
          preg_match("/return|print/", $student_code_array[$x], $student_statement);  //checks student's code for 'return' or 'print' and stores into $student_statement array
          $student_statement_array[$x] = $student_statement[0];                       //stores student's closing statement ('return' or 'print') per question
          //echo "\n\ncorrect statement: ".$correct_statement[$x]."\n";
          //echo "student statement: ".$student_statement[$x]."\n\n\n";

          if($student_statement[0] != $correct_statement[0] && $correct_statement[0] == 'print'){
            $total_test_cases = test_case_total($x, $input_data['test_cases_array']);
            $reduction_statement[$x] = (1/$total_test_cases)*25;              //(1/total # of testcases)*25; --> 25 is (1/4) amount of points for a question x 100%l point reduction for misspelled function name.
            //echo "\n\nstudent_code before: $student_code_array[$x]\n";
            $student_code_array[$x] = preg_replace("/return/", "print", $student_code_array[$x]);
            //echo "student code fix: $student_code_array[0]\n\n\n";
            //echo "student_code after: $student_code_array[$x]\n\n";
            //echo " 1 / ".$total_test_cases." ";
          }
          elseif($student_statement[0] != $correct_statement[0] && $correct_statement[0] == 'return'){
            $total_test_cases = test_case_total($x, $input_data['test_cases_array']);
            $reduction_statement[$x] = (1/$total_test_cases)*25;              //(1/total # of testcases)*25; --> 25 is (1/4) amount of points for a question x 100%l point reduction for misspelled function name.
            $student_code_array[$x] = preg_replace("/print/", "return", $student_code_array[$x]);
            //echo "student code fix: $student_code_array[0]\n\n\n";
            //echo " 1 / ".$total_test_cases." ";
          }
          else{
            $reduction_statement[$x] = 0;
          }
        }

        //echo " student answer after: ".$student_code_array[$x]." \n\n\n";
        //print_r($test_case_explode_array);

        //echo " \n\n\nstudent_code_array[$x]: ".$student_code_array[$x]." ";
        //echo " test_case_explode_array[0]: ".$test_case_explode_array[0]. " \n\n\n";

        //This will be used for each new "student_code" variable passed-------------
        //Writes code to "student_answer.py" file, runs it and prints any output
        //if(sizeof($test_case_explode_array)!=0){
        if(!empty($test_case_explode_array)){
          $overwrite_execution = shell_exec("python /afs/cad.njit.edu/u/r/l/rl265/public_html/php/overwrite.py '$student_code_array[$x]' '$test_case_explode_array[0]'");       //runs "overwrite.py" file with 2 arguments; $student_code[$x]=student's code for 1 question; $test_case_explode_array[0]=testcase
          $overwrite_answer = shell_exec("python /afs/cad.njit.edu/u/r/l/rl265/public_html/php/student_answer.py"); //runs "student_answer.py" file and stores output into "$overwrite_answer"
        }
        //echo " |student code: ".$student_code_array[$x]."||test_case_explode[0]&[1]: ".$test_case_explode_array[0]."||".$test_case_explode_array[1]."||overwrite_answer: ".$overwrite_answer."| ";
        //--------------------------------------------------------------------------
        //print_r($test_case_explode_array[1]);
        //echo " |".trim($overwrite_answer)."==".$test_case_explode_array[1]."| \n\n\n";

        /*
        //this if statement doesn't stop count from being incremented for a missing test case
        if($x==3 && $y==1){                                                               //$input_data['test_cases_array'][3][1] --> broken
          echo " overwrite_answer: ";
          print_r($overwrite_answer);
          echo " test_case_explode_array[1]: ";
          print_r($test_case_explode_array[1]);
        }
        */
        //echo "overwrite_answer == test_case_explode_array\n";                         --> working
        //echo trim($overwrite_answer)."==".trim($test_case_explode_array[1])."\n\n\n"; --> working
        //print("test case $y for question $x:".$input_data['test_cases_array'][$y][$x]."\n\n\n");

        //echo "test case $y for question $x:\n";
        //echo trim($overwrite_answer)."=".trim($test_case_explode_array[1])."\n\n\n";
        if(trim($overwrite_answer) == trim($test_case_explode_array[1]) && preg_match('/^[A-Za-z0-9`~!@#$%^&*()-_=+\\|\/.,<>?]/', trim($overwrite_answer))){         //if the python output == test case solution
          //print("correct: test case $y for question $x\n");
          $input_data['test_cases_answer_array'][$y][$x] = '1';               //test case answer for each test case in each question (remember 1 question can have up to 5 test cases each)
          //$count++;                                                         //Post-increment:	Returns $a, then increments $a by one. --> still works, but for testing doesn't include updated value
          $count = $count + 1;
          //echo "count: ". $count."\n";
        }
        else{
          //print("wrong: test case $y for question $x\n");
          $input_data['test_cases_answer_array'][$y][$x] = '0';
          //echo "count not added";
        }
        //echo "test case $y == size of test_cases_array at $x is ". sizeof($input_data['test_cases_array'][$x]). "\n";
        // echo "size of test_case_array 2 = ".sizeof($input_data['test_cases_array'][1])."\n";
        //if($y == sizeof($input_data['test_cases_array'][$x])-1){             //Done at the end of all test cases running. --> DOESN'T WORK OUT THIS WAY AND FUCKED UP LAST TEST CASE FROM READING!! --> TOOK HOURS!!!!!!! DAYS EVEN!!!!
          $total_test_cases = test_case_total($x, $input_data['test_cases_array']);
          //echo "count=".$count."| total_test_cases=".$total_test_cases."\n";
          //$points[$x] = ($count)/($total_test_cases)*100 - $reduction_function[$x] - $reduction_statement[$x];  //(# of correct testcases)/(total # of testcases)*100 - # of point reductions PER question
          $points[$x] = ($count)/($total_test_cases) - ($reduction_function[$x] + $reduction_statement[$x])/100;  //(# of correct testcases)/(total # of testcases)- # of point reductions PER question/100
          $question_grade[$x] = $question_points[$x]*$points[$x];             //Complete grade PER question: question_points*points
          //echo $count." / ".$total_test_cases." ";
        //}
      }
      unset($overwrite_answer);                                               //prevents variable from storing previous iterations values
      unset($test_case_explode_array);                                        //resets variable to null
    }
    unset($student_func_name_with_def);
    unset($student_func_name);
    unset($correct_func_name);
    unset($student_statement);
    unset($correct_statement);
  }

  #get exam grade
  $exam_grade = 0;
  for($i=0; $i<sizeof($question_grade); $i++){
    $exam_grade += $question_grade[$i];
  }

  echo "\n\nreduction_statments: ";
  print_r($reduction_statement);
  echo "\n";

  echo "\n\npoints: ";
  print_r($points);

  echo "\n\nquestion_grade: ";
  print_r($question_grade);

  echo "\n\nexam_grade: ";
   print_r($exam_grade);

  echo "\n\n\n ";
  //print_r(sizeof($input_data['test_cases_array']));
  //print_r($input_data['test_cases_answer_array']);
  //print_r(json_encode($data = array('type'=>'student_answers','answers'=>$student_code_array,'test_case_1_answer'=>$input_data['test_cases_array'][0],'test_case_2_answer'=>$input_data['test_cases_array'][1],'test_case_3_answer'=>$input_data['test_cases_array'][2],'test_case_4_answer'=>$input_data['test_cases_array'][3],'test_case_5_answer'=>$input_data['test_cases_array'][4])));

  $data = array ('type'=>'student_answers','username'=>$_POST['username'],'question_id'=>$_POST['question_id'],'answers'=>$student_code_array,'test_case_1_answer'=>$input_data['test_cases_answer_array'][0],'test_case_2_answer'=>$input_data['test_cases_answer_array'][1],'test_case_3_answer'=>$input_data['test_cases_answer_array'][2],'test_case_4_answer'=>$input_data['test_cases_answer_array'][3],'test_case_5_answer'=>$input_data['test_cases_answer_array'][4],'points'=>$points,'reduction_function'=>$reduction_function,'reduction_statement'=>$reduction_statement,'student_function_array'=>$student_function_array,'student_statement_array'=>$student_statement_array,'question_grade'=>$question_grade,'exam_grade'=>$exam_grade);

  //print_r($data);
  //print_r($data['test_case_1_answer'][0]);
/*
  $string = http_build_query($data);
  $ch = curl_init("https://web.njit.edu/~rl265/php/backend.php");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_exec($ch);
  curl_close($ch);
*/
/*
  for($x=0;$x<sizeof($test_case_1_array)){
    if(sizeof($test_case_1_array)!=0){                                      //To avoid 'Index Out Of Bounds' Error.
      $tmp_id_array= explode(" ",$test_case_1[$x]);                         //Takes values separated by commas in string and puts into an array
      //take out values that are equal to null from id_array
      $j = 0;                                                               //index of _explode_array
      //id_array will have only id values, no null values.
      for($i=0; $i<sizeof($tmp_id_array); $i++){
        if($tmp_id_array[$i] != ""){                                        //Every test case will be split into an _explode_array of size 2, [0]=testcase [1]=intended output
          $test_case_1_explode_array[$j] = $tmp_id_array[$i];
          $j++;
        }
      }
      //print_r($test_case_1_explode_array);
      //This will be used for each new "student_code" variable passed-------------
      //Writes code to "student_answer.py" file, runs it and prints any output
      $overwrite_execution = shell_exec("python /afs/cad.njit.edu/u/r/l/rl265/public_html/php/overwrite.py '$student_code[0]' '$test_case_1_explode_array[0]'");       //runs "overwrite.py" file with 2 arguments
      $overwrite_answer = shell_exec("python /afs/cad.njit.edu/u/r/l/rl265/public_html/php/student_answer.py"); //runs "student_answer.py" file and stores output into "$overwrite_answer"
      //--------------------------------------------------------------------------
      if($overwrite_answer == $test_case_1_explode_array[1]){
        $test_case_1_answer = '1';
      }
      else{
        $test_case_1_answer = '0';
      }


    }
  }
*/


  /*
  //Used for testing if student_answer.py file was overwritten correctly.
  $student_output = file_get_contents('/afs/cad.njit.edu/u/r/l/rl265/public_html/php/student_answer.py'); //echo newly overwritten file
  //echo "<-----------write_to_me-------------->"."\n"."\n".$write_to_me_python."\n";
  */

  /*
  //Used for inputting test cases into the "student_code.py" file
  $test_case_python = escapeshellcmd("python /afs/cad.njit.edu/u/r/l/rl265/public_html/php/student_answer.py");
  $test_case_python_output = shell_exec("$command '' ");                    //Accepts 2 command line arguments
  */

//}

//Input from Professor's Release Exam Page after points have been adjusted and comments have been made----//Receiving $data = array('type'=>'points_update','username'=>$username,'exam_id'=>$exam_id,'question_id'=>$question_id,'points'=>$points,'comments'=>$comments,'subtract_points'=>$subtract_points,'add_points'=>$add_points,'question_points'=>$question_points); where each variable is an array
if($_POST['type']=='points_update'){
  //finish adding logic for updating points
  //Just need to account for the following:
  //1. change the amount of points for the question
  //2. account for $subtract_points
  //3. account for $add_points
  //BOTH 2. and 3. should be calculated PER questioin in $question_grade

  $points = $data['points'];                                                  //points received for all the testcases correct - reduction points
  $question_points = $data['question_points'];                                //points set by professor for question
  $subtract_points = $data['subtract_points'];
  $add_points = $data['add_points'];

  for($x=0;$x<sizeof($points);$x++){
    $question_grade[$x] = $question_points[$x]*$points[$x] - $subtract_points[$x] + $add_points[$x]; //Complete grade PER question: question_points*points
  }


  $data = array ('type'=>'points_update','question_points'=>$question_points,'subtract_points'=>$subtract_points,'add_points'=>$add_points,'question_grade'=>$question_grade);

  $string = http_build_query($data);
  $ch = curl_init("https://web.njit.edu/~rl265/php/backend.php");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_exec($ch);
  curl_close($ch);
}

  function test_case_total($x, $test_cases_array){                            //passing the following: $x=the value of the question on; $test_cases_array=$input_data['test_cases_array'];
    $total_test_cases = 0;                                                    //total number of test cases PER question
    //for($y=0;$y<sizeof($test_cases_array[$x]);$y++){                          //size of $test_case_#_array
      //if(sizeof($test_cases_array[$y][$x])!=0){                               //To avoid empty arrays. (size of the value of test_case_#_array) --> Takes first value of every test_case_# array (so takes all test cases[1-5] PER question)
        //if($y == sizeof($test_cases_array[$x])-1){                           //necessary for count to have been incremeneted for each test case

          for($i=0; $i<sizeof($test_cases_array); $i++){                      //size of number of test cases
            if(sizeof($test_cases_array[$i][$x])==0){                         //To avoid 'Index Out Of Bounds' Error. (size of the value of test_case_#_array) --> Takes first value of every test_case_# array (so takes all test cases[1-5] PER question)
              $i++;
            }
            //if(is_null($test_cases_array[$i][$x])){
              //$i++;
            //}
            //if(!is_null($test_cases_array[$i][$x]) && $test_cases_array[$i][$x] != " " && preg_match('/^[A-Za-z0-9`~!@#$%^&*()-_=+\\|\/.,<>?]/', $test_cases_array[$i][$x])){ //if test case has a valid value and isn't empty or null
            else{
              //echo "value: ".$test_cases_array[$i][$x]."\n";
              if(preg_match('/^[A-Za-z0-9`~!@#$%^&*()-_=+\\|\/.,<>?]/', $test_cases_array[$i][$x])){
                $total_test_cases++;
                //echo "total test cases: $total_test_cases\n\n\n";
              }
            }
          }
        //}
      //}
    //}
    return $total_test_cases;
  }
?>
