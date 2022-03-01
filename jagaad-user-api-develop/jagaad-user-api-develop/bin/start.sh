#!/bin/bash

cd `dirname $0`/..;

COLOUR_GREEN=$(tput setaf 2)
RESET=$(tput sgr0)

for arg in "$@"; do
    if [[ $arg == --env* ]]; then
        IFS='='; env=($arg); unset IFS;
        argEnv="${env[1]}"
    elif [[ $arg == --port* ]]; then
        IFS='='; port=($arg); unset IFS;
        argHostPort="${port[1]}"
    elif [ $arg == --build ]; then
        IFS='='; build=($arg); unset IFS;
        argBuild="${build}"
    else
        args+=($arg)
    fi
done

ENVIRONMENT=${argEnv:-${ENVIRONMENT:-'dev'}}
BUILD=${argBuild:-${BUILD:-''}}
HOST_PORT=${argHostPort:-${HOST_PORT:-''}}

case "${ENVIRONMENT}" in
     'dev')
        files=("docker-compose.yml" "docker-compose.dev.yml")

        # For M1 macs
        if [[ `uname -m` == 'arm64' ]]; then
            files+=( "docker-compose.dev.arm64.yml" )
        fi

        if [[ ${BUILD} == --build ]]; then
            echo "${COLOUR_GREEN}Building dev environment...${RESET}"

            docker-compose`printf -- \ -f\ %s ${files[@]}` build
            docker-compose`printf -- \ -f\ %s ${files[@]}` up -d
            docker-compose exec php composer install
        else
            echo "${COLOUR_GREEN}Starting built dev environment...${RESET}"
            docker-compose`printf -- \ -f\ %s ${files[@]}` up -d ${args[@]}
        fi
          ;;
     'stage')
        cd "./../${PWD}"

        echo "${COLOUR_GREEN}Building stage environment from ${PWD} directory...${RESET}"

        docker build -t php-fpm . -f ./docker/stage/php-fpm/Dockerfile
        docker build -t nginx . -f ./docker/stage/nginx/Dockerfile
        docker network create user-api-network

        docker run -d --name user-api-php --net user-api-network php-fpm

        echo "Linking nginx on port ${HOST_PORT} to php-fpm"

        docker run -d -p "$HOST_PORT:8888" --net user-api-network --name user-api-nginx  nginx
          ;;
     'prod')
          echo "Warning: please update start.sh for production version"
          ;;
esac
