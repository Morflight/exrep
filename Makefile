init:
	cp .env.dist .env \
	&& docker-compose up --build -d \
	&& docker exec -it -u $$(id -u) exrep_php bash -c 'composer install --no-scripts --no-interaction' \
	&& docker exec -it -u $$(id -u) exrep_php bash -c 'php bin/console lexik:jwt:generate-keypair --overwrite --no-interaction' \
	&& docker exec -it -u $$(id -u) exrep_php bash -c 'php bin/console doctrine:schema:create' \
	&& docker exec -it -u $$(id -u) exrep_php bash -c 'php bin/console doctrine:fixtures:load --no-interaction'

reset:
	docker exec -it -u $$(id -u) exrep_php bash -c 'php bin/console doctrine:schema:drop --force' \
	&& docker-compose down

