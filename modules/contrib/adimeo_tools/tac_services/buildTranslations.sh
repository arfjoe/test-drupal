#!/bin/bash

# Create a new json file from the original js traduction file
for file in ./libraries/tarteaucitron/lang/*.js
do
    cp $file ${file}.json
    sed -i -e 's/tarteaucitron.lang = //g' $file.json
    sed -i -e 's/;//g' $file.json
    sed -i 1d $file.json
done

# Move json files to translation folder
mv ./libraries/tarteaucitron/lang/*.json ./translations/json/

# Rename files
cd ./translations/json/
rename 's/tarteaucitron.//' *
rename 's/\.js\.json$/.json/' *

# Creating PO files
cd ../../
php JSONtoPO.php

# Clean Up
rmdir ./translations/json/