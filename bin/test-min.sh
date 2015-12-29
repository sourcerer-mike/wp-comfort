#!/usr/bin/env bash

testCode=0

bin/phpmd comfort.php,includes text phpmd.xml
testCode+=$?

bin/phpcpd -q --fuzzy *.php includes/
testCode+=$?

exit ${testCode}