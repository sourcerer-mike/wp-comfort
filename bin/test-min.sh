#!/usr/bin/env bash

testCode=0

bin/phpmd comfort.php,includes text phpmd.xml
testCode+=$?

exit ${testCode}