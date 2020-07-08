LARADOCK_PATH=veva-laradock

build:
	cd ${LARADOCK_PATH}; docker-compose up --build workspace mysql redis nginx php-fpm elasticsearch mailhog

up:
	cd ${LARADOCK_PATH}; docker-compose up workspace mysql redis nginx php-fpm elasticsearch mailhog

stop:
	cd ${LARADOCK_PATH}; docker-compose stop

workspace:
	cd ${LARADOCK_PATH}; docker-compose exec workspace bash

default: up
