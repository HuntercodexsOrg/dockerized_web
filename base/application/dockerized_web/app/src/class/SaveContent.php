<?php

namespace Dockerized;

class SaveContent
{
    /**
     * @description Save Setup
     * @param mixed $data #Mandatory
     * @param string $setup #Mandatory
     * @return bool
     */
    public static function saveSetup(mixed $data, string $setup): bool
    {
        unlink($setup);
        touch($setup);
        chmod($setup, 0777);

        /*Global Configuration*/
        $config = $data['config_setup'];
        $services_qty = $data['services_qty'];
        file_put_contents($setup, "CONFIGURATION_SETUP = {$config}".PHP_EOL);
        file_put_contents($setup, "SERVICES_QUANTITY = {$services_qty}".PHP_EOL, FILE_APPEND);

        /*NGINX*/
        $nginx = $data['nginx_config'];
        file_put_contents($setup, "NGINX_SETUP = {$nginx}".PHP_EOL, FILE_APPEND);

        /*APACHE*/
        $apache = $data['apache_config'];
        file_put_contents($setup, "APACHE_SETUP = {$apache}".PHP_EOL, FILE_APPEND);

        /*SUPERVISOR*/
        $supervisor = $data['supervisor_config'];
        file_put_contents($setup, "SUPERVISOR_SETUP = {$supervisor}".PHP_EOL, FILE_APPEND);

        /*TOMCAT*/
        $tomcat = $data['tomcat_config'];
        file_put_contents($setup, "TOMCAT_SETUP = {$tomcat}".PHP_EOL, FILE_APPEND);

        /*DOCKER*/
        $docker_compose_version = $data['docker_compose_version'];
        $docker_compose_version_other = $data['docker_compose_version_other'] ?? "";
        $network_default = $data['network_default'];
        $network_gateway = $data['network_gateway'];
        $extra_images = $data['extra_images'] ?? "";
        file_put_contents($setup, "DOCKER_COMPOSE_VERSION = {$docker_compose_version}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "DOCKER_COMPOSE_VERSION_OTHER = {$docker_compose_version_other}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "NETWORK_DEFAULT = {$network_default}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "NETWORK_GATEWAY = {$network_gateway}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "DOCKER_EXTRA_IMAGES = {$extra_images}".PHP_EOL, FILE_APPEND);

        /*DATABASES*/
        $resources_default_all = $data['resources_default_all'];
        $resources_default = $data['resources_default'];
        file_put_contents($setup, "RESOURCES_DOCKERIZED_ALL = {$resources_default_all}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "RESOURCES_DOCKERIZED = {$resources_default}".PHP_EOL, FILE_APPEND);

        /*PHP*/
        $php_version_all = $data['php_version_all'];
        $php_version = $data['php_version'];
        file_put_contents($setup, "PHP_VERSION_ALL = {$php_version_all}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "PHP_VERSION = {$php_version}".PHP_EOL, FILE_APPEND);

        /*JAVA*/
        $java_version_all = $data['java_version_all'];
        $java_version = $data['java_version'];
        file_put_contents($setup, "JAVA_VERSION_ALL = {$java_version_all}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "JAVA_VERSION = {$java_version}".PHP_EOL, FILE_APPEND);

        /*PYTHON*/
        $python_version_all = $data['python_version_all'];
        $python_version = $data['python_version'];
        file_put_contents($setup, "PYTHON_VERSION_ALL = {$python_version_all}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "PYTHON_VERSION = {$python_version}".PHP_EOL, FILE_APPEND);

        /*NODEJS*/
        $nodejs_version_all = $data['nodejs_version_all'];
        $nodejs_version = $data['nodejs_version'];
        file_put_contents($setup, "NODEJS_VERSION_ALL = {$nodejs_version_all}".PHP_EOL, FILE_APPEND);
        file_put_contents($setup, "NODEJS_VERSION = {$nodejs_version}".PHP_EOL, FILE_APPEND);

        /*GIT*/
        $count_projects = count(explode(",", $data['git_username']));
        $git_username = explode(",", $data['git_username']);
        $git_project = explode(",", $data['git_project']);
        $git_project_private = explode(",", $data['git_project_private']) ?? [];

        for ($i = 0; $i < $count_projects; $i++){
            if (in_array($i, $git_project_private)) {
                $project = $git_username[$i].":{{{GITHUB_TOKEN}}}@".$git_project[$i];
            } else{
                $project = "github.com/".$git_username[$i]."/".$git_project[$i];
            }
            file_put_contents($setup, "GIT_PROJECT = {$project}".PHP_EOL, FILE_APPEND);
        }

        return true;
    }
}
