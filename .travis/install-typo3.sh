#!/bin/bash

mkdir -p build/web/typo3conf
../build/bin/typo3cms install:setup
    --non-interactive \
    --database-host-name=127.0.0.1 \
    --database-port=3306 \
    --database-user-name=travis \
    --database-name=typo3 \
    --use-existing-database \
    --admin-user-name=travis \
    --admin-password=travis123456! \
    --site-setup-type=site

../build/bin/typo3cms install:generatepackagestates
../build/bin/typo3cms database:updateschema *.add,*.change
../build/bin/typo3cms extension:activate px_ical
../build/bin/typo3cms cache:flushgroups system
../build/bin/typo3cms configuration:set --path SYS/trustedHostsPattern --value ".*"