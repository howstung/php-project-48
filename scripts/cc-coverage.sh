#!/bin/bash

curl -L https://codeclimate.com/downloads/test-reporter/test-reporter-latest-linux-amd64 > ./cc-test-reporter
chmod +x ./cc-test-reporter
./cc-test-reporter before-build
./cc-test-reporter format-coverage -t clover test-reports/coverage.xml
./cc-test-reporter upload-coverage -r $CC_TEST_REPORTER_ID
