 <?php
 error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
 ini_set('display_errors' , 1);
 $test_case_1_answer = [];
 $test_case_2_answer = [];

 $test_cases_answer_array = array($test_case_1_answer, $test_case_2_answer); // Array of Arrays

 $data = array("test_cases_answer_array"=>$test_cases_answer_array);
 $data['test_cases_answer_array'][0][0]= '10';
 $data['test_cases_answer_array'][0][1]= '20';
 $data['test_cases_answer_array'][1][0]= '20';
 $data['test_cases_answer_array'][1][1]= '10';

$ass_array=array('test_case_1_answer'=>$data['test_cases_answer_array'][0],'test_case_2_answer'=>$data['test_cases_answer_array'][1]);
 print_r($ass_array['test_case_1_answer']);
 print_r($ass_array['test_case_2_answer']);

       //$username = $_POST['username'];
        //$password = $_POST['password'];
        //$response= $_POST['random'];
        //$post =json_decode(file_get_contents("php://input"),true);

        //echo 'ECHO'.$post;
        //echo 'ECHO'.$username;
        //echo 'ECHO'.$pass;

     if(isset($_POST['username'] ,$_POST['password'])){
        /*
        $data = array("username"=>$username, "password"=>$password);
        $string = http_build_query($data);

        //change this link to usman's middle
        $ch = curl_init("https://web.njit.edu/~rl265/php/middle_end.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
        */

        $data = array('type'=>'yo homo');
        echo json_encode($data);

     }
     //if(isset($_POST['random'])){

      //  echo 'MADE INSIDE RESPONSE';
     //}
?>
