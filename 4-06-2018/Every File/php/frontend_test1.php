<?php
  error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
  ini_set('display_errors' , 1);
?>
<!--Curl anything to backend_test1.php and print out the passed associative array. Might need to be done with a for loop to print all info.-->

<!DOCTYPE html>                                                     <!-- Tells webpage it's written in modern html (HTML 5)-->

<html lang="en">
  <head>                                                            <!-- contains tags that helps the browser render the page (data here will not be seen) -->
    <meta charset="utf-8">                                          <!-- To render English characters; must be within first 512 characters and before <title> -->
    <title>Frontend Testing Page</title>                                <!-- Shows in browser tabs, bookmarks, search results, etc. -->
	<link rel="stylesheet" href="https://web.njit.edu/~rl265/css/style.css">   <!-- Link to CSS file -->
  </head>

  <body style="background-color:#BFBFBF">
	<div id="name-window">											                      <!-- Division: Can style specific components within this tag separately from others -->
	  <center><h1>Frontend Login Testing Page</h1></center>
	</div>

	<div id="main-window">
	  <!--<form action="frontend_test1.php" method="POST">-->
    <form action="frontend_test1.php" method="POST">                           <!-- the form will post to the action as soon as the "Submit" button is pressed and won't execute the rest of the code beneath it. Got around this by creating the action to itself, however, to be practical it should be sent to another php page that should redirect you to another page depending on the application -->
    <!--<form action="https://web.njit.edu/~uk27/middle_login.php" method="POST">-->
	    <br><label class="lbls">Username:</label>
	    <input name="username" type="text" class="inputvalues" placeholder="Type in your username" /><br><br>  <!-- The name attribute allows for the input data to be called and maniupulated in the php section -->
	    <label class="lbls">Password:</label>
	    <input name="password" type="password" class="inputvalues" placeholder="Type in your password" /><br><br>
      <center><input name="log_bttn" type="submit" style="background-color:#3498db;" class="bttns" value="LOGIN"/></center><br>
	  </form>

    <!-- table for column names -->
    <!-- <table border="1">
      <?php
        $x=0;
        $test=1;
        $y=2;
        $test1=5;
        while($y<$test1){
          echo "<tr>";
          while($x<$test){
            $sum=$x+$y;
            echo "<td>".$sum."<br>".$x."</td>";
            echo "<td>hello</td>";
            echo "<td>goodbye</td>";
            $x++;
          }
          echo "</tr>";
          $y++;
          $x=0;
        }
      ?>
    </table> -->




    <!-- <?php
      // $x=0;
      // while($x<sizeof($test['problem'])){
      //   echo "<tr>";
      //   echo "<td>".$test['problem'][x]."</td>";
      //   echo "<td>".$test['difficulty'][x]."</td>";
      //   echo "<td>".$test['topic'][x]."</td>";
      //   echo "<td id='questionid'>".$test['id'][x]."</td>";
      //   echo "<td><input type='textarea' name='points[]' id='pointsx'></textarea>";
      //   echo "<input type='checkbox' name='check[]' id='Checkboxx' value='".$test['id'][x]"></td>";
      //   echo "</tr>";
      //   $x++;
      // }
    ?> -->

    <!-- <table border="1" name="?">
    <?php
      // $x=0;
      // $test=10;
      // $y=0;
      // $test1=3;
      // while($y<sizeof($test['problem'])){
      //   echo "<tr>";
      //   while($x<14){
      //     echo "<td>".$test['problem'][x]."</td>";
      //     echo "<td>".$test['difficulty'][x]."</td>";
      //     echo "<td>".$test['topic'][x]."</td>";
      //     echo "<td id='questionid'>".$test['id'][x]."</td>";
      //     echo "<input type='checkbox' name='check[]' id='Checkboxx' value='".$test['id'][x]"></td>";
      //     $x++;
      //   }
      //   echo "</tr>";
      //    $y++;
      //   $x=0;
      // }
    ?>
  </table> -->

    <!-- <?php
    //Take this information and move this to a new php page for security.
    if(isset($_POST['log_bttn'])){
      //$username = $_POST['username'];
      $password = $_POST['password'];
      //$username = 'professor';  // worked
      //$password = 'password';   // worked
      //With those both working above, this means you will be able to curl ANY associative array (doesn't have to be "POST" data)
        //and can pick up on the other page using if(isset($_POST['variable']));

      //$data = array("username"=>$username, "password"=>$password, "type"=>"login"); // works
      //$data = array("username"=>$username, "password"=>$password, "type"=>"exam_questions");

      /*$problem = "Add two women and 1 cup";
      $difficulty="medium";
      $points = "35";
      $topic = "made up crap";
      $testcase1 = "addTwo(3,5) 8";
      $testcase2 = "addTwo(4,5) 9";

      //$id = "1,2,3,4,5";

      $id = [];
      $id[0] = '2';
      $id[1] = '5';
      $id[2] = '10';
      */

/*
      $student_code=[];
      $student_code[0]= 'def addTwo(x,y):
  return x+y';
      $student_code[1]= 'def subTwo(x,y):
  return x-y';
      $student_code[2]= 'def multTwo(x,y):
  return x*y';
      $test_case_1 = [];
      $test_case_1[0] = 'addTwo(5,2)   7';
      $test_case_1[1] = 'subTwo(5,2)   3';
      $test_case_1[2] = 'multTwo(5,2)   10';
      $test_case_2 = [];
      $test_case_2[0] = 'addTwo(-1,5)   4';
      $test_case_2[1] = 'subTwo(2,5)   -3';
      $test_case_2[2] = 'multTwo(2,0)   0';
      $data = array('type'=>'student_answers', 'student_code'=>$student_code, 'test_case_1'=>$test_case_1, 'test_case_2'=>$test_case_2);
*/

      //$id = array();
      $username = "Test";
      $questions = array('Write a function addTwo() that takes the arguments x and y and returns their sum.','Write a function subTwo() that takes the arguments x and y and returns their difference.','Write a function multTwo() that takes the arguments x and y and returns their product.','Write a function divTwo() that takes the arguments x and y and returns their quotient');
      $answers = array('def addTwo(x,y):
      return(x+y)', 'def gettothechoppa(x,y):
      return(x-y)', 'def multTwo(x,y):
      return(x*y)', 'def divTwo(x,y):
      print(x/y)');
      $test_case_1 = array('addTwo(5,2)   7','subTwo(5,2)   3','multTwo(5,2)   10','divTwo(0,5)   0');
      $test_case_2 = array('addTwo(-1,5)   4','subTwo(2,5)   -3','multTwo(2,0)   0','divTwo(5,1)   5');
      $test_case_3 = array('addTwo(3,3)   6','subTwo(3,3)   0','multTwo(3,3)   9','divTwo(2,2)   1');
      $test_case_4 = array('addTwo(-3,-3)   -6','subTwo(-4,1)   -5','multTwo(-2,4)   -8','divTwo(10,5)   2');
      $test_case_5 = array('','subTwo(4,-1)   3','multTwo(-1,-3)   3','divTwo(125,5)   25');
      $data = array('type'=>'student_answers','username'=>$username,'question_id'=>$questions,'answers'=>$answers,'test_case_1'=>$test_case_1,'test_case_2'=>$test_case_2,'test_case_3'=>$test_case_3,'test_case_4'=>$test_case_4,'test_case_5'=>$test_case_5);

/*
      $tmp_id_array= explode(" ",$test_case_1[0]);
      print_r($tmp_id_array);
      for($i=0; $i<sizeof($tmp_id_array); $i++){
        if(!empty($tmp_id_array[$i]) && preg_match('/^[A-Za-z0-9]+/', $tmp_id_array[$i])){
          echo "|not empty:".$tmp_id_array[$i]."|";
        }
      }
*/
      //$data = array('type'=>'student_answers','username'=>$username,'question_id'=>$id,'answers'=>$id);
      //print_r($data);

      //$data = array('type'=>'login', 'username'=>$username, 'password'=>$password);
      //$data = array('type'=>'exam_created','id'=>$id);
      //$data = array('type'=>'create_questions','problem'=>$problem,'difficulty'=>$difficulty,'points'=>$points,'topic'=>$topic,'test_case_1'=>$testcase1,'test_case_2'=>$testcase2);
      $string = http_build_query($data);
      //echo "string: ".$string;
      $ch = curl_init("https://web.njit.edu/~rl265/php/python_test.php");
      //$ch = curl_init("https://web.njit.edu/~uk27/middle_login.php");
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $answer = curl_exec($ch);                                             //Will only accept the first echo as the function only accepts one echo
      curl_close($ch);
      echo $answer;
      //print_r($answer);

      //$test = json_decode($answer, true);
      //print_r($test);
      //print_r(json_decode($answer));

/*//concatenate variables to variables to form another variable-------------------
      $i=0;
      $Q_array = [];
      $Q_window_0 = "hello!";
      $Q_array[$i] = ${'Q_window_' . $i};
      echo $Q_array[0];
*///------------------------------------------------------------------------------

      //$test = json_decode($answer, true);                                   //true is needed to turn the string back into an associative array

      //echo sizeof($test['problem']);                                      //length of the array connected to 'problem'
      //echo $test['problem'][4];                                             //could also use print_r
      //echo $test['status']." ".$test['role'];
      //echo $test['role'];
      //print_r($test['id'][0]);                                                       //prints all the contents in passed array
    }
	  ?> -->
	</div>
  </body>
</html>
