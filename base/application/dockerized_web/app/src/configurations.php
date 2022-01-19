<?php

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

function headerMount($data): string
{
    $header  = "<div id='div-resume-config'>";
    $header .= "<table id='table-resume-config'>";
    $header .= "<tr><td id='resume-config-toggle' class='td-setup-session' colspan='10'>RESUME CONFIGURATION</td></tr>";

    $header .= "<tr>";
    $header .= "<td class='td-field-name box-cel'>CONFIGURATION SETUP</td><td>{$data["HEADER"]["CONFIGURATION_SETUP"]}</td>";
    $header .= "<td class='td-field-name box-cel'>SERVICES QUANTITY</td><td>{$data["HEADER"]["SERVICES_QUANTITY"]}</td>";
    $header .= "</tr>";

    $header .= "<tr>";
    $header .= "<td class='td-field-name box-cel'>DOCKER COMPOSE VERSION</td><td>{$data["HEADER"]["DOCKER_COMPOSE_VERSION"]}</td>";
    $header .= "<td class='td-field-name box-cel'>NETWORK GATEWAY</td><td>{$data["HEADER"]["NETWORK_GATEWAY"]}</td>";
    $header .= "</tr>";

    /*DOCKER EXTRA IMAGES*/
    $docker_extra_img = "";
    for ($i = 0; $i < count($data["HEADER"]["DOCKER_EXTRA_IMAGES"]); $i++) {
        $docker_extra_img .= "<span class='span-cell'>".$data["HEADER"]["DOCKER_EXTRA_IMAGES"][$i]."</span>";
    }
    $header .= "<tr>";
    $header .= "<td class='td-field-name box-cel'>DOCKER EXTRA IMAGES</td><td colspan='3'>{$docker_extra_img}</td>";
    $header .= "</tr>";

    /*RESOURCES DOCKERIZED*/
    $resources_dockerized = "";
    for ($i = 0; $i < count($data["HEADER"]["RESOURCES_DOCKERIZED"]); $i++) {
        $resources_dockerized .= "<span class='span-cell'>".$data["HEADER"]["RESOURCES_DOCKERIZED"][$i]."</span>";
    }
    $header .= "<tr>";
    $header .= "<td class='td-field-name box-cel'>RESOURCES DOCKERIZED</td><td colspan='3'>{$resources_dockerized}</td>";
    $header .= "</tr>";

    /*GIT PROJECTS*/
    $header .= "<tr>";
    $header .= "<td class='td-setup-sub-session' colspan='10'>USE PROJECTS FROM GIT</td>";
    $header .= "</tr>";
    for ($i = 0; $i < count($data["HEADER"]["USE_PROJECT"]); $i++) {

        $gp = $data["HEADER"]["USE_PROJECT"][$i];
        $header .= "<tr>";
        $header .= "<td class='td-field-name box-cel'>PROJECT {$i}</td><td colspan='2'>{$gp}</td>";

        if (preg_match("/GITHUB_TOKEN/", $gp, $m, PREG_OFFSET_CAPTURE)) {
            $git_token = "<input type='password' name='' id='' value='' placeholder='Type Git Hub Token' />";
            $header .= "<td class='td-token-git'>{$git_token}</td>";
        } else {
            $header .= "<td class='td-token-git'>Public</td>";
        }

        $header .= "</tr>";
    }

    $header .= "</table>";
    $header .= "</div>";

    return $header;
}

