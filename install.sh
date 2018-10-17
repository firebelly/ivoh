#!/usr/bin/env bash

composer install
gem install bundler
bundle install
cp .env.example .env
cp .env.staging.example .env.staging

cd web/app/themes/ivoh
npm install bower
npm install
bower install
gulp
