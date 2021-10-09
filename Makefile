define bash-c
	docker-compose exec --user docker app bash -c
endef

up:
	docker-compose up -d
ps:
	docker-compose ps
down:
	docker-compose down
bash:
	docker-compose exec --user docker app bash
init:
	echo DOCKER_UID=`id -u` > .env
	docker-compose build --no-cache
	docker-compose up -d
	$(bash-c) 'composer install'
	$(bash-c) 'touch database/database.sqlite'
	$(bash-c) 'chmod 777 -R storage bootstrap/cache database'
	$(bash-c) 'php artisan migrate'
	$(bash-c) 'php artisan db:seed --class=ApplicantsSeeder'
	$(bash-c) 'php artisan db:seed --class=TimesSeeder'
sqlite:
	$(bash-c) 'sqlite3 database/database.sqlite'
