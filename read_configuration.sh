#!/bin/bash

################################################################################
# Author: Jereelton Teixeira
################################################################################

function defineDatabaseVars {
    DB_SETUP=""
    DEFAULT_DB=""
    DEFAULT_DB_FLAG=1
    DB_ARRAY=""
    DB_MAIN_MODEL1="NULL"
    DB_MAIN_MODEL2="NULL"
}

function defineAppVars {
    APP_SETUP=""
    APP_URL_ACTIVE=0
    APP_URL_MODEL1="NULL"
    APP_URL_MODEL2="NULL"
}

function defineApiVars {
    API_SETUP="NULL"
    API_MODEL1="NULL"
    API_MODEL2="NULL"
}

function showDebugDatabase {
    echo "DB_SETUP" "$DB_SETUP"
    echo "DEFAULT_DB" "$DEFAULT_DB"
    echo "DEFAULT_DB_FLAG" $DEFAULT_DB_FLAG
    echo "DB_MAIN_MODEL1" "$DB_MAIN_MODEL1"
    echo "DB_MAIN_MODEL2" "$DB_MAIN_MODEL1"
}

function showDebugApp {
    echo "APP_SETUP" $APP_SETUP
    echo "APP_URL_MODEL1" "$APP_URL_MODEL1"
    echo "APP_URL_MODEL1" "$APP_URL_MODEL1"
    echo "API_SETUP" $API_SETUP
}

function showDebugApi {
    echo "API_MODEL1" "$API_MODEL1"
    echo "API_MODEL1" "$API_MODEL1"
}

