#!/bin/bash

################################################################################
# Author: Jereelton Teixeira
################################################################################

if [[ ! -e ".configure" ]]; then
    echo ""
    echo "[WARNING] The .configure file is not exists, would you create now ?"
    echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
    read -n1 OP

    cp -v ./conf/configure.conf .configure

    echo ""
    echo "[INFO] Now edit the .configure and run again this script !"

    exit
fi

echo ""
echo "[WARNING] Are you sure to configure the dockerized for web projects ?"
echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
read -n1 OP

USERDATA=$(grep "USERDATA" ".configure" | cut -d "=" -f2 | sed -e 's/[ ";]//g');
CONFIGURATION_SETUP=$(grep "CONFIGURATION_SETUP" ".configure" | cut -d "=" -f2 | sed -e 's/[ ";]//g');
SERVICES=$(grep "SERVICES" ".configure" | cut -d "=" -f2 | sed -e 's/[^0-9]//g');
GITHUB_ACCOUNT=$(grep "GITHUB_ACCOUNT" ".configure" | cut -d "=" -f2 | sed -e 's/[ ";]//g');
TARGET_PROJECTS=$(grep "TARGET_PROJECTS" ".configure" | cut -d "=" -f2 | sed -e 's/[ ";]//g');
DOCKER_EXTRA_IMAGES=$(grep "DOCKER_EXTRA_IMAGES" ".configure" | cut -d "=" -f2 | sed -e 's/[ ";]//g');
VERSION=$(grep "VERSION" ".configure" | cut -d "=" -f2 | sed -e 's/[^0-9.]//g');
GATEWAY=$(grep "GATEWAY" ".configure" | cut -d "=" -f2 | sed -e 's/[ ";]//g');
NGINX_CONFIGURE=$(grep "NGINX_CONFIGURE" ".configure" | cut -d "=" -f2 | sed -e 's/[ ";]//g');
RESOURCES=$(grep "RESOURCES" ".configure" | cut -d "=" -f2 | sed -e 's/[ ";]//g');

PHP_V_RESOURCE=$(echo $RESOURCES | grep -E -o '(php-[0-9]+.[0-9]+)')
MYSQL_RESOURCE=$(echo $RESOURCES | grep -o 'mysql')
REDIS_RESOURCE=$(echo $RESOURCES | grep -o 'redis')
NGINX_RESOURCE=$(echo $RESOURCES | grep -o 'nginx')
SUPER_RESOURCE=$(echo $RESOURCES | grep -o 'supervisor')

if [[ "${PHP_V_RESOURCE}" =~ 'php' ]]; then
    PHP_VERSION=$(echo "${PHP_V_RESOURCE}" | cut -d "-" -f2)
    if [[ "${PHP_VERSION}" == "" ]]; then
        echo "[ERROR] PHP Version is missing..."
        exit
    fi
fi

CONFIGURATION="[PROJECT-ENV-CONFIGURATION-START]

[INFO-START]
TIP=\"Use true or NULL value to set the fields below\"
IMPORTANT=\"Don't change the file layout below, only make a changes in the values.\"
[INFO-END]

[GLOBAL-START]
CONFIGURATION_SETUP = \"${CONFIGURATION_SETUP}\"
SERVICES = \"${SERVICES}\"
GITHUB_ACCOUNT = \"${GITHUB_ACCOUNT}\"
TARGET_PROJECTS = \"${TARGET_PROJECTS}\"
DOCKER_EXTRA_IMAGES = \"${DOCKER_EXTRA_IMAGES}\"
RESOURCES = \"${RESOURCES}\"
VERSION = \"${VERSION}\"
GATEWAY = \"${GATEWAY}\"

[DATABASE-START] #USE=\"HOST;PORT;USER;PASSWD;DBNAME\"
DB_SETUP = \"NULL\"
DB_MAIN_MODEL1 = \"NULL\"
DB_MAIN_MODEL2 = \"NULL\"
[DATABASE-END]

[APP-START]
APP_SETUP = \"NULL\"
APP_URL_MODEL1 = \"NULL\"
APP_URL_MODEL2 = \"NULL\"
[APP-END]

