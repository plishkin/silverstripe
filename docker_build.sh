#!/usr/bin/env bash

sudo chmod 0777 . ./storage ./.graphql-generated ./silverstripe-cache

docker-compose build

docker-compose up -d db

docker-compose up -d fpm
docker-compose exec fpm bash ./vendor/bin/sake installsake
docker-compose exec --user www-data fpm composer install --optimize-autoloader
docker-compose exec --user www-data fpm sake /dev/build flush=1

docker-compose up -d websockets
docker-compose exec --user root websockets bash ./vendor/bin/sake installsake

docker-compose up -d