function applyDatabaseEnvSettings {

    ##Example: Databases
    sed -i "s/{{{DB_MAIN_MODEL1}}}/${DB_MAIN_MODEL1_HOST}/g" tmp_env/*.env

    ##Example: Apps
    sed -i "s/{{{APP_URL_MODEL1}}}/${APP_URL_MODEL1}/g" tmp_env/*.env

    ##Example: Apis
    sed -i "s/{{{API_URL_MODEL1}}}/${API_URL_MODEL1}/g" tmp_env/*.env

    ##Work in each project
    for ((i = 0; i < ${#PROJECT[@]}; i++)); do

        if [[ ! -e "tmp_env/${PROJECT[$i]}.env" ]]; then
            echo "[WARNING] Missing .env file " "tmp_env/${PROJECT[$i]}.env"
            exit
        fi

        ##Databases
        DATA_ITEM=$(echo ${DB_ARRAY} | egrep -o "\[${PROJECT[$i]}=.*;=${PROJECT[$i]}\]"| cut -d "=" -f2)

        DB_MAIN_HOST=$(echo "${DATA_ITEM}" | cut -d ";" -f1)
        DB_MAIN_PORT=$(echo "${DATA_ITEM}" | cut -d ";" -f2)
        DB_MAIN_USER=$(echo "${DATA_ITEM}" | cut -d ";" -f3)
        DB_MAIN_PASSWD=$(echo "${DATA_ITEM}" | cut -d ";" -f4)
        DB_MAIN_DBNAME=$(echo "${DATA_ITEM}" | cut -d ";" -f5)

        ITEM_UPPER=$(echo "${PROJECT[$i]}" | tr '[:lower:]' '[:upper:]' | sed -e 's/-//g')

        ##Force replace in all files that contain key to other project
        sed -i "s/{{{DB_MAIN_${ITEM_UPPER}}}}/${DB_MAIN_HOST}/g" tmp_env/*.env
        sed -i "s/{{{DB_MAIN_${ITEM_UPPER}_PORT}}}/${DB_MAIN_PORT}/g" tmp_env/*.env
        sed -i "s/{{{DB_MAIN_${ITEM_UPPER}_USER}}}/${DB_MAIN_USER}/g" tmp_env/*.env
        sed -i "s/{{{DB_MAIN_${ITEM_UPPER}_PASSWD}}}/${DB_MAIN_PASSWD}/g" tmp_env/*.env
        sed -i "s/{{{DB_MAIN_${ITEM_UPPER}_DBNAME}}}/${DB_MAIN_DBNAME}/g" tmp_env/*.env

        if [[ ! -e "${PROJECT[$i]}/" ]];
        then
            mkdir -p "${PROJECT[$i]}/"
        fi

    done

    ##Put dotenv in project root path
    for ((i = 0; i < ${#PROJECT[@]}; i++)); do
        cp -v "tmp_env/${PROJECT[$i]}.env" "${PROJECT[$i]}/.env"
    done

}

function setDefaultDatabaseSettings {
    DB_MAIN_MODEL1="${DEFAULT_DB}"
    DB_MAIN_MODEL2="${DEFAULT_DB}"
}

function setRulesDatabase {
    if [[ $line =~ "DB_MAIN_MODEL1=" ]]; then
        if [[ $DEFAULT_DB_FLAG == 0 ]]; then
            DB_MAIN_MODEL1=$(echo "$line" | cut -d "=" -f2)
            DB_ARRAY="${DB_ARRAY}[dbname=${DB_MAIN_MODEL1};=dbname]"
        fi
    fi

    if [[ $line =~ "DB_MAIN_MODEL2=" ]]; then
        if [[ $DEFAULT_DB_FLAG == 0 ]]; then
            DB_MAIN_MODEL2=$(echo "$line" | cut -d "=" -f2)
            DB_ARRAY="${DB_ARRAY}[dbname=${DB_MAIN_MODEL2};=dbname]"
        fi
    fi
}

function setRulesApp {
    if [[ $APP_SETUP == "NULL" ]]; then
        continue
    fi

    if [[ $line =~ "APP_URL_MODEL1=" ]]; then
        if [[ $APP_SETUP == "true" ]]; then
            APP_URL_MODEL1=$(echo "$line" | cut -d "=" -f2-9 | sed 's/\//\\\//g')
        fi
    fi

    if [[ $line =~ "APP_URL_MODEL2=" ]]; then
        if [[ $APP_SETUP == "true" ]]; then
            APP_URL_MODEL2=$(echo "$line" | cut -d "=" -f2-9 | sed 's/\//\\\//g')
        fi
    fi
}

function setRulesApi {
    if [[ $API_SETUP == "NULL" ]]; then
        continue
    fi

    if [[ $line =~ "API_MODEL1=" ]]; then
        if [[ $APP_SETUP == "true" ]]; then
            API_MODEL1=$(echo "$line" | cut -d "=" -f2-9 | sed 's/\//\\\//g')
        fi
    fi

    if [[ $line =~ "API_MODEL2=" ]]; then
        if [[ $APP_SETUP == "true" ]]; then
            API_MODEL2=$(echo "$line" | cut -d "=" -f2-9 | sed 's/\//\\\//g')
        fi
    fi
}

############################
# GLOBAL
############################

TARGET_PROJECTS=$1 #Array
VERSION=NULL
SERVICES=0
GATEWAY=""
EXTERNAL_GATEWAY=$(echo "true" | sed -e 's/"//g')
SERVICE_START=0
ENVIRONMENT="NULL"
VOLUMES="NULL"
LINKS="NULL"
TEMPLATE_TO_YML="conf/docker-compose.tpl"
TEMPLATE_TO_APP="conf/app.tpl"
DOCKER_YML="conf/docker-compose.yml"
CONFIGURATION_FILE="configuration.conf"

############################
# DATABASE
############################

#Define here the references of Databases placed in configuration file (configuration.conf)
defineDatabaseVars

############################
# APP
############################

#Define here the references of App settings placed in configuration file (configuration.conf)
defineAppVars

############################
# API
############################

#Define here the references of API settings placed in configuration file (configuration.conf)
defineApiVars

############################
# PROCESS START
############################

#Copy dotenv files template
PROJECT=($(grep "TARGET_PROJECTS" ${CONFIGURATION_FILE} | cut -d "=" -f2 | sed -e 's/,/"-"/g' | sed -e 's/ //g' | sed -e 's/"-"/" "/g' | sed -e 's/"//g'))

mkdir tmp_env
for ((i = 0; i < ${#PROJECT[@]}; i++)); do
    if [[ ! -e "conf/env/${PROJECT[$i]}.env.tpl" ]]; then
        echo "[WARNING] Missing .env file" "conf/env/${PROJECT[$i]}.env.tpl"
        #exit
    else
        cp "conf/env/${PROJECT[$i]}.env.tpl" "tmp_env/${PROJECT[$i]}.env"
    fi
done

#Read configuration file
while IFS= read -r line || [[ -n "$line" ]]; do

    line=$(echo "$line" | sed -e 's/"//g' | sed -e 's/ = /=/g')

    if [[ $line =~ "[GLOBAL-START]" ]]; then

        echo "READ CONFIGURATION START - GLOBAL START"

        #Remove file docker-compose.yml if exists
        if [[ -e ${DOCKER_YML} ]]; then
            rm -v ${DOCKER_YML}
        fi

    elif [[ $line =~ "[GLOBAL-END]" ]]; then

        ########################################################
        # DEBUG START
        ########################################################
        echo "PROJECTS" "$TARGET_PROJECTS"
        echo "VERSION" $VERSION
        echo "SERVICES" $SERVICES
        echo "GATEWAY" "$GATEWAY"

        #Show here the Databases that placed in configuration file
        showDebugDatabase

        #Show here the Apps settings that placed in configuration file
        showDebugApp

        #Show here the API settings that placed in configuration file
        showDebugApi

        ##Apply all database env settings
        applyDatabaseEnvSettings

        echo "READ CONFIGURATION - GLOBAL END"

    else

        ########################################################
        # GLOBAL CONFIGURATION
        ########################################################

        if [[ $line =~ "CONFIGURATION_SETUP=" ]]; then
            CONFIGURATION_SETUP=$(echo "$line" | cut -d "=" -f2)

            if [[ $CONFIGURATION_SETUP == "" || $CONFIGURATION_SETUP == "NULL" ]]; then
                echo "Invalid configuration: CONFIGURATION_SETUP is not accept for this process !"
                exit
            fi
        fi

        if [[ $line =~ "SERVICES=" ]]; then
            SERVICES=$(echo "$line" | cut -d "=" -f2)
            FOUND_SERVICES=$(egrep "#SERVICE\-[0-9]" configuration.conf | wc -l)

            if [[ $SERVICES != $FOUND_SERVICES ]]; then
                echo "configuration setup:services [${SERVICES}] != found:services [${FOUND_SERVICES}] !"
                exit
            fi
        fi

        if [[ $line =~ "TARGET_PROJECTS=" ]]; then
            TARGET_PROJECTS=$(echo "$line" | cut -d "=" -f2)

            if [[ $TARGET_PROJECTS == "" || $TARGET_PROJECTS == "NULL" ]]; then
                echo $TARGET_PROJECTS
                echo "Invalid configuration: TARGET_PROJECTS !"
                exit
            fi
        fi

        #########################################################################
        # DOCKER COMPOSE YML START
        #########################################################################

        if [[ $line =~ "VERSION=" ]]; then
            VERSION=$(echo "$line" | cut -d "=" -f2)

            if [[ $VERSION == "" || $VERSION == "NULL" ]]; then
                echo "Invalid configuration: missing version docker-compose !"
                exit
            fi

            VERSION='"'${VERSION}'"'

            touch ${DOCKER_YML}
            grep "{{{VERSION}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{VERSION}}}/${VERSION}/" >> ${DOCKER_YML}
            grep "services:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
        fi

        if [[ $line =~ "GATEWAY=" ]]; then
            GATEWAY=$(echo "$line" | cut -d "=" -f2)

            if [[ $GATEWAY == "" || ! $GATEWAY ]];
            then
                echo "Invalid configuration gateway, use NULL to ignore this !"
                exit
            fi
        fi

        #######################################################################
        # DOTENV CONFIGURATION START - DATABASE
        #######################################################################

        if [[ $line =~ "[DATABASE-START]" ]]; then
            echo "# >>> DATABASE START"
            continue
        fi

        if [[ $line =~ "[DATABASE-END]" ]]; then
            echo "# >>> DATABASE END"
            DB_SETUP="" #Unlock process flow
            continue
        fi

        if [[ $line =~ "DB_SETUP=" ]]; then
            DB_SETUP=$(echo "$line" | cut -d "=" -f2)
            echo "DB_SETUP" "${DB_SETUP}"

            if [[ $DB_SETUP != "true" ]]; then
                DB_SETUP="NULL"
            fi
        fi

        #Ignore database config
        if [[ $DB_SETUP == "NULL" ]]; then
            continue
        fi

        if [[ $line =~ "DB_MASTER_ALL=" ]]; then
            DEFAULT_DB=$(echo "$line" | cut -d "=" -f2)

            if [[ $DEFAULT_DB == "NULL" || $DEFAULT_DB == "null" ]]; then
                DEFAULT_DB_FLAG=0
            else

                setDefaultDatabaseSettings

                for ((i = 0; i < ${#PROJECT[@]}; i++)); do
                    DB_ARRAY="${DB_ARRAY}[${PROJECT[$i]}=${DEFAULT_DB};=${PROJECT[$i]}]"
                done
            fi
        fi

        #Define here the database rules of setting in configuration file
        setRulesDatabase

        #######################################################################
        # DOTENV CONFIGURATION START - APPs
        #######################################################################

        if [[ $line =~ "[APP-START]" ]]; then
            echo "# >>> APP START"
            continue
        fi

        if [[ $line =~ "[APP-END]" ]]; then
            echo "# >>> APP END"
            APP_SETUP="" #Unlock process flow
            continue
        fi

        if [[ $line =~ "APP_SETUP=" ]]; then
            APP_SETUP=$(echo "$line" | cut -d "=" -f2)
            echo "APP_SETUP" "${APP_SETUP}"

            if [[ $APP_SETUP != "true" ]]; then
                APP_SETUP="NULL"
            fi
        fi

        #Ignore App config
        setRulesApp

        #######################################################################
        # DOTENV CONFIGURATION START - APIs
        #######################################################################

        if [[ $line =~ "[API-START]" ]]; then
            echo "# >>> API START"
            continue
        fi

        if [[ $line =~ "[API-END]" ]]; then
            echo "# >>> API END"
            API_SETUP="" #Unlock process flow
            continue
        fi

        if [[ $line =~ "API_SETUP=" ]]; then
            API_SETUP=$(echo "$line" | cut -d "=" -f2)
            echo "API_SETUP" "${API_SETUP}"

            if [[ $API_SETUP != "true" ]]; then
                API_SETUP="NULL"
            fi
        fi

        #Ignore API config
        setRulesApi
    fi

    #######################################################################
    # SERVICES - TO CREATE A DOCKER COMPOSE YML
    #######################################################################

    if [[ $line =~ "[SERVICE-START]" ]]; then
        echo ""
        echo "# >>> SERVICE START"
        continue
    fi

    if [[ $line =~ "[SERVICE-END]" ]]; then
        echo ""
        echo "# >>> SERVICE END"
        continue
    fi

    if echo "$line" | egrep '#SERVICE\-[0-9]*' >> /dev/null 2>&1
    then
        echo ""
        echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++"
        echo "SERVICE START" "$line"

        #Reset Status NGINX Conf (service by service)
        SERVICE_START=1
        NGINX_PROCESSED=0
        PROJECT_NAME="NULL"
        NGINX_LISTEN="NULL"
        NGINX_FAST_CGI_PASS="NULL"
        NGINX_CONF="NULL"
        NGINX_ROOT_PATH="NULL"
        NGINX_APP_CONF="NULL"
        NGINX72_CONF="NULL"
        SUPERVISOR_CONF="NULL"
    fi

    if echo "$line" | egrep '\*{90}' >> /dev/null 2>&1
    then
        if [[ $SERVICE_START == 1 ]];
        then
            echo "SERVICE END"
            SERVICE_START=0
            echo "" >> ${DOCKER_YML}
        fi
    fi

    if [[ $SERVICE_START == 1 ]]; then

        if [[ $line =~ "SERVICE_NAME=" ]];
        then
            SERVICE_NAME=$(echo "$line" | cut -d "=" -f2)
            echo ">> SERVICE_NAME" $SERVICE_NAME

            if [[ $SERVICE_NAME != "" && $SERVICE_NAME != "NULL" ]];
            then
                grep "{{{SERVICE_NAME}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{SERVICE_NAME}}}/${SERVICE_NAME}/" >> ${DOCKER_YML}
            else
                echo "Critical Error: Missing Service Name"
                exit
            fi

        fi

        if echo "$line" | grep "CONTAINER_NAME" >> /dev/null 2>&1
        then
            CONTAINER_NAME=$(echo "$line" | cut -d "=" -f2)
            echo "CONTAINER_NAME" $CONTAINER_NAME

            if [[ $CONTAINER_NAME != "" && $CONTAINER_NAME != "NULL" ]];
            then
                grep "{{{CONTAINER_NAME}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{CONTAINER_NAME}}}/${CONTAINER_NAME}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "IMAGE" >> /dev/null 2>&1
        then
            IMAGE=$(echo "$line" | cut -d "=" -f2)
            echo "IMAGE" $IMAGE

            if [[ $IMAGE != "" && $IMAGE != "NULL" ]];
            then
                grep "{{{IMAGE}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{IMAGE}}}/${IMAGE}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "PRIVILEGED" >> /dev/null 2>&1
        then
            PRIVILEGED=$(echo "$line" | cut -d "=" -f2)
            echo "PRIVILEGED" $PRIVILEGED

            if [[ $PRIVILEGED != "" && $PRIVILEGED != "NULL" ]];
            then
                grep "{{{PRIVILEGED}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{PRIVILEGED}}}/${PRIVILEGED}/" >> ${DOCKER_YML}
            else
                grep "{{{PRIVILEGED}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{PRIVILEGED}}}/true/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "LOCALHOST_PORT" >> /dev/null 2>&1
        then
            LOCALHOST_PORT=$(echo "$line" | cut -d "=" -f2)
            echo "LOCALHOST_PORT" $LOCALHOST_PORT
        fi

        if echo "$line" | grep "INTERNAL_PORT" >> /dev/null 2>&1
        then
            INTERNAL_PORT=$(echo "$line" | cut -d "=" -f2)
            echo "INTERNAL_PORT" $INTERNAL_PORT

            if [[ $LOCALHOST_PORT != "" && $INTERNAL_PORT != "" && $LOCALHOST_PORT != "NULL" && $INTERNAL_PORT != "NULL" ]];
            then

                grep "ports:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}

                if echo $LOCALHOST_PORT | grep "," >> /dev/null 2>&1
                then

                    LOCAL_PORTS=$(echo "$LOCALHOST_PORT" | sed -e 's/,/" "/g')
                    LOCAL_PORTS=('"'${LOCAL_PORTS}'"')
                    INTERNAL_PORTS=$(echo "$INTERNAL_PORT" |sed -e 's/,/" "/g')
                    INTERNAL_PORTS=('"'${INTERNAL_PORTS}'"')

                    if [[ ${#LOCAL_PORTS[@]} != ${#INTERNAL_PORTS[@]} ]];
                    then
                        echo "Critical Error: Local Ports are different of Internal Ports !"
                        exit
                    fi

                    for ((i = 0; i < ${#LOCAL_PORTS[@]}; i++)); do
                        PORTS=$(echo "${LOCAL_PORTS[$i]}:${INTERNAL_PORTS[$i]}" | sed -e 's/"//g')
                        PORTS='"'${PORTS}'"'
                        grep "{{{PORTS}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{PORTS}}}/${PORTS}/" >> ${DOCKER_YML}
                    done

                else
                    PORTS='"'$LOCALHOST_PORT:$INTERNAL_PORT'"'
                    grep "{{{PORTS}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{PORTS}}}/${PORTS}/" >> ${DOCKER_YML}
                fi
            fi
        fi

        if echo "$line" | grep "NETWORKS" >> /dev/null 2>&1
        then
            NETWORKS=$(echo "$line" | cut -d "=" -f2)
            echo "NETWORKS" $NETWORKS

            if [[ $NETWORKS != "" && $NETWORKS != "NULL" ]];
            then
                grep "networks:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
                if echo $NETWORKS | grep "," >> /dev/null 2>&1
                then
                    IFS=',' read -ra NET <<< "$NETWORKS"
                    for i in "${NET[@]}"; do
                        grep "{{{NETWORK}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{NETWORK}}}/${i}/" >> ${DOCKER_YML}
                    done
                else
                    grep "{{{NETWORK}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{NETWORK}}}/${NETWORKS}/" >> ${DOCKER_YML}
                fi
            fi
        fi

        if echo "$line" | grep "ENVIRONMENT=" >> /dev/null 2>&1
        then
            ENVIRONMENT=$(echo "$line" | cut -d "=" -f2)

            if [[ $ENVIRONMENT == "true" ]];
            then
                echo "ENVIRONMENT [START]" $ENVIRONMENT
                grep "environment:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
                continue
            fi
        fi

        if [[ $ENVIRONMENT == "true" ]];
        then
            if ! echo "$line" | egrep 'VOLUMES=.*' >> /dev/null 2>&1
            then
                echo "$line"
                ENV=$(echo "$line" | awk '{print $1$2}' | sed -e "s/=/: /" | sed 's/\//\\\//g')
                grep "{{{ENV}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{ENV}}}/${ENV}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "VOLUMES=" >> /dev/null 2>&1
        then
            echo "ENVIRONMENT [END]" $ENVIRONMENT
            ENVIRONMENT="NULL"
            VOLUMES=$(echo "$line" | cut -d "=" -f2)
            echo "VOLUMES [START]" $VOLUMES

            if [[ $VOLUMES == "true" ]];
            then
                grep "volumes:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
                continue
            fi
        fi

        if [[ $VOLUMES == "true" ]];
        then
            if ! echo "$line" | egrep 'LINKS=.*' >> /dev/null 2>&1
            then
                echo "$line"
                VOL=$(echo "$line" | awk '{print $1$2}' | cut -d "=" -f2 | sed 's/\//\\\//g')
                grep "{{{VOLUME}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{VOLUME}}}/${VOL}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "LINKS=" >> /dev/null 2>&1
        then
            echo "VOLUMES [END]"
            VOLUMES="NULL"
            LINKS=$(echo "$line" | cut -d "=" -f2)
            echo "LINKS [START]" $LINKS

            if [[ $LINKS == "true" ]];
            then
                grep "links:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
                continue
            fi
        fi

        if [[ $LINKS == "true" ]];
        then
            if ! echo "$line" | egrep 'DEPENDS_ON=.*' >> /dev/null 2>&1
            then
                echo "$line"
                LINK=$(echo "$line" | cut -d "=" -f2 | sed 's/\//\\\//g')
                grep "{{{LINK}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{LINK}}}/${LINK}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "DEPENDS_ON" >> /dev/null 2>&1
        then
            echo "LINKS [END]"
            LINKS="NULL"
            DEPENDS_ON=$(echo "$line" | cut -d "=" -f2)
            echo "DEPENDS_ON" $DEPENDS_ON

            if [[ $DEPENDS_ON != "" && $DEPENDS_ON != "NULL" ]];
            then
                grep "depends_on:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
                grep "{{{DEPENDS_ON}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{DEPENDS_ON}}}/${DEPENDS_ON}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "BUILD" >> /dev/null 2>&1
        then
            BUILD=$(echo "$line" | cut -d "=" -f2 | sed 's/\//\\\//g')
            echo "BUILD" $BUILD

            if [[ $BUILD != "" && $BUILD != "NULL" && $BUILD != "dockerfile" ]];
            then
                grep "{{{BUILDER}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{BUILDER}}}/${BUILD}/" >> ${DOCKER_YML}
            elif [[ $BUILD == "dockerfile" ]]; then
                grep "{{{BUILDER}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{BUILDER}}}//" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "CONTEXT" >> /dev/null 2>&1
        then
            CONTEXT=$(echo "$line" | cut -d "=" -f2 | sed 's/\//\\\//g')
            echo "CONTEXT" $CONTEXT

            if [[ $CONTEXT != "" && $CONTEXT != "NULL" ]];
            then
                grep "{{{CONTEXT}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{CONTEXT}}}/${CONTEXT}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "DOCKERFILE" >> /dev/null 2>&1
        then
            DOCKERFILE=$(echo "$line" | cut -d "=" -f2 | sed 's/\//\\\//g')
            echo "DOCKERFILE" $DOCKERFILE

            if [[ $DOCKERFILE != "" && $DOCKERFILE != "NULL" ]];
            then
                grep "{{{DOCKERFILE}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{DOCKERFILE}}}/${DOCKERFILE}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "WORKING_DIR" >> /dev/null 2>&1
        then
            WORKING_DIR=$(echo "$line" | cut -d "=" -f2 | sed 's/\//\\\//g')
            echo "WORKING_DIR" $WORKING_DIR

            if [[ $WORKING_DIR != "" && $WORKING_DIR != "NULL" ]];
            then
                grep "{{{WORKING_DIR}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{WORKING_DIR}}}/${WORKING_DIR}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "COMMAND" >> /dev/null 2>&1
        then
            COMMAND=$(echo "$line" | sed -e 's/COMMAND=//g')
            echo "COMMAND" $COMMAND

            if [[ $COMMAND != "" && $COMMAND != "NULL" ]];
            then
                grep "{{{COMMAND}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{COMMAND}}}/${COMMAND}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "ENV_FILE" >> /dev/null 2>&1
        then
            ENV_FILE=$(echo "$line" | cut -d "=" -f2 | sed 's/\//\\\//g')
            echo "ENV_FILE" "[$ENV_FILE]"
            if [[ $ENV_FILE != "" && $ENV_FILE != "NULL" ]];
            then
                grep "env_file:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
                grep "{{{ENV_FILE}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{ENV_FILE}}}/${ENV_FILE}/" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "DEPLOY=" >> /dev/null 2>&1
        then
            DEPLOY=$(echo "$line" | cut -d "=" -f2)
            echo "DEPLOY" $DEPLOY

            if [[ $DEPLOY == "true" ]];
            then
                grep "deploy:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
                grep "resources:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
                grep "limits:" "${TEMPLATE_TO_YML}" >> ${DOCKER_YML}
            fi
        fi

        if echo "$line" | grep "DEPLOY_MEMORY" >> /dev/null 2>&1
        then
            DEPLOY_MEMORY=$(echo "$line" | cut -d "=" -f2)
            echo "DEPLOY_MEMORY" $DEPLOY_MEMORY

            if [[ $DEPLOY == "true" ]];
            then
                grep "{{{DEPLOY_MEMORY}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{DEPLOY_MEMORY}}}/${DEPLOY_MEMORY}/" >> ${DOCKER_YML}
            fi
        fi

        #################################################################################
        # NGINX - APP.CONF
        #################################################################################

        if echo "$line" | grep "PROJECT_NAME" >> /dev/null 2>&1
        then
            PROJECT_NAME=$(echo "$line" | cut -d "=" -f2)
            echo "PROJECT_NAME" $PROJECT_NAME
        fi

        if echo "$line" | grep "NGINX_LISTEN" >> /dev/null 2>&1
        then
            NGINX_LISTEN=$(echo "$line" | cut -d "=" -f2)
            echo "NGINX_LISTEN" $NGINX_LISTEN
        fi

        if echo "$line" | grep "NGINX_FAST_CGI_PASS" >> /dev/null 2>&1
        then
            NGINX_FAST_CGI_PASS=$(echo "$line" | cut -d "=" -f2)
            echo "NGINX_FAST_CGI_PASS" $NGINX_FAST_CGI_PASS
        fi

        if echo "$line" | grep "NGINX_CONF" >> /dev/null 2>&1
        then
            NGINX_CONF=$(echo "$line" | cut -d "=" -f2)
            echo "NGINX_CONF" $NGINX_CONF
        fi

        if echo "$line" | grep "NGINX_APP_CONF" >> /dev/null 2>&1
        then
            NGINX_APP_CONF=$(echo "$line" | cut -d "=" -f2)
            echo "NGINX_APP_CONF" $NGINX_APP_CONF
        fi

        if echo "$line" | grep "NGINX_ROOT_PATH" >> /dev/null 2>&1
        then
            NGINX_ROOT_PATH=$(echo "$line" | cut -d "=" -f2 | sed 's/\//\\\//g')
            echo "NGINX_ROOT_PATH" $NGINX_ROOT_PATH
        fi

        if [[ $PROJECT_NAME != "NULL" && $NGINX_LISTEN != "NULL" && $NGINX_FAST_CGI_PASS != "NULL" && $NGINX_APP_CONF != "NULL" && $NGINX_ROOT_PATH != "NULL" ]];
        then

            if [[ $NGINX_PROCESSED == 1 ]];
            then
                continue
            fi
            NGINX_PROCESSED=1

            if [[ -e "conf/${PROJECT_NAME}.app.conf" ]];
            then
                rm "conf/${PROJECT_NAME}.app.conf"
            fi

            if [[ -e "nginx/${PROJECT_NAME}/app.conf" ]];
            then
                rm "nginx/${PROJECT_NAME}/app.conf"
            fi

            #New Conf File to Nginx based on project name - project.conf -> app.conf
            touch "conf/${PROJECT_NAME}.app.conf"
            cat "${TEMPLATE_TO_APP}" > "conf/${PROJECT_NAME}.app.conf"

            #Replace data
            echo "Writing conf to ${PROJECT_NAME}"
            sed -i "s/{{{NGINX_LISTEN}}}/${NGINX_LISTEN}/" "conf/${PROJECT_NAME}.app.conf"
            sed -i "s/{{{NGINX_ROOT_PATH}}}/${NGINX_ROOT_PATH}/" "conf/${PROJECT_NAME}.app.conf"
            sed -i "s/{{{NGINX_FAST_CGI_PASS}}}/${NGINX_FAST_CGI_PASS}/" "conf/${PROJECT_NAME}.app.conf"

            #Copy app.conf to project path final
            FINAL_PATH=$(echo "${NGINX_APP_CONF}" | cut -d ":" -f1)
            FINAL_PATH=$(dirname "${FINAL_PATH}")
            if [[ ! -e "${FINAL_PATH}" ]];
            then
                mkdir -p "${FINAL_PATH}"
            fi
            mv -v "./conf/${PROJECT_NAME}.app.conf" "${FINAL_PATH}/app.conf"
            echo "FINAL_PATH ==>>> APP.CONF" "${FINAL_PATH}"
        fi

        #Copy nginx.conf if exists
        if [[ $NGINX_CONF != "NULL" ]];
        then
            FINAL_PATH=$(echo "${NGINX_CONF}" | cut -d ":" -f1)
            FINAL_PATH=$(dirname "${FINAL_PATH}")
            if [[ ! -e "${FINAL_PATH}" ]];
            then
                mkdir -p "${FINAL_PATH}"
            fi
            cp -v "./conf/nginx.conf" "${FINAL_PATH}/nginx.conf"
            echo "FINAL_PATH ==>>> NGINX.CONF" "${FINAL_PATH}"
        fi

        #Copy nginx72.conf if exists
        if [[ $NGINX72_CONF != "NULL" ]];
        then
            FINAL_PATH=$(echo "${NGINX72_CONF}" | cut -d ":" -f1)
            FINAL_PATH=$(dirname "${FINAL_PATH}")
            if [[ ! -e "${FINAL_PATH}" ]];
            then
                mkdir -p "${FINAL_PATH}"
            fi
            cp -v "./conf/nginx72.conf" "${FINAL_PATH}/nginx72.conf"
            echo "FINAL_PATH ==>>> NGINX72.CONF" "${FINAL_PATH}"
        fi

        #Copy supervisor.conf if exists
        if [[ $SUPERVISOR_CONF != "NULL" ]];
        then
            FINAL_PATH=$(echo "${SUPERVISOR_CONF}" | cut -d ":" -f1)
            FINAL_PATH=$(dirname "${FINAL_PATH}")
            if [[ ! -e "${FINAL_PATH}" ]];
            then
                mkdir -p "${FINAL_PATH}"
            fi
            cp -v "./conf/supervisor.conf" "${FINAL_PATH}/supervisor.conf"
            echo "FINAL_PATH ==>>> SUPERVISOR.CONF" "${FINAL_PATH}"
        fi
    fi

done < ${CONFIGURATION_FILE}

grep "{{{NETWORKS}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{NETWORKS}}}/networks/" >> ${DOCKER_YML}
grep "{{{GATEWAY}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{GATEWAY}}}/${GATEWAY}/" >> ${DOCKER_YML}
grep "{{{EXTERNAL_GATEWAY}}}" "${TEMPLATE_TO_YML}" | sed -e "s/{{{EXTERNAL_GATEWAY}}}/${EXTERNAL_GATEWAY}/" >> ${DOCKER_YML}

mv -v ${DOCKER_YML} .
#cp -rv nginx ./projects/
cp -rv php ./projects/
cp -rv redis ./projects/
rm -rf tmp_env

echo ""
echo "--------------------------------------------------------"
echo ""
echo "READ CONFIGURATION FINISHED"
echo ""
echo "--------------------------------------------------------"
echo ""
