version: {{{VERSION_DOCKER_COMPOSE}}}
services:
    {{{SERVICE_NAME}}}:
        container_name: {{{CONTAINER_NAME}}}
        image: {{{IMAGE}}}
        privileged: {{{PRIVILEGED}}}
        ports:
            - {{{PORTS}}}
        networks:
            - {{{NETWORK}}}
        environment:
            {{{ENV}}}
        volumes:
            - {{{VOLUME}}}
        links:
            - {{{LINK}}}
        depends_on:
            - {{{DEPENDS_ON}}}
        build: {{{BUILDER}}}
            context: {{{CONTEXT}}}
            dockerfile: {{{DOCKERFILE}}}
        working_dir: {{{WORKING_DIR}}}
        command: {{{COMMAND}}}
        env_file:
            - {{{ENV_FILE}}}
        deploy:
            resources:
                limits:
                    memory: {{{DEPLOY_MEMORY}}}
        stdin_open: {{{STDIN_OPEN_VALUE}}}
        tty: {{{TTY_VALUE}}}
    
networks:
    {{{NETWORK_GATEWAY}}}:
        external: {{{TRUE_FALSE}}}