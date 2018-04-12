#!/usr/bin/env python
# function overwrites write_to_me.py file

def write_to_py(arg):
    f = open('/afs/cad.njit.edu/u/r/l/rl265/public_html/php/new_test.py', 'w')
    #f.write("#!/usr/bin/env python" + "\n") #may be needed if student does not write it himself in his python code

    #this portion is necessary for the file to take in arguments. Specifically hard coded for the add function in test.py
    f.write(arg + "\n")
    f.write("if __name__ == \'__main__\':" + "\n"
    + "\timport sys" + "\n"
    + "\tsys.argv[1] = int(sys.argv[1])" + "\n" #converts args to int. for some reason int passed from php to python turn into strings
    + "\tsys.argv[2] = int(sys.argv[2])" + "\n"
    + "\tadd(sys.argv[1], sys.argv[2])")
    f.close()

    a = None #unset variable

if __name__ == '__main__': #accepts arguments from php
    import sys
    #print("\n" + "<-------argv[1] is------>: " + "\n" + "\n" + str(sys.argv[1]))
    write_to_py(sys.argv[1]) #execute
