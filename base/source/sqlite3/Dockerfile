FROM ubuntu:20.04

## UPDATE SYSTEM
RUN apt-get update -y && apt-get upgrade -y

# ESSENTIALS
RUN apt-get install -y build-essential tar wget nano

## PREPARE
#RUN mkdir -p /data/sqlite3/
#RUN chmod 777 /data -R
#
### COPY SOURCE CODE
#COPY ./projects/sqlite3/sqliteDB/sqlite-autoconf/ /data/
#sudo apt install sqlite3
#sudo apt install sqlite3 libsqlite3-dev
#sudo add-apt-repository ppa:linuxgndu/sqlitebrowser
#sudo apt install sqlitebrowser
#
#### CONFIGURE & INSTALATION
#RUN chmod 777 /data -R
#RUN cd /data/sqlite-autoconf/
#RUN ./configure
#RUN make
#RUN make install
#
#### CHECK VERSION INSTALED
#RUN sqlite3 --version

## PORT ACCESS
EXPOSE 5050

## PROCESS FINALLY
#CMD ['bash']