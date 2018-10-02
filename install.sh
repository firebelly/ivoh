#!/usr/bin/env bash

composer install
bundle install

cd web/app/themes/fb-sage
npm install bower
npm install
bower install
gulp
