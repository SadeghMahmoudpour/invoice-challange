dc := docker-compose
exec := $(dc) exec
webexec := $(exec) --user application -w /app
rwebexec := $(exec) --user root -w /app
status:
	$(dc) ps

up:
	$(dc) up -d --build

stop:
	$(dc) stop

down:
	$(dc) down

restart:
	$(dc) restart

sh:
	$(webexec) web bash

rsh:
	$(rwebexec) web bash

install:
	$(webexec) web composer install

seed:
	$(webexec) web php bin/console doctrine:fixtures:load --no-interaction

init: up install seed

test:
	$(webexec) web php ./vendor/bin/phpunit