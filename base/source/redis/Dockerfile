FROM ubuntu:20.04

## UPDATE
RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y software-properties-common

## REDIS INSTALL
RUN add-apt-repository ppa:redislabs/redis
RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y build-essential tar wget nano
RUN apt-get install -y redis

## PORT ACCESS
EXPOSE 6379

## PROCESS FINALLY
CMD ['redis-server']