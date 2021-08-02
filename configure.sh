#!/bin/bash

################################################################################
# Author: Jereelton Teixeira
################################################################################

echo ""
echo "[WARNING] Are you sure to configure the dockerized for web projects ?"
echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
read -n1 OP

CONFIGURATION="[PROJECT-ENV-CONFIGURATION-START]

[INFO-START]
TIP=\"Use true or NULL value to set the fields below\"
IMPORTANT=\"Don't change the file layout below, only make a changes in the values.\"
[INFO-END]

[GLOBAL-START]
CONFIGURATION_SETUP = \"true\"
SERVICES = \"3\"
GITHUB_ACCOUNT = \"github_account\"
TARGET_PROJECTS = \"project1, project2\"
DOCKER_EXTRA_IMAGES = \"extra_image1_remove, extra_image2_remove\"
VERSION = \"3.2\"
GATEWAY = \"gateway_name_default\"

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
NGINX_ROOT_PATH = \"NULL\"
NGINX_CONF = \"NULL\"
NGINX_APP_CONF = \"NULL\"
NGINX_LISTEN = \"NULL\"
NGINX_FAST_CGI_PASS = \"NULL\"
NGINX72_CONF = \"NULL\"
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
NGINX_ROOT_PATH = \"/var/www/html/public\"
NGINX_CONF = \"NULL\"
NGINX_APP_CONF = \"./projects/nginx/project2/app.conf:/etc/nginx/conf.d/default.conf\"
NGINX_LISTEN = \"80\"
NGINX_FAST_CGI_PASS = \"php_project2:9000\"
NGINX72_CONF = \"NULL\"
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
NGINX_ROOT_PATH = \"/var/www/html/public\"
NGINX_CONF = \"NULL\"
NGINX_APP_CONF = \"./projects/nginx/project2/app.conf:/etc/nginx/conf.d/default.conf\"
NGINX_LISTEN = \"80\"
NGINX_FAST_CGI_PASS = \"php_project2:9000\"
NGINX72_CONF = \"NULL\"
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
NGINX_ROOT_PATH = \"NULL\"
NGINX_CONF = \"NULL\"
NGINX_APP_CONF = \"NULL\"
NGINX_LISTEN = \"NULL\"
NGINX_FAST_CGI_PASS = \"NULL\"
NGINX72_CONF = \"NULL\"
SUPERVISOR_CONF = \"NULL\"
******************************************************************************************
#SERVICE-5
------------------------------------------------------------------------------------------
SERVICE_NAME = \"mysql\"
CONTAINER_NAME = \"mysql\"
IMAGE = \"mysql\"
PRIVILEGED = \"NULL\"
LOCALHOST_PORT = \"8700\"
INTERNAL_PORT = \"3306\"
NETWORKS = \"NULL\"
ENVIRONMENT = \"true\"
    MYSQL_ROOT_PASSWORD = \"root\"
    MYSQL_DATABASE = \"fis\"
VOLUMES = \"true\"
    VOLUME = \"./projects/project2/environment/mysql:/var/lib/mysql\"
LINKS = \"NULL\"
DEPENDS_ON = \"NULL\"
BUILD = \"\"
WORKING_DIR = \"NULL\"
COMMAND = \"--default-authentication-plugin=mysql_native_password --sql_mode=''\"
ENV_FILE = \"NULL\"
DEPLOY = \"NULL\"
DEPLOY_MEMORY = \"NULL\"
PROJECT_NAME = \"NULL\"
NGINX_ROOT_PATH = \"NULL\"
NGINX_CONF = \"NULL\"
NGINX_APP_CONF = \"NULL\"
NGINX_LISTEN = \"NULL\"
NGINX_FAST_CGI_PASS = \"NULL\"
NGINX72_CONF = \"NULL\"
SUPERVISOR_CONF = \"NULL\"
******************************************************************************************
[SERVICE-END]

[PROJECT-ENV-CONFIGURATION-END]"

USERDATA="username:your_token"

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

echo ""
echo "Configure is done !"
echo ""
