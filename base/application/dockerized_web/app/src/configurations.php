<?php
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

if (isset($_GET['action']) && $_GET['action'] == 'view') {
    $i = 0;
    $document = "";
    $fho = fopen("config/configuration.conf", "r");

    $resume_label = '
            <table id="table-configuration-header">
            <tr>
                <td class="td-setup-session" colspan="10">
                    RESUME CONFIGURATIONS
                </td>
            </tr>';

    $services_label = '
            <table id="table-configuration-services">
            <tr>
                <td class="td-setup-session" colspan="10">
                    SERVICES
                </td>
            </tr>';

    while (!feof($fho)) {
        $line = trim(preg_replace('/[\n\r]/', '', fgets($fho, 4096)));
        if ($line == "") continue;

        /*HEADER CONFIGURATIONS*/
        if (preg_match('/TIP=|IMPORTANT=|\[HEADER-START]|\[INFO-START]|\[INFO-END]|\[GLOBAL-START]|\[TARGET-PROJECTS]|\[DATABASE-START]|\[DATABASE-END]|\[APP-START]|\[APP-END]|\[API-START]|\[API-END]|\[GLOBAL-END]|\[HEADER-END]/', $line, $m, PREG_OFFSET_CAPTURE)) {
            continue;
        }

        if (preg_match('/CONFIGURATION_SETUP/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>CONFIGURATION_SETUP</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/SERVICES_QUANTITY/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>SERVICES_QUANTITY</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/DOCKER_EXTRA_IMAGES/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>DOCKER_EXTRA_IMAGES</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/RESOURCES_DOCKERIZED/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>RESOURCES_DOCKERIZED</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/DOCKER_COMPOSE_VERSION/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>DOCKER_COMPOSE_VERSION</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/NETWORK_GATEWAY/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>NETWORK_GATEWAY</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/USE_PROJECT/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>USE_PROJECT</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/DB_SETUP/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>DB_SETUP</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/APP_SETUP/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>APP_SETUP</td><td>{$tmp[1]}</td></tr>";
        }

        if (preg_match('/API_SETUP/', $line, $m, PREG_OFFSET_CAPTURE)) {
            $tmp = explode("=", $line);
            $line = "<tr><td class='td-field-name'>API_SETUP</td><td>{$tmp[1]}</td></tr>";
        }

        $line = str_replace("[PROJECT-ENV-CONFIGURATION-START]", "<div id='div-configuration-start'>".$resume_label, $line);
        $line = str_replace("[PROJECT-ENV-CONFIGURATION-END]", "</div>", $line);

        /*SERVICES CONFIGURATIONS*/
        $line = str_replace("[SERVICE-START]", "<div id='div-configuration-services'>".$services_label."</table>", $line);
        $line = str_replace("[SERVICE-END]", "</div>", $line);

        if (preg_match('/^#SERVICE-([0-9]+)/', $line, $m, PREG_OFFSET_CAPTURE) && $i == 0) {
            $line = str_replace("#SERVICE-".$i, "<div class='div-configuration-service'>SERVICE-".$i, $line);
            $i++;
        } else if (preg_match('/^#SERVICE-([0-9]+)/', $line, $m, PREG_OFFSET_CAPTURE) && $i > 0) {
            $line = "</div>".str_replace("#SERVICE-".$i, "<div class='div-configuration-service'>SERVICE-".$i, $line);
            $i++;
        }

        $document .= $line.PHP_EOL."<br />";
    }

    fclose($fho);

    echo '<div id="div-make-configurations">'.$document.'</div>';

} else {
    echo '
        <div id="div-generate-configurations">
            '.$buttons_allowed.'
            <div id="jh-typist-container"></div>
        </div>
    ';
}
