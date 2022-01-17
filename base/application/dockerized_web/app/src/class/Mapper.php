<?php

namespace Dockerized;

class Mapper
{
    /**
     * @description Mapper Setup
     * @param string $setup_file #Mandatory
     * @return string|bool
     */
    public static function mapperSetup(string $setup_file): string|bool
    {
        return json_encode([
            "CONFIGURATION_SETUP" => Reader::apiReaderSetup('CONFIGURATION_SETUP', $setup_file),
            "SERVICES_QUANTITY" => Reader::apiReaderSetup('SERVICES_QUANTITY', $setup_file),
            "NGINX_SETUP" => Reader::apiReaderSetup('NGINX_SETUP', $setup_file),
            "APACHE_SETUP" => Reader::apiReaderSetup('APACHE_SETUP', $setup_file),
            "SUPERVISOR_SETUP" => Reader::apiReaderSetup('SUPERVISOR_SETUP', $setup_file),
            "TOMCAT_SETUP" => Reader::apiReaderSetup('TOMCAT_SETUP', $setup_file),
            "DOCKER_COMPOSE_VERSION" => Reader::apiReaderSetup('DOCKER_COMPOSE_VERSION', $setup_file),
            "DOCKER_COMPOSE_VERSION_OTHER" => Reader::apiReaderSetup('DOCKER_COMPOSE_VERSION_OTHER', $setup_file),
            "NETWORK_DEFAULT" => Reader::apiReaderSetup('NETWORK_DEFAULT', $setup_file),
            "NETWORK_GATEWAY" => Reader::apiReaderSetup('NETWORK_GATEWAY', $setup_file),
            "RESOURCES_DOCKERIZED_ALL" => Reader::apiReaderSetup('RESOURCES_DOCKERIZED_ALL', $setup_file),
            "RESOURCES_DOCKERIZED" => explode(",", Reader::apiReaderSetup('RESOURCES_DOCKERIZED', $setup_file)),
            "DOCKER_EXTRA_IMAGES" => explode(",", Reader::apiReaderSetup('DOCKER_EXTRA_IMAGES', $setup_file)),
            "PHP_VERSION_ALL" => Reader::apiReaderSetup('PHP_VERSION_ALL', $setup_file),
            "PHP_VERSION" => explode(",", Reader::apiReaderSetup('PHP_VERSION', $setup_file)),
            "JAVA_VERSION_ALL" => Reader::apiReaderSetup('JAVA_VERSION_ALL', $setup_file),
            "JAVA_VERSION" => explode(",", Reader::apiReaderSetup('JAVA_VERSION', $setup_file)),
            "PYTHON_VERSION_ALL" => Reader::apiReaderSetup('PYTHON_VERSION_ALL', $setup_file),
            "PYTHON_VERSION" => explode(",", Reader::apiReaderSetup('PYTHON_VERSION', $setup_file)),
            "NODEJS_VERSION_ALL" => Reader::apiReaderSetup('NODEJS_VERSION_ALL', $setup_file),
            "NODEJS_VERSION" => explode(",", Reader::apiReaderSetup('NODEJS_VERSION', $setup_file)),
            "GIT_PROJECT" => Reader::apiReaderSetupAll('GIT_PROJECT', $setup_file),
        ]);
    }
}
