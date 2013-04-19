#!/usr/bin/env python
import sys
import os.path

def error(content):
    print >> sys.stderr, content
    exit()

if len(sys.argv) == 2:
    filename = sys.argv[1]
    toself = False
elif len(sys.argv) == 3:
    filename = sys.argv[2]
    toself = True
else:
    error("Format: ./replace.py [-] FILENAME")

try:
    fp = open(filename, "rb")
    content = fp.read()
    fp.close()
except IOError:
    error("File {} is not found".format(filename))

content = content.replace("\r", "")
if toself and os.path.isfile(filename):
    try:
        fp = open(filename, "wb")
        fp.write(content)
        fp.close()
    except IOError:
        error("Error file write")
else:
    print(content)