<?php
$resources_path = "/data/dockerized_web/resources";
$available = explode(',', file_get_contents($resources_path."/available.txt"));
$php_available = explode(',', file_get_contents($resources_path."/php_available.txt"));
$java_available = explode(',', file_get_contents($resources_path."/java_available.txt"));
$python_available = explode(',', file_get_contents($resources_path."/python_available.txt"));
$nodejs_available = explode(',', file_get_contents($resources_path."/nodejs_available.txt"));

$button_load_setup = "";
$button_config = "";
if (file_exists('config/setup.txt')) {
    $button_load_setup = '
            <button type="button" value="button-load-setup" id="button-load-setup">
                LOAD SETUP
            </button>';
    $button_config = '
            <button type="button" value="button-config" id="button-config">
                CONFIG
            </button>';
}
?>

<table id="table-configurations">
    <tr>
        <td class="td-setup-session" colspan="4">
            <a>CONFIGURATIONS</a>
            <?=$button_load_setup;?>
            <button type="submit" value="button-submit-setup" id="button-submit">
                Save
            </button>
            <button type="reset" value="button-reset-setup" id="button-reset">
                Cancel
            </button>
            <?=$button_config;?>
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            ENABLED CONFIGURATION SETUP
        </td>
        <td colspan="3">
            <input type="radio" name="config_setup" id="config-setup-true" value="true" /> <span>true</span>
            <input type="radio" name="config_setup" id="config-setup-false" value="false" /> <span>false</span>
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            <a>SERVICES QUANTITY</a>
        </td>
        <td colspan="3">
            <input class="input-text-common short-number" type="text" name="services_qty" id="services-qty" />
        </td>
    </tr>
</table>

<table id="table-nginx">
    <tr>
        <td class="td-setup-session" colspan="4">
            NGINX
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            <a>ENABLED NGINX CONFIGURE</a>
        </td>
        <td colspan="3">
            <input type="radio" name="nginx_config" id="nginx-config-true" value="true" /> <span>true</span>
            <input type="radio" name="nginx_config" id="nginx-config-false" value="false" /> <span>false</span>
        </td>
    </tr>
</table>

<table id="table-apache">
    <tr>
        <td class="td-setup-session" colspan="4">
            APACHE
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            <a>ENABLED APACHE CONFIGURE</a>
        </td>
        <td colspan="3">
            <input type="radio" name="apache_config" id="apache-config-true" value="true" /> <span>true</span>
            <input type="radio" name="apache_config" id="apache-config-false" value="false" /> <span>false</span>
        </td>
    </tr>
</table>

<table id="table-supervisor">
    <tr>
        <td class="td-setup-session" colspan="4">
            SUPERVISOR
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            <a>ENABLED SUPERVISOR CONFIGURE</a>
        </td>
        <td colspan="3">
            <input type="radio" name="supervisor_config" id="supervisor-config-true" value="true" /> <span>true</span>
            <input type="radio" name="supervisor_config" id="supervisor-config-false" value="false" /> <span>false</span>
        </td>
    </tr>
</table>

<table id="table-tomcat">
    <tr>
        <td class="td-setup-session" colspan="4">
            TOMCAT
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            <a>ENABLED TOMCAT CONFIGURE</a>
        </td>
        <td colspan="3">
            <input type="radio" name="tomcat_config" id="tomcat-config-true" value="true" /> <span>true</span>
            <input type="radio" name="tomcat_config" id="tomcat-config-false" value="false" /> <span>false</span>
            <input type="radio" name="tomcat_config" id="tomcat-config-false" value="spring-boot" /> <span>
                I will be use boarded Tomcat from Spring Boot Project
            </span>
        </td>
    </tr>
</table>

<table id="table-docker">
    <tr>
        <td class="td-setup-session" colspan="4">
            <a>DOCKER</a>
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            DOCKER VERSION (YML)
        </td>
        <td colspan="3">
            <input type="radio" name="docker_compose_version" value="3.0" id="docker-compose-version-30" /> <span>3.0</span>
            <input type="radio" name="docker_compose_version" value="3.1" id="docker-compose-version-31" /> <span>3.1</span>
            <input type="radio" name="docker_compose_version" value="3.2" id="docker-compose-version-32" /> <span>3.2</span>
            | Other
            <input
                    class="input-text-common short-number"
                    type="text"
                    name="docker_compose_version_other"
                    id="docker-compose-version-other" />
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            NETWORK GATEWAY
        </td>
        <td class="td-default">
            <input type="checkbox" name="network_default" id="checkbox-network-gateway" /> <span>default</span>
        </td>
        <td colspan="2">
            <input class="input-text-common" type="text" name="network_gateway" id="input-text-network-gateway" />
        </td>
    </tr>
    <tr>
        <td class="td-field-name" title="Use this feature to inform the dockerized web about the images that should be deleted on uninstall process...">
            <a>
                DOCKER EXTRA IMAGES
            </a>
        </td>
        <td class="td-default">
            <input type="checkbox" name="extra_images_default" id="checkbox-extra-images-none" /> <span>none</span>
        </td>
        <td colspan="2">
            <input
                    class="input-text-common"
                    type="text"
                    name="extra_images"
                    id="input-text-extra-images"
                    placeholder="project1, project2, mysql, tests, redis, mongo..." />
        </td>
    </tr>
