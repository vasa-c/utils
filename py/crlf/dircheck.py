#!/usr/bin/env python
import sys
from os import path, walk

excs = ["txt", "html", "xml", "conf", "py", "php", "js", "css", "c", "sh", "twig", "sql"]

def error(content):
    print >> sys.stderr, content
    exit()

def dircheck(dirname):
    if not path.isdir(dirname):
        error("{} is not directory".format(dirname))
    for root, subfolders, files in walk(dirname):
        for filename in files:
            filecheck("{}{}{}".format(root, "/", filename))

def filecheck(filename):
    fn = filename.split(".")
    if len(fn) == 1:
        return
    fn = fn.pop()
    if (excs is not None) and (fn not in excs): 
        return
    try:
        fp = open(filename, "rb")
        content = fp.read()
        fp.close()
    except IOError:
        return
    count = content.count("\r")
    if count > 0:
        print("{} [{}]".format(filename, count))

dirs = sys.argv[1:]

if (len(dirs) > 0) and (dirs[0][0] == "-"):
    if dirs[0] == "-":
        excs = None
    else:
        excs = dirs[0][1:].split(",")
    dirs = dirs[1:]            
    
if len(dirs) == 0:
    error("Format: ./dircheck.py [-exc1,exc2,exc3] DIRNAME1 [DIRNAME2 DIRNAME3]");
    

for dirname in dirs:
    dircheck(dirname)
