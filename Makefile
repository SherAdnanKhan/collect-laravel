up:
	cd laradock; docker-compose up workspace mysql redis nginx php-fpm

workspace:
	cd laradock; docker-compose exec workspace bash

default: up
