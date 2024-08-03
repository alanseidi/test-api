include .env

install:
	docker run --rm -u "$(shell id -u):$(shell id -g)" -v $(shell pwd):/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs

update:
	docker run --rm -u "$(shell id -u):$(shell id -g)" -v $(shell pwd):/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer update

start:
	./vendor/bin/sail up

swagger:
	./vendor/bin/sail artisan l5-swagger:generate

fresh-seed:
	./vendor/bin/sail artisan migrate:fresh --seed

run-migration:
	./vendor/bin/sail artisan migrate

test-feature:
	./vendor/bin/sail artisan make:test $(name)

test-unit:
	./vendor/bin/sail artisan make:test $(name) --unit

run-test:
	./vendor/bin/sail php vendor/bin/phpunit $(test)

controller:
	./vendor/bin/sail artisan make:controller

model:
	./vendor/bin/sail artisan make:model

resource:
	./vendor/bin/sail artisan make:resource

request:
	./vendor/bin/sail artisan make:request

migration:
	./vendor/bin/sail artisan make:migration

service:
	./vendor/bin/sail artisan make:class \Services\\$(name)
