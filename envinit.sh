#!/bin/bash

################################################################################
# Author: Jereelton Teixeira
################################################################################

STARTTIME=$(date +%T)

echo "*************************************************************************"
echo "Welcome to Dockerized Environment for Web developer..."
echo "*************************************************************************"

SAVE_PATH="${PWD}"

#System commands check-in

#Git installed ?
CHECKIN1=$(dpkg -l | grep git)
CHECKIN2=$(builtin type -P git)
apt show git >> /dev/null 2>&1
CHECKIN3=$?
if [[ $CHECKIN1 == "" && $CHECKIN2 == "" && $CHECKIN3 != "0" ]]; then
    echo "Error: The git is not installed in your system !"
    exit
fi

#Docker installed ?
CHECKIN1=$(dpkg -l | grep docker)
CHECKIN2=$(builtin type -P docker)
apt show docker >> /dev/null 2>&1
CHECKIN3=$?
if [[ $CHECKIN1 == "" && $CHECKIN2 == "" && $CHECKIN3 != "0" ]]; then
    echo "Error: The docker is not installed in your system !"
    exit
fi

#Docker Compose installed ?
CHECKIN1=$(dpkg -l | grep docker-compose)
CHECKIN2=$(builtin type -P docker-compose)
apt show docker-compose >> /dev/null 2>&1
CHECKIN3=$?
if [[ $CHECKIN1 == "" && $CHECKIN2 == "" && $CHECKIN3 != "0" ]]; then
    echo "Error: The docker is not installed in your system !"
    exit
fi

#User data file checkin
if [[ ! -e .userdata ]]; then
	echo "Error: Userdata File not Found !"
	exit
fi

USERNAME=$(cat .userdata | cut -d ":" -f1)
PASSWORD=$(cat .userdata | cut -d ":" -f2)

if [[ $USERNAME == "" || $PASSWORD == "" ]]; then
	echo "Error: USERNAME or PASSWORD not found: use USERNAME:PASSWORD in .userdata file"
	exit
fi

#Configuration file checkin
CONFIGURATION_FILE="configuration.conf"
if [[ ! -e ${CONFIGURATION_FILE} ]]; then
	echo "Error: Configuration File not Found !"
	exit
fi

PROJECTS=('"'$(grep "TARGET_PROJECTS" "${CONFIGURATION_FILE}" | sed -e 's/[ "]//g' | cut -d "=" -f2 | sed -e 's/,/" "/g')'"')
DIRS=$(echo ${PROJECTS[@]} | sed -e 's/"//g')
CONFIGURATION=$(grep "CONFIGURATION_SETUP" "${CONFIGURATION_FILE}" | sed -e 's/[ "]//g' | cut -d "=" -f2)

