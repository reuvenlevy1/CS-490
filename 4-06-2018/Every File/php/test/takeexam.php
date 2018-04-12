<?php
  $data = array("type"=>"student_exam");
  $string = http_build_query($data);
  $ch = curl_init("https://web.njit.edu/~rl265/php/backend.php");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $answer = curl_exec($ch);
  curl_close($ch);

  $test = json_decode($answer, true);

  //echo $test['problem'][3];
  //print_r($test);
//print_r($test);
  //echo $test['problem'][0];
?>


 <link rel="stylesheet" href="input.css">
 <center><div style="background-color:aliceblue;color:slategray;font-size:20px;text-align:center;">Time left = <span id="timer"></div></center>
<div class="container">
<center><h1>Exam 1 Student!</h1></center>


<?php for($x=0; $x<sizeof($test['problem']); $x++){ ?>
<div class="questionPane" id=<?php echo $x; ?>>
  <p id="question"><?php echo $test['problem'][$x]; ?> </p>
  <textarea name="answer$x" style="width:200px;height:auto;" input type="text" id="answer$x" placeholder="Question".<?php echo $x; ?>."enter answer here"></textarea>
<div class="id$x">
Points:<?php echo $test['points'][$x]; ?>
   </div>
  -<button onclick="Question$x()" class="checkbtn">?</button>

<p id="answerPane$x"></p>
</div>
<?php } ?>



<!-- <div class="questionPane" id="1">
  <p id="question"><?php echo $test['problem'][0]; ?> </p>
  <textarea name="answer1" style="width:200px;height:auto;" input type="text" id="answer1" placeholder="Question 1 enter answer here"></textarea>
<div class="id1">
Points : <?php echo $test['points'][0]; ?>
   </div>
  -<button onclick="Question1()" class="checkbtn">?</button>

<p id="answerPane1"></p>
</div>

<div class="questionPane" id="2">
  <p id="question"><?php echo $test['problem'][1]; ?> </p>
  <textarea name="answer2" style="width:200px;height:auto;" input type="text" id="answer2" placeholder="Question 2 enter answer here"></textarea>
  <div class="id2">
  Points : <?php echo $test['points'][1]; ?>
     </div>
  -<button onclick="Question2()" class="checkbtn">?</button>

<p id="answerPane1"></p>
</div>

<div class="questionPane" id="3">
  <p id="question"><?php echo $test['problem'][2];?></p>
  <textarea name="answer3" style="width:200px;height:auto;" input type="text" id="answer3" placeholder="Question 3 enter answer here"></textarea>
  <div class="id3">
  Points : <?php echo $test['points'][2]; ?>
     </div>
  -<button onclick="Question3()" class="checkbtn">?</button>

<p id="answerPane1"></p>
</div>
<div class="questionPane" id="4">
  <p id="question"><?php echo $test['problem'][3]; ?> </p>
  <textarea name="answer4" style="width:200px;height:auto;" input type="text" id="answer4" placeholder="Question 4 enter answer here"></textarea>
  <div class="id4">
  Points : <?php echo $test['points'][3]; ?>
     </div>
  -<button onclick="Question4()" class="checkbtn">?</button>

<p id="answerPane1"></p> -->

</div>
</div>
<center><button type="button" onclick="send()">Submit exam</button></center>



<div id="test"></div>
<script>
window.onload = codeAddress();
// function timeout(){
// myVar = setTimeout(codeAddress, 1000);
// }

 function codeAddress(){

//    if(!(document.getElementById("id3"))){
//    document.getElementById("3").style.display="none";
//  }
//  if(!(document.getElementById("id4"))){
//  document.getElementById("4").style.display="none";
// }

}
  // if(document.getElementById("question") === " "){       //supposed to hide div when empty, not show test
  //   document.getElementById("2").style.display="none";
  // }
  // if(document.getElementById("question") === " "){
  //   document.getElementById("2").style.display="none";
  // }

      function Question1()
      {
        var ans1 = document.getElementById("answer1").value;

        if (ans1.toLowerCase() != "")
        {
          document.getElementById("2").style.display="none";

        }
      }
      function Question2()
      {
        var ans2 = document.getElementById("answer2").value;
        var score = document.getElementById("scoring").innerHTML;
        if (ans2.toLowerCase() != "")
        {
          document.getElementById("2").style.display="none";
         }

      }
      function Question3()
      {
        var ans3 = document.getElementById("answer3").value;

        if (ans3.toLowerCase() != "")
        {
        document.getElementById("3").style.display="none";
      }
      }

      function Question4()
      {
        var ans4 = document.getElementById("answer4").value;

        if (ans4.toLowerCase() != "")
        {
        document.getElementById("4").style.display="none";
      }
      }

        document.getElementById('timer').innerHTML =
        30 + ":" + 00;
        startTimer();

function startTimer() {
  var presentTime = document.getElementById('timer').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1}
  //if(m<0){alert('timer completed')}

  document.getElementById('timer').innerHTML =
    m + ":" + s;
  setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}
