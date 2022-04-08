<?php

use Dockerized\Helpers;
use Dockerized\Data;

function showButtonsConfig()
{
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

function htmlSelect(string $str, array $data, string $name, string $id, int $counter): string
{
    return Helpers::buildSelectHtmlElement($str, $data, ['name' => $name, 'id' => $id, 'counter' => $counter]);
}

function getButtonAdd(string $id, string $val, bool $disabled = true): string
{
    $state = ($disabled) ? "disabled" : "enabled";
    return "<input type='button' class='bt-add-app-setting-{$state}' id='{$id}' value='+ Add' data-button-app data-content='{$val}' {$state} />";
}

function resumeLines(string $config_name, string $config_value, string $cols = "1"): string
{
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
    $header .= "<button id='bt-export-configuration' value='download'>Export</button>";
    $header .= "<button id='bt-import-configuration' value='download'>Import</button>";
    $header .= "<button id='bt-collapse-configuration' value='collapse-resume'>Collapse</button>";
    $header .= "<button id='bt-expand-configuration' value='expand-resume'>Expand</button>";
    $header .= "</td></tr>";

    /*CONFIGURATION_SETUP X SERVICES_QUANTITY*/
    $header .= "<tr>";
    $header .= resumeLines("CONFIGURATION_SETUP", $data["HEADER"]["CONFIGURATION_SETUP"]);
    $header .= resumeLines("SERVICES_QUANTITY", $data["HEADER"]["SERVICES_QUANTITY"]);
    $header .= "</tr>";

    /*DOCKER_COMPOSE_VERSION X NETWORK_GATEWAY*/
    $header .= "<tr>";
    $header .= resumeLines("DOCKER_COMPOSE_VERSION", $data["HEADER"]["DOCKER_COMPOSE_VERSION"]);
    $header .= resumeLines("NETWORK_GATEWAY", $data["HEADER"]["NETWORK_GATEWAY"]);
    $header .= "</tr>";

    /*DOCKER EXTRA IMAGES*/
    $docker_extra_img = "";
    for ($i = 0; $i < count($data["HEADER"]["DOCKER_EXTRA_IMAGES"]); $i++) {
        $docker_extra_img .= "<span class='span-cell'>".$data["HEADER"]["DOCKER_EXTRA_IMAGES"][$i]."</span>";
    }
    $header .= "<tr>";
    $header .= resumeLines("DOCKER_EXTRA_IMAGES", $docker_extra_img, "3");
    $header .= "</tr>";

    /*RESOURCES DOCKERIZED*/
    $resources_dockerized = "";
    for ($i = 0; $i < count($data["HEADER"]["RESOURCES_DOCKERIZED"]); $i++) {
        $resources_dockerized .= "<span class='span-cell'> ".$data["HEADER"]["RESOURCES_DOCKERIZED"][$i]."</span>";
    }
    $header .= "<tr>";
    $header .= resumeLines("RESOURCES_DOCKERIZED", $resources_dockerized, "3");
    $header .= "</tr>";

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
            $header .= "<span class='span-cell-light'>public</span>";
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

    $header .= "</table>";
    $header .= "</div>";

    return $header;
}

function technologySettings(string $ref, string $resources, string $resources_version): string {
    $name_ref = strtoupper($ref);
    $settings  = "<td class='td-field-name box-cel-tab1'>{$name_ref}</td>";
    $settings .= "<td class='td-field-name'>{$resources}</td>";
    $settings .= "<td class='td-field-name box-cel-tab1'>{$name_ref} VERSION</td>";
    $settings .= "<td class='td-field-name'>{$resources_version}</td>";
    return $settings;
}

function applicationSettings(string $index, string $setRef, string $setTabName): string
{
    $lower     = strtolower($setRef);
    $upper     = strtoupper($setTabName);
    $tbody_id  = "tb-app-settings-{$lower}-{$index}";
    $button_id = "bt-add-{$lower}-{$index}";
    $tr_id     = "tr-app-settings-{$lower}-{$index}";

    $bt_add    = getButtonAdd($button_id, $index, true);

    $table_ref = "<table class='generic-table'><tbody id='{$tbody_id}'></tbody></table>";
    $settings  = "<tr id='{$tr_id}'>";
    $settings .= "<td class='td-setup-sub-session1' colspan='10'>{$upper} {$bt_add}</td>";
    $settings .= "</tr>";
    $settings .= "<tr>";
    $settings .= "<td class='td-empty' colspan='10'>{$table_ref}</td>";
    $settings .= "</tr>";

    return $settings;
}

function nginxSettings(string $index, string $placeholder, string $data): string
{
    $data_current = explode("=", $data);
    $field_name = $data_current[0] ?? "UNKNOWN";
    $field_value = $data_current[1] ?? "";
    $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
    $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
    $input = "<input 
                    data-server-settings-nginx-{$index} 
                    type='text' 
                    class='generic-input-text'
                    name='server-settings-nginx-{$index}' 
                    value='' 
                    placeholder='{$placeholder}' 
                    {$required} 
                    disabled />";

    $settings  = "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td>";
    $settings .= "<td>{$input}</td>";

    return $settings;
}

function apacheSettings(string $index, string $placeholder, string $nameRef): string
{
    $input    = "<input 
                    data-server-settings-apache-{$index} 
                    type='text' 
                    class='generic-input-text' 
                    name='server-settings-apache-{$index}' 
                    value='' 
                    placeholder='{$placeholder}' 
                    disabled />";
    $settings  = "<td class='td-field-name box-cel'>{$nameRef}</td>";
    $settings .= "<td>{$input}</td>";

    return $settings;
}

function tomcatSpringBootSettings(string $index, string $placeholder, string $nameRef): string
{
    $input    = "<input 
                    data-server-settings-tomcat-{$index} 
                    type='text' 
                    class='generic-input-text'
                    name='server-settings-tomcat-{$index}' 
                    value='' 
                    placeholder='{$placeholder}' 
                    disabled />";
    $settings  = "<td class='td-field-name box-cel'>{$nameRef}</td>";
    $settings .= "<td>{$input}</td>";

    return $settings;
}

function nodejsSettings(string $index, string $placeholder, string $nameRef): string
{
    $input = "<input 
                data-server-settings-node-{$index} 
                type='text' 
                class='generic-input-text'  
                name='server-settings-node-{$index}' 
                value='' 
                placeholder='{$placeholder}' 
                disabled />";
    $settings  = "<td class='td-field-name box-cel'>{$nameRef}</td>";
    $settings .= "<td>{$input}</td>";

    return $settings;
}

function webConfigSettings(string $index, string $placeholder, string $nameRef): string
{
    $input = "<input 
                data-server-settings-web_config-{$index} 
                type='text' 
                class='generic-input-text' 
                name='server-settings-web_config-{$index}' 
                value='' 
                placeholder='{$placeholder}' 
                disabled />";
    $settings  = "<td class='td-field-name box-cel'>{$nameRef}</td>";
    $settings .= "<td>{$input}</td>";

    return $settings;
}

function projectSettings(string $index, string $data): string
{
    $data_current = explode("=", $data);
    $field_name = $data_current[0] ?? "UNKNOWN";
    $field_value = str_replace('"', '', $data_current[1]) ?? "";
    $required = strpos($field_value, "MANDATORY") ? "required='true'" : "";
    $required_class = strpos($field_value, "MANDATORY") ? "required-class" : "";
    $placeholder = $field_value;

    if (
        strpos($field_value, "{{{MANDATORY") ||
        strpos($field_value, "{{{OPTIONAL") ||
        strpos($field_value, "{{{SERVICE_NAME")
    ) {
        $field_value = "";
    }

    $input = "<input 
                        data-generic-settings-project-{$index} 
                        type='text' 
                        class='generic-input-text' 
                        name='generic-settings-project-{$index}' 
                        value='{$field_value}' 
                        placeholder='{$placeholder}' 
                        {$required} />";

    $settings = "<td class='td-field-name box-cel {$required_class}'>{$field_name}</td>";
    $settings .= "<td>{$input}</td>";

    return $settings;
}

function additionalSettings(string $index, string $ref, string $nameRef): string
{
    $tb = "<table class='generic-table'><tbody id='tb-app-settings-{$ref}-{$index}'></tbody></table>";
    $add_bt = getButtonAdd("bt-add-{$ref}-".$index, $index, false);

    $settings  = "<tr>";
    $settings .= "<td class='td-field-name box-cel-tab1' colspan='10'>{$nameRef} {$add_bt}</td>";
    $settings .= "</tr>";
    $settings .= "<tr>";
    $settings .= "<td class='td-empty' colspan='10'>{$tb}</td>";
    $settings .= "</tr>";

    return $settings;
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
        $languages = htmlSelect("Language", Data::getLanguages(), "lang", "language", $i);
        $languages_version = htmlSelect("Language Version", Data::getLanguagesVersion(), "lang", "language_version", $i);
        $servers_type = htmlSelect("Server", Data::getServers(), "srv", "server", $i);
        $server_version= htmlSelect("Server Version", Data::getServersVersion(), "srv", "server_version", $i);

        /**
         * BEGIN SERVICE
         */
        $services .= "<div id='div-service-config-{$i}' class='div-service'>";
        $services .= "<table class='table-service-config'>";

        /**
         * SERVICE IDENTIFY
         */
        $current = str_replace('"', "", explode("=", $data["SERVICES"][$i][0])[1]);
        $services .= "<tr>";
        $services .= "<td data-service-toggle data-content='{$i}' class='td-setup-sub-session hover-tab-service' colspan='10'>SERVICE - {$current}</td>";
        $services .= "</tr>";

        /**
         * TECHNOLOGY SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>TECHNOLOGY SETTINGS</td>";
        $services .= "</tr>";

        /*LANGUAGE X LANGUAGE VERSION*/
        $services .= "<tr>";
        $services .= technologySettings("LANGUAGE", $languages, $languages_version);
        $services .= "</tr>";

        /*SERVER X SERVER VERSION*/
        $services .= "<tr>";
        $services .= technologySettings("SERVER", $servers_type, $server_version);
        $services .= "</tr>";

        /**
         * APPLICATION SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>APPLICATION SETTINGS</td>";
        $services .= "</tr>";

        /*WHEN PHP - DOTENV*/
        $services .= applicationSettings($i, 'php', 'PHP DOTENV');

        /*WHEN JAVA - PROPERTIES*/
        $services .= applicationSettings($i, 'java', 'JAVA FILE PROPERTIES');

        /*WHEN PYTHON - CFG*/
        $services .= applicationSettings($i, 'python', 'PYTHON CFG FILE');

        /*WHEN NODEJS - CONFIGURATION*/
        $services .= applicationSettings($i, 'nodejs', 'NODEJS CONFIGURATION FILE');

        /*WHEN CSHARP - CONFIG*/
        $services .= applicationSettings($i, 'csharp', 'CSHARP CONFIG FILE');

        /**
         * SERVER SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>SERVER SETTINGS</td>";
        $services .= "</tr>";

        /**
         * SERVER SETTINGS: NGINX
         */
        $services .= "<tr id='tr-server-settings-nginx-{$i}'>";
        $services .= "<td class='center' colspan='10'>NGINX</td>";
        $services .= "</tr>";

        /*NGINX_CONF X NGINX_SERVER_NAME*/
        $services .= "<tr>";
        $services .= nginxSettings($i, 'Type Nginx Conf Value', $data["SERVICES"][$i][24]);
        $services .= nginxSettings($i, 'Type Nginx Server Name Value', $data["SERVICES"][$i][25]);
        $services .= "</tr>";

        /*NGINX_ROOT_PATH X NGINX_APP_CONF*/
        $services .= "<tr>";
        $services .= nginxSettings($i, 'Type Nginx Root Path Value', $data["SERVICES"][$i][26]);
        $services .= nginxSettings($i, 'Type Nginx App Conf Value', $data["SERVICES"][$i][27]);
        $services .= "</tr>";

        /*NGINX_LISTEN X NGINX_FAST_CGI_PASS*/
        $services .= "<tr>";
        $services .= nginxSettings($i, 'Type Nginx Listen Value', $data["SERVICES"][$i][28]);
        $services .= nginxSettings($i, 'Type Nginx Fast Cgi Value', $data["SERVICES"][$i][29]);
        $services .= "</tr>";

        /*NGINX72_CONF X NGINX72_RESTFUL_CONF*/
        $services .= "<tr>";
        $services .= nginxSettings($i, 'Type Nginx72 Conf Value', $data["SERVICES"][$i][30]);
        $services .= nginxSettings($i, 'Type Nginx72 Restful Conf Value', $data["SERVICES"][$i][31]);
        $services .= "</tr>";

        /*SUPERVISOR_CONF X NGINX72_RESTFUL_CONF*/
        $services .= "<tr>";
        $services .= nginxSettings($i, 'Type Supervisor Conf Value', $data["SERVICES"][$i][32]);
        $services .= nginxSettings($i, 'Type an value', 'OTHERS');
        $services .= "</tr>";

        /**
         * SERVER SETTINGS: APACHE
         */
        $services .= "<tr id='tr-server-settings-apache-{$i}'>";
        $services .= "<td class='center' colspan='10'>APACHE</td>";
        $services .= "</tr>";

        /*VIRTUAL HOST X VIRTUAL HOST PORT*/
        $services .= "<tr>";
        $services .= apacheSettings($i, 'localhost', 'VIRTUAL HOST');
        $services .= apacheSettings($i, '80', 'VIRTUAL HOST PORT');
        $services .= "</tr>";

        /*SERVER ADMIN X SERVER NAME*/
        $services .= "<tr>";
        $services .= apacheSettings($i, 'admin@example.com', 'SERVER ADMIN');
        $services .= apacheSettings($i, 'sample.local', 'SERVER NAME');
        $services .= "</tr>";

        /*SERVER ALIAS X DOCUMENT ROOT*/
        $services .= "<tr>";
        $services .= apacheSettings($i, 'apache.sample.local', 'SERVER ALIAS');
        $services .= apacheSettings($i, '/var/www/sample.local/public', 'DOCUMENT ROOT');
        $services .= "</tr>";

        /*ERROR LOG NAME X ACCESS LOG NAME (CUSTOM LOG)*/
        $services .= "<tr>";
        $services .= apacheSettings($i, 'log name', 'ERROR LOG NAME');
        $services .= apacheSettings($i, 'log name', 'ACCESS LOG NAME');
        $services .= "</tr>";

        /**
         * SERVER SETTINGS: TOMCAT/JAVA-SPRING-BOOT
         */
        $services .= "<tr id='tr-server-settings-tomcat-{$i}'>";
        $services .= "<td class='center' colspan='10'>TOMCAT (JAVA SPRING BOOT PROJECTS)</td>";
        $services .= "</tr>";

        /*DATABASE SOURCE URL - spring.datasource.url X DATABASE SOURCE USERNAME - spring.datasource.username*/
        $services .= "<tr>";
        $services .= tomcatSpringBootSettings($i, 'jdbc:mysql://localhost:3306/dbname?useTimezone=true&serverTimezone=UTC', 'DATABASE SRC URL (JDBC)');
        $services .= tomcatSpringBootSettings($i, 'user-devel', 'DATABASE SOURCE USERNAME');
        $services .= "</tr>";

        /*DATABASE SOURCE PASSWORD - spring.datasource.password X DATABASE SOURCE DRIVER CLASS - spring.datasource.driver-class-name*/
        $services .= "<tr>";
        $services .= tomcatSpringBootSettings($i, '123456', 'DATABASE PASSWORD');
        $services .= tomcatSpringBootSettings($i, 'com.mysql.jdbc.Driver', 'DATABASE SOURCE DRIVER');
        $services .= "</tr>";

        /*SPRING JPA SHOW SQL - spring.jpa.show-sql X SPRING JPA HIBERNATE AUTO - spring.jpa.hibernate.ddl-auto*/
        $services .= "<tr>";
        $services .= tomcatSpringBootSettings($i, 'true', 'SPRING JPA SHOW SQL');
        $services .= tomcatSpringBootSettings($i, 'update', 'HIBERNATE AUTO');
        $services .= "</tr>";

        /*DATABASE PLATFORM - spring.jpa.database-platform X SERVER PORT - server.port*/
        $services .= "<tr>";
        $services .= tomcatSpringBootSettings($i, 'org.hibernate.dialect.MySQL8Dialect', 'DATABASE PLATFORM');
        $services .= tomcatSpringBootSettings($i, '9000', 'SERVER PORT');
        $services .= "</tr>";

        /*SERVER NAME - server.name X SERVER MODE - spring.main.web-application-type*/
        $services .= "<tr>";
        $services .= tomcatSpringBootSettings($i, 'server', 'SERVER NAME');
        $services .= tomcatSpringBootSettings($i, 'servlet', 'SERVER MODE');
        $services .= "</tr>";

        /*SPRING DOCS PATH - springdoc.api-docs.path*/
        $services .= "<tr>";
        $services .= tomcatSpringBootSettings($i, '/api-docs', 'SPRING DOCS PATH');
        $services .= tomcatSpringBootSettings($i, 'Type any value', 'OTHERS');
        $services .= "</tr>";

        /**
         * SERVER SETTINGS: NODE
         */
        $services .= "<tr id='tr-server-settings-node-{$i}'>";
        $services .= "<td class='center' colspan='10'>NODE</td>";
        $services .= "</tr>";

        /*APPLICATION MODE APPLICATION ROOT*/
        $services .= "<tr>";
        $services .= nodejsSettings($i, "Development/root/test", "APPLICATION MODE");
        $services .= nodejsSettings($i, "url-root-test", "APPLICATION ROOT");
        $services .= "</tr>";

        /*APPLICATION URL X APPLICATION STARTUP FILE*/
        $services .= "<tr>";
        $services .= nodejsSettings($i, "app.test.local", "APPLICATION URL");
        $services .= nodejsSettings($i, "index.js/app.js", "APPLICATION STARTUP FILE");
        $services .= "</tr>";

        /*CONFIGURATION FILE X SERVER PORT*/
        $services .= "<tr>";
        $services .= nodejsSettings($i, "package.json", "CONFIGURATION FILE");
        $services .= nodejsSettings($i, "9898", "SERVER PORT");
        $services .= "</tr>";

        /**
         * SERVER SETTINGS: WEB CONFIG
         */
        $services .= "<tr id='tr-server-settings-web_config-{$i}'>";
        $services .= "<td class='center' colspan='10'>WEB CONFIG</td>";
        $services .= "</tr>";

        /*SERVER NAME X SERVER PORT*/
        $services .= "<tr>";
        $services .= webConfigSettings($i, "server name", "SERVER NAME");
        $services .= webConfigSettings($i, "8888", "SERVER PORT");
        $services .= "</tr>";

        /**
         * PROJECT SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>GENERIC SETTINGS (docker-compose.yml)</td>";
        $services .= "</tr>";

        /*PROJECT_NAME X SERVICE_NAME*/
        $services .= "<tr>";
        $services .= projectSettings($i, $data["SERVICES"][$i][23]);
        $services .= projectSettings($i, $data["SERVICES"][$i][0]);
        $services .= "</tr>";

        /*CONTAINER_NAME X IMAGE*/
        $services .= "<tr>";
        $services .= projectSettings($i, $data["SERVICES"][$i][1]);
        $services .= projectSettings($i, $data["SERVICES"][$i][2]);
        $services .= "</tr>";

        /*LOCALHOST_PORT X INTERNAL_PORT*/
        $services .= "<tr>";
        $services .= projectSettings($i, $data["SERVICES"][$i][4]);
        $services .= projectSettings($i, $data["SERVICES"][$i][5]);
        $services .= "</tr>";

        /*PRIVILEGED X NETWORKS*/
        $services .= "<tr>";
        $services .= projectSettings($i, $data["SERVICES"][$i][3]);
        $services .= projectSettings($i, $data["SERVICES"][$i][6]);
        $services .= "</tr>";

        /*BUILD X CONTEXT*/
        $services .= "<tr>";
        $services .= projectSettings($i, $data["SERVICES"][$i][15]);
        $services .= projectSettings($i, $data["SERVICES"][$i][16]);
        $services .= "</tr>";

        /*DOCKERFILE X WORKING_DIR*/
        $services .= "<tr>";
        $services .= projectSettings($i, $data["SERVICES"][$i][17]);
        $services .= projectSettings($i, $data["SERVICES"][$i][18]);
        $services .= "</tr>";

        /*COMMAND X ENV_FILE*/
        $services .= "<tr>";
        $services .= projectSettings($i, $data["SERVICES"][$i][19]);
        $services .= projectSettings($i, $data["SERVICES"][$i][20]);
        $services .= "</tr>";

        /*DEPLOY X DEPLOY_MEMORY*/
        $services .= "<tr>";
        $services .= projectSettings($i, $data["SERVICES"][$i][21]);
        $services .= projectSettings($i, $data["SERVICES"][$i][22]);
        $services .= "</tr>";

        /*ENVIRONMENT*/
        $services .= additionalSettings($i, "env", "ENVIRONMENT");

        /*VOLUMES*/
        $services .= additionalSettings($i, "volume", "VOLUMES");

        /*LINKS*/
        $services .= additionalSettings($i, "link", "LINKS");

        /*DEPENDS_ON*/
        $services .= additionalSettings($i, "depend", "DEPENDS ON");

        /*FINISH TABLE*/
        $services .= "</table>";
        $services .= "</div>";

    }

    $services .= "</div>";

    return $services;
}

function requestDataConfiguration()
{
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
