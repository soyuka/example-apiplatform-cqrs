.PHONY: all
	all: vendor

vendor: composer.json composer.lock
	composer install

lint:
	php-cs-fixer fix

fixtures: reset
	bin/console hautelook:fixtures:load -n

reset:
	vendor/bin/schema generate-types src config/schema.yaml
	sed -i 's/TV/Tv/g' src/Entity/*.php
	mv src/Entity/TVSeries.php src/Entity/TvSeries.php
	mv src/Entity/TVSeason.php src/Entity/TvSeason.php
	bin/console doctrine:schema:drop --force
	bin/console doctrine:schema:create
