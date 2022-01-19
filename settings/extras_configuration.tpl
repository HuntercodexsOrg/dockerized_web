******************************************************************************************
#SERVICE-{{{MANDATORY: SERVICE_NUMBER}}}
------------------------------------------------------------------------------------------
SERVICE_NAME = "{{{MANDATORY: SERVICE_NAME}}}"
CONTAINER_NAME = "{{{MANDATORY: SERVICE_NAME}}}"
IMAGE = "{{{MANDATORY[DEPENDS_BUILD)]: IMAGE_VALUE}}}"
PRIVILEGED = "{{{NULL,true}}}"
LOCALHOST_PORT = "{{{MANDATORY: HOST_PORT}}}"
INTERNAL_PORT = "{{{MANDATORY: CONTAINER_PORT}}}"
NETWORKS = "default,{{{MANDATORY: NULL,NETWORK_GATEWAY}}}"
ENVIRONMENT = "{{{OPTIONAL: NULL,true}}}"
    {{{ENV}}}
VOLUMES = "{{{OPTIONAL: NULL,true}}}"
    {{{VOLUME}}}
LINKS = "{{{OPTIONAL: NULL,true}}}"
    {{{LINK}}}
DEPENDS_ON = "{{{OPTIONAL: NULL,true}}}"
    {{{DEPEND}}}
BUILD = "{{{OPTIONAL[DEPENDS_IMAGE]: NULL,true,dockerfile,BUILDER_PATH}}}"
    CONTEXT = "./projects/{{{SERVICE_NAME}}}/"
    DOCKERFILE = "{{{SERVICE_NAME}.dockerfile}}"
WORKING_DIR = "{{{OPTIONAL: NULL,WORKING_PATH_VALUE}}}"
COMMAND = "{{{OPTIONAL: NULL,COMMAND_VALUE}}}"
ENV_FILE = "{{{OPTIONAL: NULL,env_file_path}}}"
DEPLOY = "{{{OPTIONAL: NULL,true}}}"
DEPLOY_MEMORY = "{{{OPTIONAL: NULL,MEMORY_SIZE}}}"
STDIN_OPEN = "{{{NULL,true}}}"
TTY = "{{{NULL,true}}}"
PROJECT_NAME = "{{{MANDATORY: SERVICE_NAME}}}"