[API-START]
API_SETUP = \"NULL\"
API_MODEL1 = \"NULL\"
API_MODEL2 = \"NULL\"
[API-END]

[GLOBAL-END]

[SERVICE-START]
******************************************************************************************
#SERVICE-1
------------------------------------------------------------------------------------------
SERVICE_NAME = \"service_name\"
CONTAINER_NAME = \"container_name\"
IMAGE = \"NULL\"
PRIVILEGED = \"true\"
LOCALHOST_PORT = \"8080\"
INTERNAL_PORT = \"80\"
NETWORKS = \"default,gateway_name_default\"
ENVIRONMENT = \"true\"
    PHP_IDE_CONFIG = \"serverName=docker\"
VOLUMES = \"true\"
    VOLUME = \"./projects/project1/:/var/www/project1/\"
LINKS = \"NULL\"
DEPENDS_ON = \"NULL\"
BUILD = \"dockerfile\"
    CONTEXT = \"./projects/project1/docker/\"
    DOCKERFILE = \"project1.dockerfile\"
WORKING_DIR = \"/var/www/project1\"
COMMAND = \"NULL\"
ENV_FILE = \"NULL\"
DEPLOY = \"NULL\"
DEPLOY_MEMORY = \"NULL\"
PROJECT_NAME = \"project1\"
NGINX_SERVER_NAME = \"NULL\"
NGINX_CONF = \"NULL\"
NGINX_ROOT_PATH = \"NULL\"
NGINX_APP_CONF = \"NULL\"
NGINX_LISTEN = \"NULL\"
NGINX_FAST_CGI_PASS = \"NULL\"
NGINX72_CONF = \"NULL\"
NGINX72_RESTFUL_CONF = \"NULL\"
SUPERVISOR_CONF = \"NULL\"
******************************************************************************************
#SERVICE-2
------------------------------------------------------------------------------------------
SERVICE_NAME = \"project2\"
CONTAINER_NAME = \"project2\"
IMAGE = \"nginx:latest\"
PRIVILEGED = \"true\"
LOCALHOST_PORT = \"8888\"
INTERNAL_PORT = \"80\"
NETWORKS = \"default,gateway_name_default\"
ENVIRONMENT = \"true\"
    PHP_IDE_CONFIG = \"serverName=docker\"
VOLUMES = \"true\"
	VOLUME = \"./projects/project2:/var/www/html\"
LINKS = \"true\"
    LINK = \"php_project2\"
    LINK = \"redis\"
    LINK = \"mysql\"
DEPENDS_ON = \"NULL\"
BUILD = \"NULL\"
WORKING_DIR = \"NULL\"
COMMAND = \"NULL\"
ENV_FILE = \"NULL\"
DEPLOY = \"NULL\"
DEPLOY_MEMORY = \"NULL\"
PROJECT_NAME = \"project2\"
NGINX_SERVER_NAME = \"NULL\"
NGINX_CONF = \"NULL\"
NGINX_ROOT_PATH = \"/var/www/html/public\"
NGINX_APP_CONF = \"./projects/nginx/project2/app.conf:/etc/nginx/conf.d/default.conf\"
NGINX_LISTEN = \"80\"
NGINX_FAST_CGI_PASS = \"php_project2:9000\"
NGINX72_CONF = \"NULL\"
NGINX72_RESTFUL_CONF = \"NULL\"
SUPERVISOR_CONF = \"NULL\"
******************************************************************************************
#SERVICE-3
------------------------------------------------------------------------------------------
SERVICE_NAME = \"php_project2\"
CONTAINER_NAME = \"php_project2\"
IMAGE = \"NULL\"
PRIVILEGED = \"true\"
LOCALHOST_PORT = \"NULL\"
INTERNAL_PORT = \"NULL\"
NETWORKS = \"NULL\"
ENVIRONMENT = \"true\"
    PHP_IDE_CONFIG = \"serverName=docker\"
VOLUMES = \"true\"
	VOLUME = \"./projects/project2:/var/www/html\"
