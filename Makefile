LARADOCK_PATH=laradock

print-%:
	@echo $* = $($*)

showdir:
	cd ${LARADOCK_PATH}; ls
.PHONY: showdir

up:
	cd ${LARADOCK_PATH}; docker-compose up workspace mysql redis apache2 php-fpm mailhog elasticsearch
.PHONY: up

up-d:
	cd ${LARADOCK_PATH}; docker-compose up -d workspace mysql redis apache2 php-fpm mailhog elasticsearch
.PHONY: up-d

rebuild:
	cd ${LARADOCK_PATH}; docker-compose build --no-cache workspace mysql redis apache2 php-fpm mailhog elasticsearch; docker-compose up -d mysql redis apache2 php-fpm mailhog elasticsearch;
.PHONY: rebuild

stop:
	cd ${LARADOCK_PATH}; docker-compose stop
.PHONY: stop

up-workspace:
	cd ${LARADOCK_PATH}; docker-compose up -d workspace; docker-compose exec workspace bash
.PHONY: up-workspace

workspace:
	cd ${LARADOCK_PATH}; docker-compose exec workspace bash
.PHONY: workspace

composer-install:
	cd ${LARADOCK_PATH}; docker-compose exec workspace bash -c "composer install"
.PHONY: composer-install

composer-update:
	cd ${LARADOCK_PATH}; docker-compose exec workspace bash -c  "composer update"
.PHONY: composer-update

composer-require:
	cd ${LARADOCK_PATH}; docker-compose exec workspace bash -c  "composer require $(PACKAGE)"
.PHONY: composer-require

artisan:
	cd ${LARADOCK_PATH}; docker-compose exec workspace bash -c  "php artisan $(COMMAND)"
.PHONY: artisan

test:
	cd ${LARADOCK_PATH}; docker-compose exec workspace bash -c  "vendor/bin/phpunit"
.PHONY: test

test-workspace:
	vendor/bin/phpunit --debug --stop-on-error --stop-on-warning
.PHONY: test-workspace

mysql2sqlite:
	./tests/mysql2sqlite ./tests/main-database.sql > ./tests/main-database.sqlite.sql
.PHONY: mysql2sqlite

todo:
	@grep \
		--exclude-dir=vendor \
		--exclude-dir=node_modules \
		--exclude-dir=laradock \
		--exclude-dir=nova \
		--exclude=Makefile \
		--text \
		--color \
		-nRo -E ' TODO:.*|SkipNow' .
.PHONY: todo

setup:
	bash setup.sh
.PHONY: setup

default: up
