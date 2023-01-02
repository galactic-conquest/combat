### ToDo
- Pre-strike carrier space unit losses calculation in StrikePlugin

## Requirements
Docker

### Setup in project root cli, one by one
```
    COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker compose -f .docker/dev/docker-compose.yaml build
    docker compose -f .docker/dev/docker-compose.yaml up -d
    docker exec -it gc_combat_dev_php sh
    $(which php) bin/composer.phar install
    $(which php) bin/composer.phar dump-autoload --optimize
```

or use phing with ./build.xml and execute build:dev

### TestCommand in Container
```
    $(which php) console combat:example
```
