<?php

use Dockerized\Helpers;
use Dockerized\Data;

function showButtonsConfig() {

    $services_qty = Dockerized\Reader::getSetupVar('SERVICES_QUANTITY');

    $buttons_allowed = "";

    if (file_exists('config/setup.txt') && !file_exists('config/configuration.conf')) {
        $buttons_allowed = '
        <button type="button" value="button-generate-configurations" id="button-generate-configurations">
            Generate Configuration
        </button>
        <p class="p-message-default">
            Was found '.$services_qty.' service(s) to configurations generate
        </p>';
    } else {
        $buttons_allowed = '
        <button type="button" value="button-view-configurations" id="button-view-configurations">
            View Configuration
        </button>
        <button type="button" value="button-delete-configurations" id="button-delete-configurations">
            Delete Configuration
        </button>';
    }
    echo '
        <div id="div-generate-configurations">
            '.$buttons_allowed.'
            <div id="jh-typist-container"></div>
        </div>
    ';
}

function htmlSelect(string $str, array $data, string $id, int $counter): string
{
    return Helpers::buildSelectHtmlElement($str, $data, ['id' => $id, 'counter' => $counter]);
}

function getButtonAdd(string $id, string $val, bool $disabled = true): string
{
    $state = ($disabled) ? "disabled" : "enabled";
    return "<input type='button' class='bt-add-app-setting-{$state}' id='{$id}' value='+ Add' data-button-app data-content='{$val}' {$state} />";
}

function resumeLines(string $config_name, string $config_value, string $cols = "1"): string {
    $cfgName = strtoupper(str_replace("_", " ", $config_name));
    $name = strtolower(str_replace(" ", "_", $config_name));
    $hidden_value = trim(strip_tags($config_value));

    $config  = "<td class='td-field-name box-cel'>{$cfgName}</td>";
    $config .= "<td colspan='{$cols}'>{$config_value}";
    $config .= "<input type='hidden' name='{$name}' value='{$hidden_value}' />";
    $config .= "</td>";


    return $config;
}

