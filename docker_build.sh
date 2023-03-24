#!/usr/bin/env bash

sudo chmod -R 0777 ./storage
sudo chown -R www-data ./storage ./public

docker-compose build

docker-compose up -d swoole
docker-compose exec swoole composer install --optimize-autoloader
docker-compose exec swoole bash ./vendor/bin/sake installsake
docker-compose stop swoole

docker-compose up -d