LINKS = \"NULL\"
DEPENDS_ON = \"NULL\"
BUILD = \"./projects/php/8.0\"
WORKING_DIR = \"/var/www/html\"
COMMAND = \"NULL\"
ENV_FILE = \"NULL\"
DEPLOY = \"NULL\"
DEPLOY_MEMORY = \"NULL\"
PROJECT_NAME = \"project2\"
NGINX_SERVER_NAME = \"NULL\"
NGINX_CONF = \"NULL\"
NGINX_ROOT_PATH = \"/var/www/html/public\"
NGINX_APP_CONF = \"./projects/nginx/project2/app.conf:/etc/nginx/conf.d/default.conf\"
NGINX_LISTEN = \"80\"
NGINX_FAST_CGI_PASS = \"php_project2:9000\"
NGINX72_CONF = \"NULL\"
NGINX72_RESTFUL_CONF = \"NULL\"
SUPERVISOR_CONF = \"NULL\"
******************************************************************************************
#SERVICE-4
------------------------------------------------------------------------------------------
SERVICE_NAME = \"redis\"
CONTAINER_NAME = \"redis\"
IMAGE = \"NULL\"
PRIVILEGED = \"NULL\"
LOCALHOST_PORT = \"6969,6700,6479,6701\"
INTERNAL_PORT = \"6379,6379,6979,6379\"
NETWORKS = \"NULL\"
ENVIRONMENT = \"NULL\"
VOLUMES = \"NULL\"
LINKS = \"NULL\"
DEPENDS_ON = \"NULL\"
BUILD = \"./projects/redis\"
WORKING_DIR = \"NULL\"
COMMAND = \"NULL\"
ENV_FILE = \"NULL\"
DEPLOY = \"NULL\"
DEPLOY_MEMORY = \"NULL\"
PROJECT_NAME = \"NULL\"
NGINX_SERVER_NAME = \"NULL\"
NGINX_CONF = \"NULL\"
NGINX_ROOT_PATH = \"NULL\"
NGINX_APP_CONF = \"NULL\"
NGINX_LISTEN = \"NULL\"
NGINX_FAST_CGI_PASS = \"NULL\"
NGINX72_CONF = \"NULL\"
NGINX72_RESTFUL_CONF = \"NULL\"
SUPERVISOR_CONF = \"NULL\"
******************************************************************************************
#SERVICE-5
------------------------------------------------------------------------------------------
SERVICE_NAME = \"mysql\"
CONTAINER_NAME = \"mysql\"
IMAGE = \"mysql\"
PRIVILEGED = \"NULL\"
LOCALHOST_PORT = \"3308\"
INTERNAL_PORT = \"3306\"
NETWORKS = \"NULL\"
ENVIRONMENT = \"true\"
    MYSQL_ROOT_PASSWORD = \"root\"
    MYSQL_USERNAME = \"root\"
    MYSQL_DATABASE = \"dbname\"
VOLUMES = \"true\"
    VOLUME = \"./projects/mysql/:/var/lib/mysql/\"
LINKS = \"NULL\"
DEPENDS_ON = \"NULL\"
BUILD = \"\"
WORKING_DIR = \"NULL\"
COMMAND = \"--default-authentication-plugin=mysql_native_password --sql_mode=''\"
ENV_FILE = \"NULL\"
DEPLOY = \"NULL\"
DEPLOY_MEMORY = \"NULL\"
PROJECT_NAME = \"NULL\"
NGINX_CONF = \"NULL\"
NGINX_SERVER_NAME = \"NULL\"
NGINX_ROOT_PATH = \"NULL\"
NGINX_APP_CONF = \"NULL\"
NGINX_LISTEN = \"NULL\"
NGINX_FAST_CGI_PASS = \"NULL\"
NGINX72_CONF = \"NULL\"
NGINX72_RESTFUL_CONF = \"NULL\"
SUPERVISOR_CONF = \"NULL\"
******************************************************************************************
[SERVICE-END]

[PROJECT-ENV-CONFIGURATION-END]"

if [[ -e configuration.conf ]]; then
    cp configuration.conf configuration.conf.bkp
fi

if [[ -e .userdata ]]; then
    cp .userdata .userdata.bkp
fi

touch configuration.conf
touch .userdata

echo "${CONFIGURATION}" > configuration.conf
echo "${USERDATA}" > .userdata

if [[ ! -e projects ]]; then
    mkdir projects
