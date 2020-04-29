#!/bin/bash
if [[ $1 == "build" ]]
then
docker-compose up -d --build 
fi
docker-compose up -d
