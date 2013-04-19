#!/usr/bin/env python
import sys

def error(content):
    print >> sys.stderr, content
    exit()

files = sys.argv[1:]

if len(files) == 0:
    error("Format: ./filecheck.py FILENAME1 [FILENAME2 FILENAME3]");

for filename in files:
    try:
        fp = open(filename, "rb")
        content = fp.read()
        fp.close()
    except IOError:
        error("Error: file {} is not found".format(filename))
    if content.find("\r") != -1:
        print("FAIL {}".format(filename))