#!/bin/bash

cd `dirname $0`/..
docker container stop `docker ps -f name=jagaad-user-api -q`
echo "Containers stopped!"