</table>

<table id="table-databases-fpm">
    <tr>
        <td class="td-setup-session" colspan="4">
            DATABASES
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            RESOURCES AVAILABLE
        </td>
        <td class="td-default">
            <input type="checkbox" name="resources_default_all" id="checkbox-resources-all" /> <span>all</span>
        </td>
        <td colspan="2">

            <?php
            for ($i = 0; $i < count($available); $i++){
                $res = trim($available[$i]);
                echo '<input 
                    type="checkbox" 
                    name="resources_default[]" 
                    id="checkbox-resources-'.$res.'" 
                    class="checkbox-resources-default" 
                    value="'.$res.'" /> <span>'.$res.' </span>'.PHP_EOL;
            }
            ?>

        </td>
    </tr>
</table>

<table id="table-php-fpm">
    <tr>
        <td class="td-setup-session" colspan="4">
            PHP
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            PHP-FPM VERSION
        </td>
        <td class="td-default">
            <input type="checkbox" name="php_version_all" id="checkbox-php-version-all" /> <span>all</span>
        </td>
        <td colspan="2">

            <?php
            for ($i = 0; $i < count($php_available); $i++){
                $php = trim(str_replace(".", "", $php_available[$i]));
                echo '<input 
                    type="checkbox" 
                    name="php_version[]" 
                    id="checkbox-php-version-'.$php.'" 
                    class="checkbox-php-version" value="'.$php.'" /> <span>'.$php_available[$i].' </span>'.PHP_EOL;
            }
            ?>

        </td>
    </tr>
</table>

<table id="table-java">
    <tr>
        <td class="td-setup-session" colspan="4">
            JAVA
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            JAVA SDK/JDK VERSION
        </td>
        <td class="td-default">
            <input type="checkbox" name="java_version_all" id="checkbox-java-version-all" /> <span>all</span>
        </td>
        <td colspan="2">

            <?php
            for ($i = 0; $i < count($java_available); $i++){
                $php = trim(str_replace(".", "", $java_available[$i]));
                echo '<input 
                    type="checkbox" 
                    name="java_version[]" 
                    id="checkbox-java-version-'.$php.'" 
                    class="checkbox-java-version" value="'.$php.'" /> <span>'.$java_available[$i].' </span>'.PHP_EOL;
            }
            ?>

        </td>
    </tr>
</table>

<table id="table-python">
    <tr>
        <td class="td-setup-session" colspan="4">
            PYTHON
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            PYTHON VERSION
        </td>
        <td class="td-default">
            <input type="checkbox" name="python_version_all" id="checkbox-python-version-all" /> <span>all</span>
        </td>
        <td colspan="2">

            <?php
            for ($i = 0; $i < count($python_available); $i++){
                $php = trim(str_replace(".", "", $python_available[$i]));
                echo '<input 
                    type="checkbox" 
                    name="python_version[]" 
                    id="checkbox-python-version-'.$php.'" 
                    class="checkbox-python-version" value="'.$php.'" /> <span>'.$python_available[$i].' </span>'.PHP_EOL;
            }
            ?>

        </td>
    </tr>
</table>

<table id="table-nodejs">
    <tr>
        <td class="td-setup-session" colspan="4">
            NODEJS
        </td>
    </tr>
    <tr>
        <td class="td-field-name">
            NODEJS VERSION
        </td>
        <td class="td-default">
            <input type="checkbox" name="nodejs_version_all" id="checkbox-nodejs-version-all" /> <span>all</span>
        </td>
        <td colspan="2">

            <?php
            for ($i = 0; $i < count($nodejs_available); $i++){
                $php = trim(str_replace(".", "", $nodejs_available[$i]));
                echo '<input 
                    type="checkbox" 
                    name="nodejs_version[]" 
                    id="checkbox-nodejs-version-'.$php.'" 
                    class="checkbox-nodejs-version" value="'.$php.'" /> <span>'.$nodejs_available[$i].' </span>'.PHP_EOL;
            }
            ?>

        </td>
    </tr>
</table>

<table id="table-add-git-projects">
    <tr>
        <td class="td-setup-session" colspan="4">
            <a>GIT</a>
            <button type="button" value="button-add-git-project" id="button-add-git-project">
                Add Project
            </button>
        </td>
    </tr>
</table>

<table id="table-git-projects">
</table>
