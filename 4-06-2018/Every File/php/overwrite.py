#!/usr/bin/env python

#function overwrites "student_answer.py" file using the 2 passed command line arguments
def write_to_py(students_code, test_case):
    f = open('/afs/cad.njit.edu/u/r/l/rl265/public_html/php/student_answer.py', 'w')    #opens "student_answer.py" to be written to
    f.write(students_code + "\n" + "print(" + test_case + ")")                          #writes the students code then runs test case in "student_answer.py"
    f.close()

if __name__ == '__main__':                                                              #accepts arguments from php
    import sys                                                                          #needed to call arguments
    write_to_py(sys.argv[1], sys.argv[2])                                               #calls write_to_py() with 1st and 2nd passed arguments