function send()
{
var xhttp;
if (window.XMLHttpRequest)
  {
  // for newer browers
  xhttp=new XMLHttpRequest();
  }
else
  {
  // code for njit machines
  xhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xhttp.onreadystatechange=function()
//readyState: 4: request finished and response is ready, status: 200: "OK"  and When readyState is 4 and status is 200, the response is ready:
  {
  if (this.readyState==4 && this.status==200)
    {
    document.getElementById("test").innerHTML=this.responseText; //make sure php file this gets sent to echoes "EXAM SUBMITTED"
    }
  }
xhttp.open("POST","https://web.njit.edu/~aem39/beta/submit.php",true); //ANYTHING HERE WILL BE ECHO'd

xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

var ans1 = document.getElementById("answer1").value;
var ans2 = document.getElementById("answer2").value;
var ans3 = document.getElementById("answer3").value;
var ans4 = document.getElementById("answer4").value;


var id1 = "<?php echo $test['question_id'][0]; ?>" ;
var id2 = "<?php echo $test['question_id'][1]; ?>" ;
var id3 = "<?php echo $test['question_id'][2]; ?>" ;
var id4 = "<?php echo $test['question_id'][3]; ?>" ;



var test1 = "<?php echo $test['test_case_1'][0]; ?>" ;
var test2 = "<?php echo $test['test_case_1'][1]; ?>" ;
var test3 = "<?php echo $test['test_case_1'][2]; ?>" ;
var test4 = "<?php echo $test['test_case_1'][3]; ?>" ;

var test6 = "<?php echo $test['test_case_2'][0]; ?>" ;
var test7 = "<?php echo $test['test_case_2'][1]; ?>" ;
var test8 = "<?php echo $test['test_case_2'][2]; ?>" ;
var test9 = "<?php echo $test['test_case_2'][3]; ?>" ;



var test11 =  "<?php echo $test['test_case_3'][0]; ?>" ;
var test12 = "<?php echo $test['test_case_3'][1]; ?>" ;
var test13 = "<?php echo $test['test_case_3'][2]; ?>" ;
var test14 = "<?php echo $test['test_case_3'][3]; ?>";

var test16 =  "<?php echo $test['test_case_4'][0]; ?>" ;
var test17 = "<?php echo $test['test_case_4'][1]; ?>" ;
var test18 = "<?php echo $test['test_case_4'][2]; ?>" ;
var test19 = "<?php echo $test['test_case_4'][3]; ?>";


var test21 =  "<?php echo $test['test_case_5'][0]; ?>" ;
var test22 = "<?php echo $test['test_case_5'][1]; ?>" ;
var test23 = "<?php echo $test['test_case_5'][2]; ?>" ;
var test24 = "<?php echo $test['test_case_5'][3]; ?>";


var test25 =  "<?php echo $test['points'][0]; ?>" ;
var test26 = "<?php echo $test['points'][1]; ?>" ;
var test27 = "<?php echo $test['points'][2]; ?>" ;
var test28 = "<?php echo $test['points'][3]; ?>";

var test29 =  "<?php echo $test['problem'][0]; ?>" ;
var test30 = "<?php echo $test['problem'][1]; ?>" ;
var test31 = "<?php echo $test['problem'][2]; ?>" ;
var test32 = "<?php echo $test['problem'][3]; ?>";







xhttp.send("id1="+encodeURIComponent(id1)
+"&id2="+encodeURIComponent(id2)
+"&id3="+encodeURIComponent(id3)
+"&id4="+encodeURIComponent(id4)
+"&answer1="+encodeURIComponent(ans1)
+"&answer2="+encodeURIComponent(ans2)
+"&answer3="+encodeURIComponent(ans3)
+"&answer4="+encodeURIComponent(ans4)
+"&test1="+encodeURIComponent(test1)
+"&test2="+encodeURIComponent(test2)
+"&test3="+encodeURIComponent(test3)
+"&test4="+encodeURIComponent(test4)

+"&test6="+encodeURIComponent(test6)
+"&test7="+encodeURIComponent(test7)
+"&test8="+encodeURIComponent(test8)
+"&test9="+encodeURIComponent(test9)

+"&test11="+encodeURIComponent(test11)
+"&test12="+encodeURIComponent(test12)
+"&test13="+encodeURIComponent(test13)
+"&test14="+encodeURIComponent(test14)

+"&test16="+encodeURIComponent(test16)
+"&test17="+encodeURIComponent(test17)
+"&test18="+encodeURIComponent(test18)
+"&test19="+encodeURIComponent(test19)

+"&test21="+encodeURIComponent(test21)
+"&test22="+encodeURIComponent(test22)
+"&test23="+encodeURIComponent(test23)
+"&test24="+encodeURIComponent(test24)

+"&test25="+encodeURIComponent(test25)
+"&test26="+encodeURIComponent(test26)
+"&test27="+encodeURIComponent(test27)
+"&test28="+encodeURIComponent(test28)

+"&test29="+encodeURIComponent(test29)
+"&test30="+encodeURIComponent(test30)
+"&test31="+encodeURIComponent(test31)
+"&test32="+encodeURIComponent(test32)



);

document.write("Exam Complete, You will be redirected in 5 seconds to your homepage");
setTimeout('Redirect()', 5000);


}
function Redirect()
{
window.location="student.php";
}

var textareas = document.getElementsByTagName('textarea');
var count = textareas.length;
for(var i=0;i<count;i++){
    textareas[i].onkeydown = function(e){
        if(e.keyCode==9 || e.which==9){
            e.preventDefault();
            var s = this.selectionStart;
            this.value = this.value.substring(0,this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
            this.selectionEnd = s+1;
        }
    }
}
      </script>
