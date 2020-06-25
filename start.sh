#!/bin/bash
if [[ $1 == "build" ]]
then
docker-compose up -d --build --no-cache  
fi
docker-compose up -d
