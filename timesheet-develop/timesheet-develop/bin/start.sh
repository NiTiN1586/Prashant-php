#!/bin/bash

export DOCKER_SCAN_SUGGEST=false

COLOUR_GREEN=$(tput setaf 2)
RESET=$(tput sgr0)

cd `dirname $0`/..;

for arg in "$@"; do
    if [[ $arg == --env* ]]; then
        IFS='='; env=($arg); unset IFS;
        argEnv="${env[1]}"
    elif [ $arg == --build ]; then
        IFS='='; build=($arg); unset IFS;
        argBuild="${build}"
    else
        args+=($arg)
    fi
done

ENVIRONMENT=${argEnv:-${ENVIRONMENT:-'dev'}}
BUILD=${argBuild:-${BUILD:-''}}

files=( "docker-compose.yml" )
envSpecificFile="docker-compose.${ENVIRONMENT}.yml"

if [[ -f $envSpecificFile ]]; then
    files+=( $envSpecificFile )
else
    echo "Warning: file '$envSpecificFile' doesn't exist"
fi 

# For M1 macs
if [[ `uname -m` == 'arm64' ]]; then
    files+=( "docker-compose.dev.arm64.yml" )
fi

if [[ ${BUILD} == --build ]]; then
    echo "${COLOUR_GREEN}Building environment...${RESET}"

    docker-compose`printf -- \ -f\ %s ${files[@]}` build
    docker-compose`printf -- \ -f\ %s ${files[@]}` up -d
    docker-compose exec php composer install

else
    echo "${COLOUR_GREEN}Starting existing environment...${RESET}"
    docker-compose`printf -- \ -f\ %s ${files[@]}` up -d ${args[@]}
fi