fi

if [[ ! -e projects/nginx && ${NGINX_RESOURCE} == "nginx" ]]; then
    mkdir -p projects/nginx
    chmod 777 projects/nginx

    PROJECTS_LIST=('"'$(grep "TARGET_PROJECTS" ".configure" | sed -e 's/[ "]//g' | cut -d "=" -f2 | sed -e 's/,/" "/g')'"')

    for ((i = 0; i < ${#PROJECTS_LIST[@]}; i++)); do
        PROJECT=$(echo "${PROJECTS_LIST[$i]}" | sed -e 's/"//g')

        echo "----------------------------"
        echo ${PROJECT}
        echo "----------------------------"

        #APP
        ################################################################################
        if [[ -e "conf/nginx/${PROJECT}.app.conf" ]];
        then
            mv -v "conf/nginx/${PROJECT}.app.conf" "conf/nginx/${PROJECT}.app.conf.bkp"
        fi

        cp -v "conf/app.tpl" "conf/nginx/${PROJECT}.app.conf"

        #NGINX
        ################################################################################
        if [[ -e "conf/nginx/${PROJECT}.nginx.conf" ]];
        then
            mv -v "conf/nginx/${PROJECT}.nginx.conf" "conf/nginx/${PROJECT}.nginx.conf.bkp"
        fi

        cp -v "conf/nginx.tpl" "conf/nginx/${PROJECT}.nginx.conf"

        #NGINX72
        ################################################################################
        if [[ -e "conf/nginx/${PROJECT}.nginx72.conf" ]];
        then
            mv -v "conf/nginx/${PROJECT}.nginx72.conf" "conf/nginx/${PROJECT}.nginx72.conf.bkp"
        fi

        cp -v "conf/nginx72.tpl" "conf/nginx/${PROJECT}.nginx72.conf"

        #NGINX72RESTFULL
        ################################################################################
        if [[ -e "conf/nginx/${PROJECT}.nginx72-restful.conf" ]];
        then
            mv -v "conf/nginx/${PROJECT}.nginx72-restful.conf" "conf/nginx/${PROJECT}.nginx72-restful.conf.bkp"
        fi

        cp -v "conf/nginx72-restful.tpl" "conf/nginx/${PROJECT}.nginx72-restful.conf"

    done
fi

if [[ ! -e projects/php && ${PHP_V_RESOURCE} =~ "php" ]]; then
    mkdir -p "projects/php"
    chmod 777 "projects/php"
    mkdir -p "projects/php/${PHP_VERSION}"
    chmod 777 "projects/php/${PHP_VERSION}"
    cp -v "conf/php/${PHP_VERSION}/Dockerfile" "projects/php/${PHP_VERSION}/Dockerfile"
    chmod 777 "projects/php/${PHP_VERSION}/Dockerfile"
fi

if [[ ${SUPER_RESOURCE} == "supervisor" ]];
then

    PROJECTS_LIST=('"'$(grep "TARGET_PROJECTS" ".configure" | sed -e 's/[ "]//g' | cut -d "=" -f2 | sed -e 's/,/" "/g')'"')

    for ((i = 0; i < ${#PROJECTS_LIST[@]}; i++)); do

        PROJECT=$(echo "${PROJECTS_LIST[$i]}" | sed -e 's/"//g')

        if [[ -e "conf/nginx/${PROJECT}.supervisor.conf" ]];
        then
            mv -v "conf/nginx/${PROJECT}.supervisor.conf" "conf/nginx/${PROJECT}.supervisor.conf.bkp"
        fi

        cp -v "conf/supervisor.tpl" "conf/nginx/${PROJECT}.supervisor.conf"

    done
fi

if [[ ! -e projects/redis && ${REDIS_RESOURCE} == "redis" ]]; then
    mkdir -p projects/redis
    chmod 777 projects/redis

    cp -rv conf/redis/* projects/redis/
fi

if [[ ! -e projects/mysql && ${MYSQL_RESOURCE} == "mysql" ]]; then
    mkdir -p projects/mysql
    chmod 777 projects/mysql
fi

echo ""
echo "Configure is done !"
echo "Now you can run envinit.sh install skip|repo true|false"
echo ""