if [[ $CONFIGURATION == "true" ]];
then
    for ((i = 0; i < ${#PROJECTS[@]}; i++)); do
        PROJECT=$(echo "${PROJECTS[$i]}" | sed -e 's/"//g')
        if [[ ! -e "conf/env/${PROJECT}.env.tpl" ]];
        then
            echo "[WARNING] Missing env file" "conf/env/${PROJECT}.env.tpl to CONFIGURATION_SETUP=true"
           # exit
        fi
    done
fi

GATEWAY=$(grep "GATEWAY" "${CONFIGURATION_FILE}" | sed -e 's/[" ]//g' | cut -d "=" -f2)

if [[ $GATEWAY == "" ]];
then
    echo "Error: Missing GATEWAY Network to Docker Compose create..."
    exit
fi

#Git data checkin
GITHUB_ACCOUNT=$(grep "GITHUB_ACCOUNT" "${CONFIGURATION_FILE}" | sed -e 's/[" ]//g' | cut -d "=" -f2)

if [[ $GITHUB_ACCOUNT == "" ]];
then
    echo "Error: Missing GitHUB Account in configuration file"
    exit
fi

GITUSER=$USERNAME
GITPASS=$PASSWORD
GITHUB="github.com/${GITHUB_ACCOUNT}/{{{PROJECT_NAME}}}.git"

#Generic data definition
PARAM1=$1
PARAM2=$2
PARAM3=$3
UNINSTALL_FILE="uninstall.txt"
LOCKER_FILE="installer.lock"
DOCKER_YML_PROJECTS="docker-compose.yml"

if [[ ! $PARAM1 || ! $PARAM2 ]]; then
    echo "-------------------------------------------------------------"
    echo "[ERROR] Please inform correctly all parameters"
    echo "./envinit.sh [install|delete] [repo|skip] [configuration: true|false]"
    exit
fi

if ! echo $PARAM1 | egrep -E '(delete|install)' >> /dev/null 2>&1
then
    echo "[ERROR] Parameter 1 is wrong, please use install or delete"
    exit
fi

if ! echo $PARAM2 | egrep -E '(repo|skip)' >> /dev/null 2>&1
then
    echo "[ERROR] Parameter 2 is wrong, please use repo or skip"
    exit
fi

if [[ $PARAM3 != "" ]]; then
    if ! echo $PARAM3 | egrep -E '(true|false)' >> /dev/null 2>&1
    then
        echo "[ERROR] Parameter 3 is wrong, please use repo or skip"
        exit
    fi
fi

#If delete environment
if [[ $PARAM1 == "delete" ]];
then

    #Docker Images
    DOCKER_IMAGES=$(echo ${DIRS} | sed -e 's/ /\|/g')
    DOCKER_EXTRA_IMAGES=$(grep "DOCKER_EXTRA_IMAGES" ${CONFIGURATION_FILE} | sed -e 's/[" ]//g' | cut -d "=" -f2 | sed -e 's/,/\|/g')
    DOCKER_IMAGES="${DOCKER_IMAGES}|${DOCKER_EXTRA_IMAGES}"
    DOCKER_SHOW=$(echo ${DOCKER_IMAGES} | tr '|' '\n')

    echo ""
    echo "[WARNING] The environment will be removed completely !"
    echo "***********************************************************************"
    echo "The projects and images below, will be uninstalled !"
    echo "${DOCKER_SHOW}"
    echo "***********************************************************************"
    echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
    read -n1 OP

    echo ""
    echo "Uninstall Starting..."
    echo ""

    #Information to remove images from current docker
    IMAGES=$(docker images -a | egrep "(REPOSITORY|${DOCKER_IMAGES})")

    echo "======================================================================="
    touch "${UNINSTALL_FILE}"
    echo "${IMAGES}" > "${UNINSTALL_FILE}"
    echo "" >> "${UNINSTALL_FILE}"
    cat "${UNINSTALL_FILE}"

    echo "======================================================================="
    echo "[WARNING]: The above images will be removed from docker !"
    echo "Continue ? Press [Enter] = Yes, or press [Esc] = No"
    read -n1 OP

    if [[ $OP != "" ]];
    then
        echo "*********************"
        echo "* Uninstall aborted !"
        echo "*********************"
        exit
    fi

    echo "Trying Stop Image from Docker Compose..."
    docker-compose stop
    sleep 2

    while IFS= read -r line || [[ -n "$line" ]]; do

        REPO_TAG=$(echo "$line" | grep -v "REPOSITORY" | sed -e 's/ /:/' | awk '{print $1$2}')

        if [[ $line == "" || $line == " " || $REPO_TAG == "" || $REPO_TAG == " " ]];
        then
            continue
        fi

        REPOSITORY=$(echo ${REPO_TAG} | cut -d ":" -f1)
        PROCESS=$(docker ps -a | grep "${REPOSITORY}")
        ID=$(echo "${PROCESS}" | awk '{print $1}')
        NAME=$(echo "${PROCESS}" | awk '{print $12}')

        if [[ $REPO_TAG != "" && $ID != "" && $NAME != "" ]];
        then
            echo " >>> 1: Removing ${REPO_TAG}"
            docker stop "${ID}"
            docker rmi "${REPO_TAG}"
            docker image rm "${REPO_TAG}"
            docker rm -v "${ID}"
            docker container rm "${NAME}"
            echo " >>> Done";
        elif [[ $REPO_TAG != "" && $REPOSITORY != "" ]]; then
            echo " >>> 2: Removing ${REPO_TAG}"
            docker rmi "${REPO_TAG}"
            docker image rm "${REPO_TAG}"
            docker container rm "${NAME}"
            echo " >>> Done";
        fi

        REPO_TAG=""
        REPOSITORY=""
        PROCESS=""
        ID=""
        NAME=""

    done < "${UNINSTALL_FILE}"

    echo ""
    ID_NETWORK=$(docker network ls | grep ${GATEWAY} | cut -d " " -f1)
    echo "Removing network: [$ID_NETWORK]"
    if [[ $ID_NETWORK != "" ]]; then
        docker network rm ${ID_NETWORK}
    fi
    echo ""

    #Remove directories and lock files
    echo "Removing directories and lock files..."

    #rm -rf ${DIRS}
    if [[ $PARAM2 == "repo" ]]; then

        echo ""
        echo "[WARNING] Are you sure to remove all project files ?"
        echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
        read -n1 OP

        rm -rf ./projects
    else
        echo "[INFO] Skipping repositories..."
    fi

    rm -rfv "${LOCKER_FILE}"
    rm -rfv "${UNINSTALL_FILE}"

    if [[ $PARAM3 == true ]]; then
        rm -rfv "./projects/${DOCKER_YML_PROJECTS}"
        rm -rfv "${DOCKER_YML_PROJECTS}"
    fi

    echo ""
    echo "[WARNING] Please, check the information below !"
    echo ""

    echo ""
    echo "[WARNING] Would like you remove all resources from docker ?"
    echo "Containers, Images, Volumes, Networks and all dependencies...."
    echo ""
    echo "Please type yes or no: "
    read  OP

    if [[ $OP == "yes" ]]; then
        echo "Please wait..."

        docker container rm $(docker container ls -a -q)
        docker container rm --force $(docker container ls -a -q)
        docker images rm --force $(docker images ls -a -q)
        docker images rm $(docker images ls -a -q)
        docker volume rm $(docker volume ls -q)
        docker image rm $(docker image ls -a -q)
        docker rmi $(docker image ls -a -q)
        docker rmi --force $(docker images -q)

    fi

	echo "Uninstall Finish..."
	exit
fi

#Start Install Process
if [[ -e ${LOCKER_FILE} ]];
then
	echo "Error: The environment is already installed !"
	echo "Use ./envinit.sh delete [options] to remove all settings..."
	exit
fi

echo ""
echo "The installation will be initialized !"
echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
read -n1 OP

echo ""
echo "Install Starting, please wait..."
echo ""

sleep 2

touch ${LOCKER_FILE}

if [[ $PARAM1 == "install" && $PARAM2 == "repo" ]]; then

    echo ""
    echo "[WARNING] Are you sure to remove all project files ?"
    echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
    read -n1 OP

    #rm -rf ${DIRS}
    rm -rf ./projects
    mkdir -p ./projects
    cd ./projects
    mkdir ${DIRS}
else
    echo "[INFO] Skipping repositories..."
fi

rm -rfv ./projects/${DOCKER_YML_PROJECTS}

# Git Projects

SAVE_PATH_PROJECTS="${PWD}/projects"

for ((i = 0; i < ${#PROJECTS[@]}; i++)); do

    PROJECT_NAME=$(echo "${PROJECTS[$i]}" | sed -e 's/"//g')
    PROJECT_UPPER=$(echo "${PROJECT_NAME}" | tr '[:lower:]' '[:upper:]')
    PROJECT_PATH=$(echo "${SAVE_PATH_PROJECTS}/${PROJECT_NAME}")
    PROJECT_GIT=$(echo "${GITHUB}" | sed -e "s/{{{PROJECT_NAME}}}/${PROJECT_NAME}/g")
    PROJECT_CLONE="https://${GITUSER}:${GITPASS}@${PROJECT_GIT}"

    echo ""
    echo "--------------------------------------------------------------------------"
    echo "${PROJECT_UPPER} Env Building..."
    cd "${PROJECT_PATH}"
    pwd

    if [[ ! -e .git ]]; then
        git clone ${PROJECT_CLONE} .
        if [[ $? != "0" ]]; then
            echo ">>> GIT ERROR !!!"
        fi
    else
        echo "[WARNING] The project ${PROJECT_GIT} already exists, skipping..."
    fi

    cd "${SAVE_PATH_PROJECTS}"
    pwd

    if [[ ! -e "${PROJECT_PATH}/.git" ]];
    then
        echo ""
        echo "[WARNING] Git Project is not installed ou missing ${PROJECT_PATH}/.git"
        echo ""
        #exit
    fi

done

# Configuration

cd "${SAVE_PATH}"

if [[ $CONFIGURATION == "true" && $PARAM3 == "true" ]];
then

    # Automatic Configuration

    echo ""
    echo "The Automatic Configuration will be initialized !"
    echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
    read -n1 OP

    echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"
    echo "Configuration is initializing"
    echo "======================================================================="
    echo ""

    source read_configuration.sh "${PROJECTS}"

    if [[ $? != "0" ]]; then
        echo "CRITICAL ERROR in process, aborting..."
        exit
    fi

    echo ""
    echo "======================================================================="
    echo "Configuration finished"
    echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"

else

    if [[ $PARAM3 == "true" ]]; then

        # Manual Configuration

        echo ""
        echo "The Manual Configuration will be needed !"
        echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
        read -n1 OP

        for ((i = 0; i < ${#PROJECTS[@]}; i++)); do

            PROJECT_NAME=$(echo "${PROJECTS[$i]}" | sed -e 's/"//g')
            PROJECT_UPPER=$(echo "${PROJECT_NAME}" | tr '[:lower:]' '[:upper:]')
            PROJECT_PATH=$(echo "${SAVE_PATH_PROJECTS}/${PROJECT_NAME}")

            echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"
            echo "${PROJECT_UPPER} Configuration"

            #Env
            if [[ -e "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.env.tpl" ]];
            then
                cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.env.tpl" "${PROJECT_PATH}/.env"
            fi

            #Conf (App)
            if [[ -e "${SAVE_PATH_PROJECTS}/conf/app.tpl" ]];
            then
                if [[ -e "${PROJECT_PATH}/environment/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/app.tpl" "${PROJECT_PATH}/environment/nginx/app.conf"
                fi

                if [[ -e "${PROJECT_PATH}/env/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/app.tpl" "${PROJECT_PATH}/env/nginx/app.conf"
                fi

                if [[ -e "${PROJECT_PATH}/docker/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/app.tpl" "${PROJECT_PATH}/nginx/docker/app.conf"
                    cp -v "${SAVE_PATH_PROJECTS}/conf/app.tpl" "${PROJECT_PATH}/nginx/docker/_default_.conf"
                fi
            fi

            #Nginx
            if [[ -e "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.nginx.conf" ]];
            then
                if [[ -e "${PROJECT_PATH}/environment/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.nginx.conf" "${PROJECT_PATH}/environment/nginx/nginx.conf"
                fi

                if [[ -e "${PROJECT_PATH}/env/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.nginx.conf" "${PROJECT_PATH}/env/nginx/nginx.conf"
                fi

                if [[ -e "${PROJECT_PATH}/docker/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.nginx.conf" "${PROJECT_PATH}/docker/nginx/nginx.conf"
                fi
            fi

            #Supervisor
            if [[ -e "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.supervisor.conf" ]];
            then
                if [[ -e "${PROJECT_PATH}/environment/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.supervisor.conf" "${PROJECT_PATH}/environment/nginx/supervisor.conf"
                fi

                if [[ -e "${PROJECT_PATH}/env/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.supervisor.conf" "${PROJECT_PATH}/env/nginx/supervisor.conf"
                fi

                if [[ -e "${PROJECT_PATH}/docker/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.supervisor.conf" "${PROJECT_PATH}/docker/nginx/supervisor.conf"
                fi
            fi

            #Nginx72
            if [[ -e "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.nginx72.conf" ]];
            then
                if [[ -e "${PROJECT_PATH}/environment/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.nginx72.conf" "${PROJECT_PATH}/environment/nginx/nginx72.conf"
                fi

                if [[ -e "${PROJECT_PATH}/env/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.nginx72.conf" "${PROJECT_PATH}/env/nginx/nginx72.conf"
                fi

                if [[ -e "${PROJECT_PATH}/docker/nginx" ]];
                then
                    cp -v "${SAVE_PATH_PROJECTS}/conf/env/${PROJECT_NAME}.nginx72.conf" "${PROJECT_PATH}/docker/nginx/nginx72.conf"
                fi
            fi
        done
    fi
fi

if [[ $PARAM3 == "false" ]]; then
    echo ""
    echo "[INFO] Configuration skipping..."
    echo ""
fi

# Docker-Compose

echo ""
echo "--------------------------------------------------------------------------"
echo "The builder of docker compose will be run now..."
echo ""
echo "Continue ? Press [Enter] = Yes, or press [Ctrl+C] = No"
read -n1 OP

sleep 2

docker-compose stop >> /dev/null 2>&1
docker network create "${GATEWAY}"
#docker-compose build
docker-compose -f ${DOCKER_YML_PROJECTS} up --build

echo ""
echo "--------------------------------------------------------------------------"
echo "Execute docker composer up now ?"
echo ""
echo "Continue ? Press [Enter] = Yes, or press [Others Key] = No"
read -n1 OP

if [[ $OP == "" ]];
then
    docker-compose up -d
    sleep 2
    docker-compose ps
fi

echo "--------------------------------------------------------------------------"
echo "Containers Docker is up now !"
echo "--------------------------------------------------------------------------"
echo ""
echo "Install Finish..."
echo ""
echo "--------------------------------------------------------------------------"

ENDTIME=$(date +%T)

echo "START TIME : $STARTTIME"
echo "END TIME    : $ENDTIME"

echo "Bye"
echo ""
exit
