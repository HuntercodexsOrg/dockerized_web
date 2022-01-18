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
    $header  = "<table>";
    $header .= "<tr><td class='td-setup-session' colspan='10'>RESUME CONFIGURATION</td></tr>";

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
        $header .= "<tr>";
        $header .= "<td class='td-field-name box-cel'>PROJECT {$i}</td><td colspan='3'>{$data["HEADER"]["USE_PROJECT"][$i]}</td>";
        $header .= "</tr>";
    }

    /*DATABASE SETTINGS*/
    $header .= "<tr>";
    $header .= "<td class='td-setup-sub-session' colspan='10'>DATABASE SETTINGS - APP</td>";
    $header .= "</tr>";
    for ($i = 1; $i < count($data["HEADER"]["USE_DATABASE"]); $i++) {
        $extract = explode("=", $data["HEADER"]["USE_DATABASE"][$i]);
        $name = trim($extract[0]);
        $value = trim(str_replace('"', '', $extract[1]));
        $header .= "<tr>";
        $header .= "<td class='td-field-name box-cel'>DATABASE {$i}</td><td colspan='1'>{$name}</td><td colspan='2'>{$value}</td>";
        $header .= "</tr>";
    }

    /*APP SETTINGS*/
    $header .= "<tr>";
    $header .= "<td class='td-setup-sub-session' colspan='10'>APP SETTINGS URL</td>";
    $header .= "</tr>";
    for ($i = 1; $i < count($data["HEADER"]["USE_APP_URL"]); $i++) {
        $extract = explode("=", $data["HEADER"]["USE_APP_URL"][$i]);
        $name = trim($extract[0]);
        $value = trim(str_replace('"', '', $extract[1]));
        $header .= "<tr>";
        $header .= "<td class='td-field-name box-cel'>APP URL {$i}</td><td colspan='1'>{$name}</td><td colspan='2'>{$value}</td>";
        $header .= "</tr>";
    }

    /*API SETTINGS*/
    $header .= "<tr>";
    $header .= "<td class='td-setup-sub-session' colspan='10'>API SETTINGS URL</td>";
    $header .= "</tr>";
    for ($i = 1; $i < count($data["HEADER"]["USE_API_URL"]); $i++) {
        $extract = explode("=", $data["HEADER"]["USE_API_URL"][$i]);
        $name = trim($extract[0]);
        $value = trim(str_replace('"', '', $extract[1]));
        $header .= "<tr>";
        $header .= "<td class='td-field-name box-cel'>API URL {$i}</td><td colspan='1'>{$name}</td><td colspan='2'>{$value}</td>";
        $header .= "</tr>";
    }

    $header .= "</table>";

    return $header;
}

function servicesMount($data): string
{
    $services = "<table>";
    $services .= "<tr><td class='td-setup-session' colspan='10'>SERVICES CONFIGURATION</td></tr>";
    foreach ($data["SERVICES"] as $k => $v) {
        if (is_array($v)) {
            $val = implode(',', $v);
        } else {
            $val = $v;
        }
        $services .= "<tr><td class='td-field-name'>{$k}</td><td>{$val}</td></tr>";
    }
    $services .= "</table>";

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
