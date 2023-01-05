### ToDo
- Pre-strike carrier space unit losses calculation in StrikePlugin

## Requirements
Docker

### Setup in project root cli
```
    docker compose -f .docker/dev/docker-compose.yaml build
    docker compose -f .docker/dev/docker-compose.yaml up -d
    docker exec -it gc_combat_dev_php sh
    php bin/composer.phar install
```

or use phing with ./build.xml and execute build:dev

### TestCommand in Container
```
    php console combat:example
```