function servicesMount($data): string
{
    $languages = Dockerized\Helpers::buildSelectHtmlElement("Language", Dockerized\Data::getLanguages());
    $languages_version = Dockerized\Helpers::buildSelectHtmlElement("Language Version", Dockerized\Data::getLanguagesVersion("PHP"));
    $servers_type = Dockerized\Helpers::buildSelectHtmlElement("Servers", Dockerized\Data::getServers());
    $server_version = Dockerized\Helpers::buildSelectHtmlElement("Server Version", Dockerized\Data::getServersVersion("NGINX"));

    $services  = "<div id='div-services-config'>";

    $services .= "<table id='table-services-config'>";
    $services .= "<tr><td class='td-setup-session' colspan='10'>SERVICES CONFIGURATION</td></tr>";
    $services .= "</table>";

    for ($i = 0; $i < count($data["SERVICES"]); $i++) {

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
        $services .= "<td data-service-toggle data-content='{$i}' class='td-setup-sub-session pointer' colspan='10'>SERVICE - {$current}</td>";
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
        $services .= "<tr>";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>PHP DOTENV</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel'>ENV NAME</td><td>NAME</td><td>VALUE</td><td>DELETE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN JAVA - PROPERTIES*/
        $services .= "<tr>";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>JAVA PROPERTIES FILE</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel'>PROPS NAME</td><td>NAME</td><td>VALUE</td><td>DELETE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN PYTHON - CFG*/
        $services .= "<tr>";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>PYTHON CFG FILE</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel'>CFG NAME</td><td>NAME</td><td>VALUE</td><td>DELETE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN NODEJS - CONFIGURATION*/
        $services .= "<tr>";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>NODEJS CONFIGURATION FILE</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel'>NODE NAME</td><td>NAME</td><td>VALUE</td><td>DELETE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*WHEN CSHARP - CONFIG*/
        $services .= "<tr>";
        $services .= "<td class='td-setup-sub-session1' colspan='10'>CSHARP CONFIG FILE</td>";
        $services .= "</tr>";
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel'>C# NAME</td><td>NAME</td><td>VALUE</td><td>DELETE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * GENERIC SETTINGS
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab' colspan='10'>GENERIC SETTINGS</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*PROJECT_NAME*/
        $data_current = explode("=", $data["SERVICES"][$i][23]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*SERVICE_NAME*/
        $data_current = explode("=", $data["SERVICES"][$i][0]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*CONTAINER_NAME*/
        $data_current = explode("=", $data["SERVICES"][$i][1]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*IMAGE*/
        $data_current = explode("=", $data["SERVICES"][$i][2]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*LOCALHOST_PORT*/
        $data_current = explode("=", $data["SERVICES"][$i][4]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*INTERNAL_PORT*/
        $data_current = explode("=", $data["SERVICES"][$i][5]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*PRIVILEGED*/
        $data_current = explode("=", $data["SERVICES"][$i][3]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*NETWORKS*/
        $data_current = explode("=", $data["SERVICES"][$i][6]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*BUILD*/
        $data_current = explode("=", $data["SERVICES"][$i][15]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*CONTEXT*/
        $data_current = explode("=", $data["SERVICES"][$i][16]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*DOCKERFILE*/
        $data_current = explode("=", $data["SERVICES"][$i][17]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*WORKING_DIR*/
        $data_current = explode("=", $data["SERVICES"][$i][18]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*COMMAND*/
        $data_current = explode("=", $data["SERVICES"][$i][19]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*ENV_FILE*/
        $data_current = explode("=", $data["SERVICES"][$i][20]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";

        /*DEPLOY*/
        $data_current = explode("=", $data["SERVICES"][$i][21]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*DEPLOY_MEMORY*/
        $data_current = explode("=", $data["SERVICES"][$i][22]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*ENVIRONMENT*/
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1' colspan='10'>ENVIRONMENT</td>";
        $services .= "</tr>";

        $services .= "<tr>";
        $data_current = explode("=", $data["SERVICES"][$i][7]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>ENV</td><td colspan='2'>{$field_value}</td><td>DELETE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*VOLUMES*/
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1' colspan='10'>VOLUMES</td>";
        $services .= "</tr>";

        $services .= "<tr>";
        $data_current = explode("=", $data["SERVICES"][$i][9]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>VOLUME</td><td colspan='2'>{$field_value}</td><td>DELETE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*LINKS*/
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1' colspan='10'>LINKS</td>";
        $services .= "</tr>";

        $services .= "<tr>";
        $data_current = explode("=", $data["SERVICES"][$i][11]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>LINK</td><td colspan='2'>{$field_value}</td><td>DELETE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /*DEPENDS_ON*/
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1' colspan='10'>DEPENDS_ON</td>";
        $services .= "</tr>";

        $services .= "<tr>";
        $data_current = explode("=", $data["SERVICES"][$i][13]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>DEPEND</td><td colspan='2'>{$field_value}</td><td>DELETE</td>";
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
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1 center' colspan='10'>NGINX</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*NGINX_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][24]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*NGINX_SERVER_NAME*/
        $data_current = explode("=", $data["SERVICES"][$i][25]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*NGINX_ROOT_PATH*/
        $data_current = explode("=", $data["SERVICES"][$i][26]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*NGINX_APP_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][27]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*NGINX_LISTEN*/
        $data_current = explode("=", $data["SERVICES"][$i][28]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*NGINX_FAST_CGI_PASS*/
        $data_current = explode("=", $data["SERVICES"][$i][29]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*NGINX72_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][30]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";

        /*NGINX72_RESTFUL_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][31]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        $services .= "<tr>";
        /*SUPERVISOR_CONF*/
        $data_current = explode("=", $data["SERVICES"][$i][32]);
        $field_name = $data_current[0] ?? "UNKNOWN";
        $field_value = $data_current[1] ?? "";
        $services .= "<td class='td-field-name box-cel'>{$field_name}</td><td>{$field_value}</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: APACHE
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1 center' colspan='10'>APACHE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: TOMCAT
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1 center' colspan='10'>TOMCAT</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: NODE
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1 center' colspan='10'>NODE</td>";
        $services .= "</tr>";
        //--------------------------------------------------------------------------------------------------------

        /**
         * SERVER SETTINGS: WEB CONFIG
         */
        $services .= "<tr>";
        $services .= "<td class='td-field-name box-cel-tab1 center' colspan='10'>WEB CONFIG</td>";
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
    /*var_dump("<pre>", $data["HEADER"], "</pre>");
    var_dump("<pre>", $data["SERVICES"], "</pre>");*/
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
