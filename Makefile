define bash-c
	docker-compose exec --user docker app bash -c
endef

define deploy-c
	docker-compose -f docker-compose-deploy.yml exec -T app bash -c
endef

define nginxproxy-c
	docker-compose -f docker-compose-nginxproxy.yml exec -T app bash -c
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
sqlite:
	$(bash-c) 'sqlite3 database/database.sqlite'

deploy:
	docker-compose -f docker-compose-deploy.yml build --no-cache
	docker-compose -f docker-compose-deploy.yml up -d
	$(deploy-c) 'composer install'
	$(deploy-c) 'touch database/database.sqlite'
	$(deploy-c) 'chmod 777 -R storage bootstrap/cache database'
	$(deploy-c) 'php artisan migrate'
deploy-up:
	docker-compose -f docker-compose-deploy.yml up -d
deploy-down:
	docker-compose -f docker-compose-deploy.yml down
deploy-phpunit:
	$(deploy-c) 'vendor/bin/phpunit'

nginxproxy:
	docker-compose -f docker-compose-nginxproxy.yml build --no-cache
	docker-compose -f docker-compose-nginxproxy.yml up -d
	$(nginxproxy-c) 'composer install'
	$(nginxproxy-c) 'touch database/database.sqlite'
	$(nginxproxy-c) 'chmod 777 -R storage bootstrap/cache database'
	$(nginxproxy-c) 'php artisan migrate'
nginxproxy-up:
	docker-compose -f docker-compose-nginxproxy.yml up -d
nginxproxy-down:
	docker-compose -f docker-compose-nginxproxy.yml down
nginxproxy-phpunit:
	$(nginxproxy-c) 'vendor/bin/phpunit'