function headerMount(array $data): string
{
    /*CONFIGURATION RESUME*/

    $header  = "<div id='div-resume-config'>";
    $header .= "<table id='table-resume-config'>";
    $header .= "<tr><td id='resume-config-toggle' class='td-setup-session' colspan='10'>";
    $header .= "<span class='float-left'>CONFIGURATION RESUME</span>";
    $header .= "<button id='bt-save-configuration' value='save'>Save</button>";
    $header .= "<button id='bt-reset-configuration' value='reset'>Reset</button>";
    $header .= "<button id='bt-collapse-configuration' value='collapse-resume'>Collapse</button>";
    $header .= "<button id='bt-expand-configuration' value='expand-resume'>Expand</button>";
    $header .= "</td></tr>";

    /*CONFIGURATION_SETUP X SERVICES_QUANTITY*/
    $header .= "<tr>";
    $header .= resumeLines("CONFIGURATION_SETUP", $data["HEADER"]["CONFIGURATION_SETUP"]);
    $header .= resumeLines("SERVICES_QUANTITY", $data["HEADER"]["SERVICES_QUANTITY"]);
    $header .= "</tr>";

    //--------------------------------------------------------------------------------------------------------

    /*DOCKER_COMPOSE_VERSION X NETWORK_GATEWAY*/
    $header .= "<tr>";
    $header .= resumeLines("DOCKER_COMPOSE_VERSION", $data["HEADER"]["DOCKER_COMPOSE_VERSION"]);
    $header .= resumeLines("NETWORK_GATEWAY", $data["HEADER"]["NETWORK_GATEWAY"]);
    $header .= "</tr>";

    //--------------------------------------------------------------------------------------------------------

    /*DOCKER EXTRA IMAGES*/
    $docker_extra_img = "";
    for ($i = 0; $i < count($data["HEADER"]["DOCKER_EXTRA_IMAGES"]); $i++) {
        $docker_extra_img .= "<span class='span-cell'>".$data["HEADER"]["DOCKER_EXTRA_IMAGES"][$i]."</span>";
    }
    $header .= "<tr>";
    $header .= resumeLines("DOCKER_EXTRA_IMAGES", $docker_extra_img, "3");
    $header .= "</tr>";

    //--------------------------------------------------------------------------------------------------------

    /*RESOURCES DOCKERIZED*/
    $resources_dockerized = "";
    for ($i = 0; $i < count($data["HEADER"]["RESOURCES_DOCKERIZED"]); $i++) {
        $resources_dockerized .= "<span class='span-cell'> ".$data["HEADER"]["RESOURCES_DOCKERIZED"][$i]."</span>";
    }
    $header .= "<tr>";
    $header .= resumeLines("RESOURCES_DOCKERIZED", $resources_dockerized, "3");
    $header .= "</tr>";

    //--------------------------------------------------------------------------------------------------------

    /*USE PROJECTS FROM GITHUB*/
    $header .= "<tr>";
    $header .= "<td class='td-setup-sub-session1' colspan='10'>USE PROJECTS FROM GITHUB</td>";
    $header .= "</tr>";
    for ($i = 0; $i < count($data["HEADER"]["USE_PROJECT"]); $i++) {

        $gp = $data["HEADER"]["USE_PROJECT"][$i];
        $header .= "<tr>";
        $header .= "<td class='td-field-name box-cel'>PROJECT {$i}</td>";
        $header .= "<td colspan='2'>{$gp}";
        $header .= "<input type='hidden' name='git_project[]' value='{$gp}' />";
        if (!preg_match("/GITHUB_TOKEN/", $gp, $m, PREG_OFFSET_CAPTURE)) {
            $header .= "<span class='span-cell'>[PUBLIC]</span>";
        }
        $header .= "</td>";

        $header .= "<td class='td-token-git'>";
        if (preg_match("/GITHUB_TOKEN/", $gp, $m, PREG_OFFSET_CAPTURE)) {
            $header .= "<input type='password' name='git_project_key[]' value='' placeholder='Type Git Hub Token' required />";
        } else {
            $header .= "<input type='password' name='git_project_key[]' value='public' disabled />";
        }
        $header .= "</td>";
        $header .= "</tr>";
    }

    //--------------------------------------------------------------------------------------------------------

    $header .= "</table>";
    $header .= "</div>";

    return $header;
}

