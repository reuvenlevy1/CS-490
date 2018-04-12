def addTwo(x,y):
    return x+y
if __name__ == '__main__':
	import sys
	sys.argv[1] = int(sys.argv[1])
	sys.argv[2] = int(sys.argv[2])
	add(sys.argv[1], sys.argv[2])