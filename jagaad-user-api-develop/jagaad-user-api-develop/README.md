# Jagaad User API

Service for creation of Jagaad system users. 


## Requirements
1. Docker v18.02.0+
2. Docker compose v3.6
5. MySQL 8.0
6. Nginx
7. PHP >=8.0

## Dev Environment Installation

1. Copy **.env** file into **.env.local** and populate all environment variables;
2. To setup project invoke **./bin/start.sh --env="dev" --build** script;
3. If you want only to start project **./bin/start.sh --env="dev"**
4. Invoke **docker-compose exec php bin/console --no-interaction doctrine:migration:migrate**

## Usage

    Use the following url to open api documentation http://localhost:8888/api

## Communication between jagaad-user-api and other services

    To access jagaad-user-api from other services api token is required. Generate token with the following command and duplicate into JAGAAD_USER_API_TOKEN environment variable into your project

```shell
docker-compose exec php bin/console api_token:create --create="Application name" - To create API token
docker-compose exec php bin/console api_token:create --rotate="Application name" - To rotate API token for application
```

### To run available code-style fixing and analyzing tools use **analyze.sh** script

```shell
./bin/analyze.sh              --help      # Display help
./bin/analyze.sh              --all       # Run all tools
./bin/analyze.sh              --phpstan   # Run phpstan
./bin/analyze.sh              --fixer     # Run cs-fixer
./bin/analyze.sh              --validate  # Validate database schema
./bin/analyze.sh              --phpunit   # Run phpunit tests
```


### Information for DevOps

1. All configuration files for dev are allocated in **docker/stage**
2. PHP-FPM and Nginx are allocated in different containers and lined together via **user-api-network** network
3. To invoke stage container use first paragraph command in instruction below

```shell
./bin/start.sh                --env="stage" --port="9999"      # Port argument is available only on stage environment. It defines host port, container port is always 8888
./bin/start.sh                --env="dev" --build              # Build dev environment
./bin/start.sh                --env="dev"                      # Run dev environment that was previously built
```