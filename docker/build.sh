#!/usr/bin/env bash

cd "$(dirname "$0")"

docker pull php:7.2-fpm-alpine
docker pull php:7.3-fpm-alpine
docker pull php:7.4-fpm-alpine

docker build -t abtercms/php:72 -f php/Dockerfile-72 php
docker build -t abtercms/php:73 -f php/Dockerfile-73 php
docker build -t abtercms/php:74 -t abtercms/php:latest -f php/Dockerfile-74 php

docker push abtercms/php:72
docker push abtercms/php:73
docker push abtercms/php:74
docker push abtercms/php:latest
