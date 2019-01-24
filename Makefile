LARADOCK_PATH=veva-laradock

up:
	cd ${LARADOCK_PATH}; docker-compose up workspace mysql redis nginx php-fpm

workspace:
	cd ${LARADOCK_PATH}; docker-compose exec workspace bash

default: up
