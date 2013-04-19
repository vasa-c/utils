#!/usr/bin/env python
import sys

def error(content):
    print >> sys.stderr, content
    exit()

if len(sys.argv) != 2:
    error("Format: ./fileout.py FILENAME");
    
filename = sys.argv[1]
try:
    fp = open(filename, "rb")
    content = fp.read()
    fp.close()
except IOError:
    error("Error: file {} is not found".format(filename))

content = content.replace("\n", "[\\n]\n").replace("\r", "[\\r]")
print(content)
