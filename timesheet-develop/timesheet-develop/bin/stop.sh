#!/bin/bash

cd `dirname $0`/..
docker container stop `docker ps -f name=jagaad-witcher -q`
echo "Containers stopped!"
