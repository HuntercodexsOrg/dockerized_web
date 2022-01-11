version: {{{VERSION}}}
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

{{{NETWORKS}}}:
    {{{GATEWAY}}}:
        external: {{{EXTERNAL_GATEWAY}}}