function servicesMount($data): string
{
    /**
     * CONFIGURATION SERVICES
     */
    $services  = "<div id='div-services-config'>";

    $services .= "<table id='table-services-config'>";
    $services .= "<tr><td class='td-setup-session' colspan='10'>";
    $services .= "CONFIGURATION SERVICES";
    $services .= "<button id='bt-collapse-services'>Collapse All</button>";
    $services .= "<button id='bt-expand-services'>Expand All</button>";
    $services .= "</td></tr>";
    $services .= "</table>";

    for ($i = 0; $i < count($data["SERVICES"]); $i++) {

        /**
         * DATA SELECT HTMLElement
         */
        $languages = htmlSelect("Language", Data::getLanguages(), "language", $i);
        $languages_version = htmlSelect("Language Version", Data::getLanguagesVersion(), "language_version", $i);
        $servers_type = htmlSelect("Server", Data::getServers(), "server", $i);
        $server_version= htmlSelect("Server Version", Data::getServersVersion(), "server_version", $i);

        /**
         * BEGIN SERVICE
         */
        $services .= "<div id='div-service-config-{$i}' class='div-service'>";
        $services .= "<table class='table-service-config'>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVICE IDENTIFY
         */
        $current = str_replace('"', "", explode("=", $data["SERVICES"][$i][0])[1]);
        $services .= "<tr>";
        $services .= "<td data-service-toggle data-content='{$i}' class='td-setup-sub-session hover-tab-service' colspan='10'>SERVICE - {$current}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * TECHNOLOGY SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>TECHNOLOGY SETTINGS</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*LANGUAGE X LANGUAGE VERSION*/
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1'>LANGUAGE</td>";
        $services .= "<td class='td-field-name'>{$languages}</td>";
        $services .= "<td class='td-field-name box-cel-tab1'>LANGUAGE VERSION</td>";
        $services .= "<td class='td-field-name'>{$languages_version}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*SERVER X SERVER VERSION*/
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1'>SERVER</td>";
        $services .= "<td class='td-field-name'>{$servers_type}</td>";
        $services .= "<td class='td-field-name box-cel-tab1'>SERVER VERSION</td>";
        $services .= "<td class='td-field-name'>{$server_version}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * APPLICATION SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>APPLICATION SETTINGS</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN PHP - DOTENV*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-php-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-php-".$i, $i, true);
        $services .= "<tr>";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>PHP DOTENV {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN JAVA - PROPERTIES*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-java-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-java-".$i, $i, true);
        $services .= "<tr id='tr-app-settings-java-{$i}'>";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>JAVA PROPERTIES FILE {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN PYTHON - CFG*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-python-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-python-".$i, $i, true);
        $services .= "<tr id='tr-app-settings-python-{$i}' >";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>PYTHON CFG FILE {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN NODEJS - CONFIGURATION*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-nodejs-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-nodejs-".$i, $i, true);
        $services .= "<tr id='tr-app-settings-node-{$i}'>";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>NODEJS CONFIGURATION FILE {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN CSHARP - CONFIG*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-csharp-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-csharp-".$i, $i, true);
        $services .= "<tr id='tr-app-settings-csharp-{$i}' >";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>CSHARP CONFIG FILE {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>SERVER SETTINGS</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: NGINX
         */
        $services .= "<tr id='tr-server-settings-nginx-{$i}'>";
        $services .= "<td class='center' colspan='10'>NGINX</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*NGINX_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][24]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Nginx Conf Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*NGINX_SERVER_NAME*/
        $data_current = explode("=", $data["SERVICES"][$i][25]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Nginx Server Name Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*NGINX_ROOT_PATH*/
        $data_current = explode("=", $data["SERVICES"][$i][26]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Nginx Root Path Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*NGINX_APP_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][27]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Nginx App Conf Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*NGINX_LISTEN*/
        $data_current = explode("=", $data["SERVICES"][$i][28]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Nginx Listen Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*NGINX_FAST_CGI_PASS*/
        $data_current = explode("=", $data["SERVICES"][$i][29]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Nginx Fast Cgi Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*NGINX72_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][30]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Nginx72 Conf Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*NGINX72_RESTFUL_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][31]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Nginx72 Restful Conf Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*SUPERVISOR_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][32]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $input = "<input data-server-settings-nginx-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Type Supervisor Conf Value' {$required} disabled />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: APACHE
         */
        $services .= "<tr id='tr-server-settings-apache-{$i}'>";
        $services .= "<td class='center' colspan='10'>APACHE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*VIRTUAL HOST*/
        $input = "<input data-server-settings-apache-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='localhost' disabled />";
        $services .= "<td class='td-field-name box-cel'>VIRTUAL HOST</td><td>{$input}</td>";

        /*VIRTUAL HOST PORT*/
        $input = "<input data-server-settings-apache-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='80' disabled />";
        $services .= "<td class='td-field-name box-cel'>VIRTUAL HOST PORT</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*SERVER ADMIN*/
        $input = "<input data-server-settings-apache-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='admin@example.com' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER ADMIN</td><td>{$input}</td>";

        /*SERVER NAME*/
        $input = "<input data-server-settings-apache-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='sample.local' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER NAME</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*SERVER ALIAS*/
        $input = "<input data-server-settings-apache-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='apache.sample.local' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER ALIAS</td><td>{$input}</td>";

        /*DOCUMENT ROOT*/
        $input = "<input data-server-settings-apache-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='/var/www/sample.local/public' disabled />";
        $services .= "<td class='td-field-name box-cel'>DOCUMENT ROOT</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*ERROR LOG NAME*/
        $input = "<input data-server-settings-apache-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='log name' disabled />";
        $services .= "<td class='td-field-name box-cel'>ERRO LOG NAMES</td><td>{$input}</td>";

        /*ACCESS LOG NAME (CUSTOM LOG)*/
        $input = "<input data-server-settings-apache-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='log name' disabled />";
        $services .= "<td class='td-field-name box-cel'>ACCESS LOG NAMES</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: TOMCAT
         */
        $services .= "<tr id='tr-server-settings-tomcat-{$i}'>";
        $services .= "<td class='center' colspan='10'>TOMCAT</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*DATABASE SOURCE URL - spring.datasource.url*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='jdbc:mysql://localhost:3306/dbname?useTimezone=true&serverTimezone=UTC' disabled />";
        $services .= "<td class='td-field-name box-cel'>DATABASE SRC URL (JDBC)</td><td>{$input}</td>";

        /*DATABASE SOURCE USERNAME - spring.datasource.username*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='devel' disabled />";
        $services .= "<td class='td-field-name box-cel'>DATABASE SOURCE USERNAME</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*DATABASE SOURCE PASSWORD - spring.datasource.password*/
        $input = "<input data-server-settings-tomcat-{$i} type='password' class='generic-input-text' id='' name='' value='' placeholder='123456' disabled />";
        $services .= "<td class='td-field-name box-cel'>DATABASE PASSWORD</td><td>{$input}</td>";

        /*DATABASE SOURCE DRIVER CLASS - spring.datasource.driver-class-name*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='com.mysql.jdbc.Driver' disabled />";
        $services .= "<td class='td-field-name box-cel'>DATABASE SOURCE DRIVER</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*SPRING JPA SHOW SQL - spring.jpa.show-sql*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='true' disabled />";
        $services .= "<td class='td-field-name box-cel'>SPRING JPA SHOW SQL</td><td>{$input}</td>";

        /*SPRING JPA HIBERNATE AUTO - spring.jpa.hibernate.ddl-auto*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='update' disabled />";
        $services .= "<td class='td-field-name box-cel'>HIBERNATE AUTO</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*DATABASE PLATFORM - spring.jpa.database-platform*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='org.hibernate.dialect.MySQL8Dialect' disabled />";
        $services .= "<td class='td-field-name box-cel'>DATABASE PLATFORM</td><td>{$input}</td>";

        /*SERVER PORT - server.port*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='9000' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER PORT</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*SERVER NAME - server.name*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='server' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER NAME</td><td>{$input}</td>";

        /*SERVER MODE - spring.main.web-application-type*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='servlet' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER MODE</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*SPRING DOCS PATH - springdoc.api-docs.path*/
        $input = "<input data-server-settings-tomcat-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='/api-docs' disabled />";
        $services .= "<td class='td-field-name box-cel'>SPRING DOCS PATH</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: NODE
         */
        $services .= "<tr id='tr-server-settings-node-{$i}'>";
        $services .= "<td class='center' colspan='10'>NODE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*APPLICATION MODE*/
        $input = "<input data-server-settings-node-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='Development/root/test' disabled />";
        $services .= "<td class='td-field-name box-cel'>APPLICATION MODE</td><td>{$input}</td>";

        /*APPLICATION ROOT*/
        $input = "<input data-server-settings-node-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='url-root-test' disabled />";
        $services .= "<td class='td-field-name box-cel'>APPLICATION ROOT</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*APPLICATION URL*/
        $input = "<input data-server-settings-node-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='app.test.local' disabled />";
        $services .= "<td class='td-field-name box-cel'>APPLICATION URL</td><td>{$input}</td>";

        /*APPLICATION STARTUP FILE*/
        $input = "<input data-server-settings-node-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='index.js/app.js' disabled />";
        $services .= "<td class='td-field-name box-cel'>APPLICATION STARTUP FILE</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*CONFIGURATION FILE*/
        $input = "<input data-server-settings-node-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='package.json' disabled />";
        $services .= "<td class='td-field-name box-cel'>CONFIGURATION FILE</td><td>{$input}</td>";

        /*SERVER PORT*/
        $input = "<input data-server-settings-node-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='9898' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER PORT</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: WEB CONFIG
         */
        $services .= "<tr id='tr-server-settings-web_config-{$i}'>";
        $services .= "<td class='center' colspan='10'>WEB CONFIG</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*SERVER NAME*/
        $input = "<input data-server-settings-web_config-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='server name' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER NAME</td><td>{$input}</td>";

        /*SERVER PORT*/
        $input = "<input data-server-settings-web_config-{$i} type='text' class='generic-input-text' id='' name='' value='' placeholder='8888' disabled />";
        $services .= "<td class='td-field-name box-cel'>SERVER PORT</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * PROJECT SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>GENERIC SETTINGS</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*PROJECT_NAME*/
        $data_current = explode("=", $data["SERVICES"][$i][23]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*SERVICE_NAME*/
        $data_current = explode("=", $data["SERVICES"][$i][0]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*CONTAINER_NAME*/
        $data_current = explode("=", $data["SERVICES"][$i][1]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*IMAGE*/
        $data_current = explode("=", $data["SERVICES"][$i][2]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*LOCALHOST_PORT*/
        $data_current = explode("=", $data["SERVICES"][$i][4]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*INTERNAL_PORT*/
        $data_current = explode("=", $data["SERVICES"][$i][5]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*PRIVILEGED*/
        $data_current = explode("=", $data["SERVICES"][$i][3]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*NETWORKS*/
        $data_current = explode("=", $data["SERVICES"][$i][6]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*BUILD*/
        $data_current = explode("=", $data["SERVICES"][$i][15]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*CONTEXT*/
        $data_current = explode("=", $data["SERVICES"][$i][16]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*DOCKERFILE*/
        $data_current = explode("=", $data["SERVICES"][$i][17]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*WORKING_DIR*/
        $data_current = explode("=", $data["SERVICES"][$i][18]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*COMMAND*/
        $data_current = explode("=", $data["SERVICES"][$i][19]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*ENV_FILE*/
        $data_current = explode("=", $data["SERVICES"][$i][20]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";

        /*DEPLOY*/
        $data_current = explode("=", $data["SERVICES"][$i][21]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";

        /*DEPLOY_MEMORY*/
        $data_current = explode("=", $data["SERVICES"][$i][22]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = str_replace('"', '', $data_current[1]) ?? "";
        $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
        $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
        $placeholder = $field_value;
        if (strpos($field_value, "{{{MANDATORY") || strpos($field_value, "{{{OPTIONAL") || strpos($field_value, "{{{SERVICE_NAME")) {$field_value = "";}
        $input = "<input data-generic-settings-project-{$i} type='text' class='generic-input-text' id='' name='' value='{$field_value}' placeholder='{$placeholder}' {$required} />";
        $services .= "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td><td>{$input}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*ENVIRONMENT*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-env-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-env-".$i, $i, false);
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1' colspan='10'>ENVIRONMENT {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*VOLUMES*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-volume-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-volume-".$i, $i, false);
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1' colspan='10'>VOLUMES {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*LINKS*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-link-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-link-".$i, $i, false);
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1' colspan='10'>LINKS {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*DEPENDS_ON*/
        $tb = "<table class='generic-table'><tbody id='tb-app-settings-depend-{$i}'></tbody></table>";
        $add_bt = getButtonAdd("bt-add-depend-".$i, $i, false);
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1' colspan='10'>DEPENDS ON {$add_bt}</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-empty' colspan='10'>{$tb}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "</table>";
        $services .= "</div>";

    }

    $services .= "</div>";

    return $services;
}

function requestDataConfiguration() {

    $config_file = "config/configuration.conf";
    $data = Dockerized\Mapper::mapperConfiguration($config_file);
    $header = headerMount($data);
    $services = servicesMount($data);

    echo '
        <div id="div-make-configurations">
            '.$header.'
            '.$services.'
        </div>';

}

if (isset($_GET['action']) && $_GET['action'] == 'view') {
    requestDataConfiguration();
} else {
    showButtonsConfig();
}
