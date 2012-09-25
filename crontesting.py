#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
import os

def main():
    rv = os.system("git checkout develop; echo $?")
    if not rv:
        print "CRON FAILED"
        sys.exit()

    rv = os.system("git reset --hard origin/develop; echo $?")
    if not rv:
        print "CRON FAILED"
        sys.exit()

    rv = os.system("git fetch origin develop; echo $?")
    if not rv:
        print "CRON FAILED"
        sys.exit()

    rv = os.system("git checkout testing; echo $?")
    if not rv:
        print "CRON FAILED"
        sys.exit()

    rv = os.system("git reset --hard origin/testing; echo $?")
    if not rv:
        print "CRON FAILED"
        sys.exit()

    rv = os.system("git merge --no-ff develop; echo $?")
    if not rv:
        print "CRON FAILED"
        sys.exit()

    os.system("php oc-includes/simpletest/test/osclass/test_all.php --installer --frontend --admin");
    
    
    

if __name__ == '__main__':
    main()