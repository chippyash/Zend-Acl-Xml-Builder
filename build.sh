#!/bin/bash
cd ~/Projects/chippyash/source/Zend-Acl-Builder
vendor/phpunit/phpunit/phpunit -c test/phpunit.xml --testdox-html contract.html test/
tdconv -t "Chippyash Zend ACL from XML Builder" contract.html docs/Test-Contract.md
rm contract.html

