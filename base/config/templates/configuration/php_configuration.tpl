******************************************************************************************
#SERVICE-{{{MANDATORY: SERVICE_NUMBER}}}
------------------------------------------------------------------------------------------
SERVICE_NAME = "{{{MANDATORY: SERVICE_NAME}}}"
CONTAINER_NAME = "{{{MANDATORY: SERVICE_NAME}}}"
PRIVILEGED = "true"
NETWORKS = "default,{{{MANDATORY: NETWORK_GATEWAY}}}"
ENVIRONMENT = "{{{OPTIONAL: NULL,true}}}"
    PHP_IDE_CONFIG = "serverName=docker"
    {{{ENV}}}
VOLUMES = "{{{MANDATORY: NULL,true}}}"
    {{{VOLUME}}}
BUILD = "{{{OPTIONAL[DEPENDS_IMAGE]: NULL,true,dockerfile,BUILDER_PATH}}}"
    CONTEXT = "./projects/{{{SERVICE_NAME}}}/"
    DOCKERFILE = "{{{SERVICE_NAME}.dockerfile}}"
WORKING_DIR = "{{{OPTIONAL: NULL,WORKING_PATH_VALUE}}}"
COMMAND = "{{{OPTIONAL: NULL,COMMAND_VALUE}}}"
PROJECT_NAME = "{{{MANDATORY: SERVICE_NAME}}}"
