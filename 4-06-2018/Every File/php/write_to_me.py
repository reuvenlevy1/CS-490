#!/usr/bin/env python

#Write function that adds x and y to get 7
def add(x,y):
    z = x + y
    print(z)

if __name__ == '__main__':
	import sys
	sys.argv[1] = int(sys.argv[1])
	sys.argv[2] = int(sys.argv[2])
	add(sys.argv[1], sys.argv[2])