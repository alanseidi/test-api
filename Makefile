include .env

install:
	docker run --rm -u "$(shell id -u):$(shell id -g)" -v $(shell pwd):/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs

update:
	docker run --rm -u "$(shell id -u):$(shell id -g)" -v $(shell pwd):/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer update

start:
	./vendor/bin/sail up
