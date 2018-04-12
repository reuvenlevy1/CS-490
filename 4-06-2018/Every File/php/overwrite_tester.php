 <?php
//convert test.py to string
$test_python = file_get_contents('/afs/cad.njit.edu/u/r/l/rl265/public_html/php/test.py');
//echo "<-----------test_python-------------->"."\n"."\n".$test_python;

//execute write_over which will overwrite the write_to_me python file
$command_write_over = escapeshellcmd("python /afs/cad.njit.edu/u/r/l/rl265/public_html/php/write_over.py");
$output_write_over = shell_exec("$command_write_over '$test_python'");

//echo newly overwritten file
$write_to_me_python = file_get_contents('/afs/cad.njit.edu/u/r/l/rl265/public_html/php/write_to_me.py');
echo "<-----------write_to_me-------------->"."\n"."\n".$write_to_me_python."\n";

//sample test case add(3,4)

$test_case = "add(3,4)";
$split = explode(",", $test_case); //splits into [add(3, 4)]
$split_0 = explode("(", $split[0]); // splits add(3 into [add, 3]
$split_1 = explode(")", $split[1]); // splits 4) into [4,)]

//echo $split_0[1]."\n"; // print 3
//echo $split_1[0]."\n"; // print 4

//execute new code in write_to_me
$command = escapeshellcmd("python /afs/cad.njit.edu/u/u/k/uk27/public_html/write_to_me.py");
$output = shell_exec("$command '$split_0[1]' '$split_1[0]'");
//test Output

/*
$test_output = 7;
echo "Output is: ".$output."of type ".gettype($output)."\n";
echo "Test output is: ".$test_output." of type ".gettype($test_output)."\n";
if($output == $test_output){
  echo "Output is correct!"."\n";
}
unset($test_python);
*/
?>
