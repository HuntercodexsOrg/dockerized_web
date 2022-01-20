
let seq = 1;
let git_project_from_setup = "";
let dockerized_theme = "default";
let resume_height = "35px";

function activeHamburger() {
    jH("#icon-menu-header-open").on('click', function() {
        jH("#div-icon-hamburger").html(
            $$.icons({
                icon: "close",
                id: "icon-menu-header-close",
                size: "s-32",
                data: "menu-header",
                color: "#FFFFFF"
            }).draw()
        );
        activeClose();
        jH("#div-menu-itens").width("20%");
        jH("#div-content").width("80%");
        jH("#div-content").left("20%");
    });
}

function activeClose() {
    jH("#icon-menu-header-close").on('click', function() {
        jH("#div-icon-hamburger").html(
            $$.icons({
                icon: "hamburger",
                id: "icon-menu-header-open",
                size: "s-32",
                data: "menu-header",
                color: "#FFFFFF"
            }).draw()
        );
        activeHamburger();
        jH("#div-menu-itens").width("0px");
        jH("#div-content").width("100%");
        jH("#div-content").left("0px");
    });
}

function checkFormSetup() {

    /*ENABLED CONFIGURATION SETUP*/
    if (jH('#config-setup-true').isChecked() === false && jH('#config-setup-false').isChecked() === false) {
        $$.alert({
            title: "Error",
            text: "Select a valid CONFIGURATION SETUP (true|false) !",
            theme: dockerized_theme,
            button: "OK"
        });
        return false;
    }

    /*SERVICES QUANTITY*/
    if (jH('#services-qty').val() === "") {
        $$.alert({
            title: "Error",
            text: "Type a correct SERVICES QUANTITY !",
            theme: dockerized_theme,
            button: "OK"
        });
        return false;
    }

    /*ENABLED NGINX CONFIGURE*/
    if (jH('#nginx-config-true').isChecked() === false && jH('#nginx-config-false').isChecked() === false) {
        $$.alert({
            title: "Error",
            text: "Select a valid ENABLED NGINX CONFIGURE (true|false) !",
            theme: dockerized_theme,
            button: "OK"
        });
        return false;
    }

    /*ENABLED APACHE CONFIGURE*/
    if (jH('#apache-config-true').isChecked() === false && jH('#apache-config-false').isChecked() === false) {
        $$.alert({
            title: "Error",
            text: "Select a valid ENABLED APACHE CONFIGURE (true|false) !",
            theme: dockerized_theme,
            button: "OK"
        });
        return false;
    }

    /*ENABLED SUPERVISOR CONFIGURE*/
    if (jH('#supervisor-config-true').isChecked() === false && jH('#supervisor-config-false').isChecked() === false) {
        $$.alert({
            title: "Error",
            text: "Select a valid ENABLED SUPERVISOR CONFIGURE (true|false) !",
            theme: dockerized_theme,
            button: "OK"
        });
        return false;
    }

    /*DOCKER COMPOSE VERSION*/
    if (
        jH('#docker-compose-version-30').isChecked() === false &&
        jH('#docker-compose-version-31').isChecked() === false &&
        jH('#docker-compose-version-32').isChecked() === false &&
        jH('#docker-compose-version-other').val() === ""
    ) {
        $$.alert({
            title: "Error",
            text: "Select a valid DOCKER COMPOSE VERSION !",
            theme: dockerized_theme,
            button: "OK"
        });
        return false;
    }

    /*NETWORK GATEWAY*/
    if (jH('#input-text-network-gateway').val() === "") {
        $$.alert({
            title: "Error",
            text: "Type a valid NETWORK GATEWAY !",
            theme: dockerized_theme,
            button: "OK"
        });
        return false;
    }

    /*RESOURCES TO DOCKERIZED*/
    if (jH('#checkbox-resources-all').isChecked() === true) {
        if (jH('.checkbox-resources-default').isChecked() === false) {
            $$.alert({
                title: "Error",
                text: "Select a valid RESOURCES TO DOCKERIZED !",
                theme: dockerized_theme,
                button: "OK"
            });
            return false;
        }
    }

    /*DOCKER EXTRA IMAGES*/
    if (
        jH('#checkbox-extra-images-none').isChecked() === false &&
        jH('#input-text-extra-images').val() === ""
    ) {
        $$.alert({
            title: "Error",
            text: "Type a valid DOCKER EXTRA IMAGES or set checkbox none !",
            theme: dockerized_theme,
            button: "OK"
        });
        return false;
    }

    /*PHP-FPM VERSION*/
    if (jH('#checkbox-php-version-all').isChecked() === true) {
        if (jH('.checkbox-php-version').isChecked() === false) {
            $$.alert({
                title: "Error",
                text: "Select a valid PHP-FPM VERSION !",
                theme: dockerized_theme,
                button: "OK"
            });
            return false;
        }
    }

    /*JAVA VERSION*/
    if (jH('#checkbox-java-version-all').isChecked() === true) {
        if (jH('.checkbox-java-version').isChecked() === false) {
            $$.alert({
                title: "Error",
                text: "Select a valid JAVA VERSION !",
                theme: dockerized_theme,
                button: "OK"
            });
            return false;
        }
    }

    /*PYTHON VERSION*/
    if (jH('#checkbox-python-version-all').isChecked() === true) {
        if (jH('.checkbox-python-version').isChecked() === false) {
            $$.alert({
                title: "Error",
                text: "Select a valid PYTHON VERSION !",
                theme: dockerized_theme,
                button: "OK"
            });
            return false;
        }
    }

    /*NODEJS VERSION*/
    if (jH('#checkbox-nodejs-version-all').isChecked() === true) {
        if (jH('.checkbox-nodejs-version').isChecked() === false) {
            $$.alert({
                title: "Error",
                text: "Select a valid NODEJS VERSION !",
                theme: dockerized_theme,
                button: "OK"
            });
            return false;
        }
    }

    /*USERNAME|PROJECT*/
    let count_projects = jH('input[name*="git_project_private"]').count();
    let valid = false;
    for (let i = 0; i < count_projects; i++) {
        valid = jH('#git-username-' + i).val() !== "" && jH('#git-project-' + i).val() !== "";
    }

    if (valid === false) {
        $$.alert({
            title: "Error",
            text: "Invalid USERNAME/PROJECT to GITHUB project\ninform at least one user and project.",
            theme: dockerized_theme,
            button: "OK"
        });
    }

    if (valid) {
        /*CHECK QUANTITY SERVICES WITH SERVICES INTO SETUP*/
        let count_resources = jH('.checkbox-resources-default').count('checked');

        if (jH('#nginx-config-true').isChecked()) {
            let total_projects_expected = (count_projects + count_resources);
            if (total_projects_expected !== parseInt(jH('#services-qty').val())) {
                $$.alert({
                    title: "Error",
                    text: "Invalid SERVICES QUANTITY to project and resources !",
                    theme: dockerized_theme,
                    button: "OK"
                });
                valid = false;
            }
        }
    }

    return valid;

}

function checkSubmit() {

    let form_type = jH("#form-type").val();

    switch (form_type) {
        case "setup":
            if (checkFormSetup())  {
                return true;
            }
            break;
        default:
            $$.alert({
                title: "Error",
                text: "Invalid Form Type",
                theme: dockerized_theme,
                button: "OK"
            });
    }

    return false;
}

function requestSetup() {
    $$.ajax({
        url: "./api/setup/load/",
        data: [],
        dataType: "json",
        contentType: "application/json",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).get(
        function(success) {
            loadSetup(success);
        },
        function(err) {
            $$.log("Error on Ajax Request to dockerized Web").print("orange");
            $$.log(err).error();
        }
    );
}

function loadSetup(data) {

    let data_setup = (function() {
        try {
            return JSON.parse(data);
        } catch (er) {
            return data;
        }
    })();

    if (data_setup.hasOwnProperty('CONFIGURATION_SETUP')) {
        if (data_setup.CONFIGURATION_SETUP === "true") {
            jH('#config-setup-true').attr('checked', true);
            jH("#config-display-menu").show();
        } else {
            jH('#config-setup-false').attr('checked', true);
            jH("#config-display-menu").hide();
        }
    }

    if (data_setup.hasOwnProperty('SERVICES_QUANTITY')) {
        jH('#services-qty').val(data_setup.SERVICES_QUANTITY);
    }

    if (data_setup.hasOwnProperty('NGINX_SETUP')) {
        if (data_setup.NGINX_SETUP === "true") {
            jH('#nginx-config-true').attr('checked', true);
            jH("#nginx-display-menu").show();
        } else {
            jH('#nginx-config-false').attr('checked', true);
            jH("#nginx-display-menu").hide();
        }
    }

    if (data_setup.hasOwnProperty('APACHE_SETUP')) {
        if (data_setup.APACHE_SETUP === "true") {
            jH('#apache-config-true').attr('checked', true);
            jH("#apache-display-menu").show();
        } else {
            jH('#apache-config-false').attr('checked', true);
            jH("#apache-display-menu").hide();
        }
    }

    if (data_setup.hasOwnProperty('SUPERVISOR_SETUP')) {
        if (data_setup.SUPERVISOR_SETUP === "true") {
            jH('#supervisor-config-true').attr('checked', true);
            jH("#supervisor-display-menu").show();
        } else {
            jH('#supervisor-config-false').attr('checked', true);
            jH("#supervisor-display-menu").hide();
        }
    }

    if (data_setup.hasOwnProperty('TOMCAT_SETUP')) {
        if (data_setup.TOMCAT_SETUP === "true") {
            jH('#tomcat-config-true').attr('checked', true);
            jH("#tomcat-display-menu").show();
        } else {
            jH('#tomcat-config-false').attr('checked', true);
            jH("#tomcat-display-menu").hide();
        }
    }

    if (data_setup.hasOwnProperty('DOCKER_COMPOSE_VERSION')) {
        let v = data_setup.DOCKER_COMPOSE_VERSION.replace('.', '');
        jH('#docker-compose-version-'+v).attr('checked', true);
    }

    if (data_setup.hasOwnProperty('DOCKER_COMPOSE_VERSION_OTHER')) {
        jH('#docker-compose-version-other').val(data_setup.DOCKER_COMPOSE_VERSION_OTHER);
    }

    if (data_setup.hasOwnProperty('NETWORK_DEFAULT')) {
        if (data_setup.NETWORK_DEFAULT === "on") {
            jH('#checkbox-network-gateway').attr('checked', true);
            jH("#input-text-network-gateway").props("disabled", true);
        } else {
            jH('#checkbox-network-gateway').attr('checked', false);
        }
    }

    if (data_setup.hasOwnProperty('NETWORK_GATEWAY')) {
        jH('#input-text-network-gateway').val(data_setup.NETWORK_GATEWAY);
    }

    if (data_setup.hasOwnProperty('RESOURCES_DOCKERIZED_ALL')) {
        if (data_setup.RESOURCES_DOCKERIZED_ALL === "on") {
            jH('#checkbox-resources-all').attr('checked', true);
        } else {
            jH('#checkbox-resources-all').attr('checked', false);
        }
    }

    if (data_setup.hasOwnProperty('DOCKER_EXTRA_IMAGES_NONE')) {
        if (data_setup.DOCKER_EXTRA_IMAGES_NONE === "on") {
            jH('#checkbox-extra-images-none').attr('checked', true);
            jH("#input-text-extra-images").props("disabled", true);
        } else {
            jH('#checkbox-extra-images-none').attr('checked', false);
        }
    }

    if (data_setup.hasOwnProperty('DOCKER_EXTRA_IMAGES')) {
        jH('#input-text-extra-images').val(data_setup.DOCKER_EXTRA_IMAGES);
    }

    if (data_setup.hasOwnProperty('RESOURCES_DOCKERIZED')) {
        let r = data_setup.RESOURCES_DOCKERIZED;
        for (let d = 0; d < r.length; d++) {
            jH('#checkbox-resources-'+r[d]).attr('checked', true);
        }
    }

    if (data_setup.hasOwnProperty('PHP_VERSION_ALL')) {
        if (data_setup.PHP_VERSION_ALL === "on") {
            jH('#checkbox-php-version-all').attr('checked', true);
        } else {
            jH('#checkbox-php-version-all').attr('checked', false);
        }
    }

    if (data_setup.hasOwnProperty('PHP_VERSION')) {
        let p = data_setup.PHP_VERSION;
        for (let d = 0; d < p.length; d++) {
            jH('#checkbox-php-version-'+p[d]).attr('checked', true);
        }
    }

    if (data_setup.hasOwnProperty('JAVA_VERSION_ALL')) {
        if (data_setup.JAVA_VERSION_ALL === "on") {
            jH('#checkbox-java-version-all').attr('checked', true);
        } else {
            jH('#checkbox-java-version-all').attr('checked', false);
        }
    }

    if (data_setup.hasOwnProperty('JAVA_VERSION')) {
        let p = data_setup.JAVA_VERSION;
        for (let d = 0; d < p.length; d++) {
            jH('#checkbox-java-version-'+p[d]).attr('checked', true);
        }
    }

    if (data_setup.hasOwnProperty('PYTHON_VERSION_ALL')) {
        if (data_setup.PYTHON_VERSION_ALL === "on") {
            jH('#checkbox-python-version-all').attr('checked', true);
        } else {
            jH('#checkbox-python-version-all').attr('checked', false);
        }
    }

    if (data_setup.hasOwnProperty('PYTHON_VERSION')) {
        let p = data_setup.PYTHON_VERSION;
        for (let d = 0; d < p.length; d++) {
            jH('#checkbox-python-version-'+p[d]).attr('checked', true);
        }
    }

    if (data_setup.hasOwnProperty('NODEJS_VERSION_ALL')) {
        if (data_setup.NODEJS_VERSION_ALL === "on") {
            jH('#checkbox-nodejs-version-all').attr('checked', true);
        } else {
            jH('#checkbox-nodejs-version-all').attr('checked', false);
        }
    }

    if (data_setup.hasOwnProperty('NODEJS_VERSION')) {
        let p = data_setup.NODEJS_VERSION;
        for (let d = 0; d < p.length; d++) {
            jH('#checkbox-nodejs-version-'+p[d]).attr('checked', true);
        }
    }

    if (data_setup.hasOwnProperty('GIT_PROJECT')) {
        let g = data_setup.GIT_PROJECT;
        jH('#table-git-projects').html("");
        seq = 0;
        for (let d = 0; d < g.length; d++) {
            git_project_from_setup = g[d];
            addGitProject(true);
        }
    }
}

function saveSetup() {
    $$.ajax({
        url: "./api/setup/save/",
        data: $$.form("#generic-form").serialize(),
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).post(
        function(resp) {
            try {
                if (resp.status === "ok") {
                    $$.toaster({
                        type: "success",
                        text: resp.message,
                        timeout: 3000
                    });

                    $$.await(1).run(requestSetup);

                } else {
                    $$.toaster({
                        type: "error",
                        text: resp.message,
                        timeout: 3000
                    });
                }
            } catch (er) {
                $$.log("saveSetup() => error: " + er).except();
            }
        },
        function(err) {
            $$.toaster({
                type: "error",
                text: err.message || "Internal Server Error",
                timeout: 3000
            });

            $$.log("Error on Ajax Request to dockerized Web").print("orange");
            $$.log(err).error();
        }
    );
}

function loadProjectsFromSetup() {

    let project;
    let username;
    let project_name;
    let private_git;

    if (git_project_from_setup.search(/@/) !== -1) {
        private_git = "checked";
        project = git_project_from_setup.split("@");
        username = project[0].replace(":{{{GITHUB_TOKEN}}}", "");
        project_name = project[1];
    } else {
        private_git = "";
        project = git_project_from_setup.split("/");
        username = project[1];
        project_name = project[2];
    }

    return '' +
        '<td class="td-field-name">' +
        'USERNAME/PROJECT ' +
        '<button value="' + seq + '" class="button-remove" type="button" id="button-remove-' + seq + '">\n' +
        'Remove\n' +
        '</button>' +
        '</td>' +
        '<td class="td-default">' +
        '    <input type="checkbox" name="git_project_private[]" id="git-project-private-' + seq + '" '+private_git+' value="'+seq+'" /> <span>private</span>' +
        '</td>' +
        '<td>' +
        '    <input class="input-text-common" type="text" name="git_username[]" id="git-username-' + seq + '" value="'+username+'" placeholder="GitHub Username" />' +
        '</td>' +
        '<td>' +
        '    <input class="input-text-common" type="text" name="git_project[]" id="git-project-' + seq + '" value="'+project_name+'" placeholder="GitHub Project Name" />' +
        '</td>';
}

function addGitProject(load_setup) {

    let new_line;

    if (load_setup) {
        new_line = loadProjectsFromSetup();
    } else {
        new_line = '' +
            '<td class="td-field-name">' +
            'USERNAME/PROJECT ' +
            '<button value="' + seq + '" class="button-remove" type="button" id="button-remove-' + seq + '">\n' +
            'Remove\n' +
            '</button>' +
            '</td>' +
            '<td class="td-default">' +
            '    <input type="checkbox" name="git_project_private[]" id="git-project-private-' + seq + '" value="'+seq+'" /> <span>private</span>' +
            '</td>' +
            '<td>' +
            '    <input class="input-text-common" type="text" name="git_username[]" id="git-username-' + seq + '" placeholder="GitHub Username" />' +
            '</td>' +
            '<td>' +
            '    <input class="input-text-common" type="text" name="git_project[]" id="git-project-' + seq + '" placeholder="GitHub Project Name" />' +
            '</td>';
    }

    $$.create({
        timeout: 0,
        element: "tr",
        attr_type: "id",
        attr_name: "#tr-git-project-add-"+seq,
        append: "#table-git-projects"
    });

    jH("#tr-git-project-add-"+seq).append(new_line);

    /**
     * REMOVE GITHUB PROJECT FROM LIST ON SETUP
     */
    jH('#button-remove-'+seq).on('click', function(){
        let current = $$this.value;
        $$.confirm({
            title: "Warning",
            question: "Are you sure that you want remove this project ?",
            theme: dockerized_theme,
            buttons: ["Yes", "No"]
        }, function(args){
            $$.remove('#table-git-projects', "#tr-git-project-add-"+current);
            $$.tooltip({
                text: "Removed successfully !",
                timer: 10,
                timeout: 2500,
                theme: "default"
            }).success();
        }, "myArgs");
    });

    seq += 1;
}

function createHeader() {
    $$.ajax({
        url: "./api/configuration/",
        data: "action=generate_header",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).post(
        function(success) {
            $$.log("createHeader()").print("yellow");
            $$.log(success).print();
            $progressBar.next();
        },
        function(error) {
            $$.log("createHeader() Error").print("orange");
            $$.log(error).error()
        }
    );
}

function createServices() {
    $$.ajax({
        url: "./api/configuration/",
        data: "action=generate_services",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).post(
        function(success) {
            $$.log("createServices()").print("yellow");
            $$.log(success).print();
            $progressBar.next();
        },
        function(error) {
            $$.log("createServices() Error").print("orange");
            $$.log(error).error()
        }
    );
}

function createExtraImages() {
    $$.ajax({
        url: "./api/configuration/",
        data: "action=generate_extra_services",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).post(
        function(success) {
            $$.log("createExtraImages()").print("yellow");
            $$.log(success).print();
            $progressBar.next();
        },
        function(error) {
            $$.log("createExtraImages() Error").print("orange");
            $$.log(error).error()
        }
    );
}

function createFooter() {
    $$.ajax({
        url: "./api/configuration/",
        data: "action=generate_footer",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).post(
        function(success) {
            $$.log("createFooter()").print("yellow");
            $$.log(success).print();
            $progressBar.last();
        },
        function(error) {
            $$.log("createFooter() Error").print("orange");
            $$.log(error).error()
        }
    );
}

function finishProgress() {
    $$.log("finishProgress").print("cyan");
    $$.await(3).run($$.redirect, "?content=configurations");
}

function generateConfiguration() {
    $$.progressBar({
        append_on: "#div-generate-configurations",
        controlled: true,
        theme: "default",
        progress: 0,
        total: 100,
        time: false,
        initializing: true,
        show_info: true,
        info: [
            "<nobr />Creating header file...",
            "<nobr />Creating services file...",
            "<nobr />Creating extra-images files to resources...",
            "<nobr />Creating footer file...",
            "<nobr />Initializing configurations..."
        ],
        callback: [
            createHeader,
            createServices,
            createExtraImages,
            createFooter,
            finishProgress
        ]
    }).run();
}

function deleteConfiguration() {
    $$.ajax({
        url: "./api/configuration/",
        data: "action=delete_configuration",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).post(
        function(success) {
            $$.toaster({
                type: "success",
                text: success.response,
                timeout: 3000
            });

            $$.await(3).run($$.redirect, "?content=configurations");
        },
        function(error) {
            $$.toaster({
                type: "error",
                text: error.response,
                timeout: 3000
            });
        }
    );
}

function requestLanguageVersion(language, service) {

    function disableButtons() {
        jH("#bt-add-php-"+service).props('disabled', true);
        jH("#bt-add-php-"+service).removeClass('bt-add-app-setting-enabled');
        jH("#bt-add-php-"+service).addClass('bt-add-app-setting-disabled');

        jH("#bt-add-java-"+service).props('disabled', true);
        jH("#bt-add-java-"+service).removeClass('bt-add-app-setting-enabled');
        jH("#bt-add-java-"+service).addClass('bt-add-app-setting-disabled');

        jH("#bt-add-python-"+service).props('disabled', true);
        jH("#bt-add-python-"+service).removeClass('bt-add-app-setting-enabled');
        jH("#bt-add-python-"+service).addClass('bt-add-app-setting-disabled');

        jH("#bt-add-nodejs-"+service).props('disabled', true);
        jH("#bt-add-nodejs-"+service).removeClass('bt-add-app-setting-enabled');
        jH("#bt-add-nodejs-"+service).addClass('bt-add-app-setting-disabled');

        jH("#bt-add-csharp-"+service).props('disabled', true);
        jH("#bt-add-csharp-"+service).removeClass('bt-add-app-setting-enabled');
        jH("#bt-add-csharp-"+service).addClass('bt-add-app-setting-disabled');
    }

    /*Fix Bug: Empty Select*/
    if (!language) {
        disableButtons();
        return;
    }

    $$.ajax({
        url: "./api/configuration/",
        data: "action=get_language_version&lang="+language,
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).get(
        function(response) {
            jH("#language_version-"+service).html("");

            response.forEach(function(item, index, array) {
                jH("#language_version-"+service)
                    .append("<option value='"+item+"'>"+item+"</option>");
            });

            disableButtons();

            let id = "#bt-add-"+language.toString().toLowerCase()+"-"+service;
            jH(id).props('disabled', false);
            jH(id).removeClass('bt-add-app-setting-disabled');
            jH(id).addClass('bt-add-app-setting-enabled');

        },
        function(error) {
            $$.toaster({
                type: "error",
                text: error.response,
                timeout: 3000
            });
        }
    );
}

function requestServerVersion(server, service) {
    $$.ajax({
        url: "./api/configuration/",
        data: "action=get_server_version&server="+server,
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        stringify: false,
        cors: true,
        async: true,
        restful: false,
        authorization: "",
        credentials: false
    }).get(
        function(response) {
            jH("#server_version-"+service).html("");

            response.forEach(function(item, index, array) {
                jH("#server_version-"+service)
                    .append("<option value='"+item+"'>"+item+"</option>");
            });
        },
        function(error) {
            $$.toaster({
                type: "error",
                text: error.response,
                timeout: 3000
            });
        }
    );
}

function resetApplicationSettings(service) {
    jH("#tb-app-settings-php-"+service).html("");
    jH("#tb-app-settings-java-"+service).html("");
    jH("#tb-app-settings-python-"+service).html("");
    jH("#tb-app-settings-nodejs-"+service).html("");
    jH("#tb-app-settings-csharp-"+service).html("");
}

function addApplicationSettingsLine(id, service) {
    let lang = id.split("-")[2];
    let settings_line = "";
    let data_bt_rm = "data-bt-remove-app-settings-"+lang;

    let bt_remove = "<input "+data_bt_rm+" type='button' value='REMOVE' class='generic-bt-remove' />";
    let input_name = "<input type='text' class='generic-input-text' placeholder='Type a value' />";
    let input_value = "<input type='text' class='generic-input-text' placeholder='Type a value' />";

    settings_line += "<tr data-added-line-app-settings-"+lang+">";
    settings_line += "<td class='td-field-name box-cel'>NAME</td><td>"+input_name+"</td>";
    settings_line += "<td class='td-field-name box-cel'>VALUE</td><td>"+input_value+"</td>";
    settings_line += "<td class='td-empty'>"+bt_remove+"</td>";
    settings_line += "</tr>";

    jH("#tb-app-settings-"+lang+"-"+service).append(settings_line);

    jH('[data-bt-remove-app-settings-php]').on('click', function() {
        console.log($$this.offsetParent.parentElement.offsetParent);
        console.log($$this.offsetParent.parentElement);
    });
}

function checkOthersAppSettings(service) {
    if (jH("#bt-add-php-"+service).isOn({type: "disabled", value: false}, 0)) {
        return true;
    }
    if (jH("#bt-add-java-"+service).isOn({type: "disabled", value: false}, 0)) {
        return true;
    }
    if (jH("#bt-add-python-"+service).isOn({type: "disabled", value: false}, 0)) {
        return true;
    }
    if (jH("#bt-add-nodejs-"+service).isOn({type: "disabled", value: false}, 0)) {
        return true;
    }
    if (jH("#bt-add-csharp-"+service).isOn({type: "disabled", value: false}, 0)) {
        return true;
    }
}

$$.loaded(function() {

    /**
     * BUTTON ADD GIT PROJECT
     */
    if ($$.findId("button-add-git-project")) {
        jH('#button-add-git-project').on('click', function () {
            addGitProject(false);
        });
    }

    /**
     * DOCKER NETWORK GATEWAY
     */
    if ($$.findId('checkbox-network-gateway')) {
        jH('#checkbox-network-gateway').check('click', function() {
            jH('#input-text-network-gateway').toggle('dockerized_web_31800');
            jH('#input-text-network-gateway').props("disabled", false);''
        });
    }

    if ($$.findId('input-text-network-gateway')) {
        jH('#input-text-network-gateway').on('keyup', function() {
            if (jH('#input-text-network-gateway').val() !== "dockerized_web_31800") {
                jH('#checkbox-network-gateway').attr('checked', false);
            } else {
                jH('#checkbox-network-gateway').attr('checked', true);
            }
        });
    }

    /**
     * DOCKER RESOURCES DOCKERIZED
     */
    if ($$.findId('checkbox-resources-all')) {
        jH('#checkbox-resources-all').check('click', function() {
            let flag = jH('#checkbox-resources-all').isOn({type: "checked", value: true}, 0);
            jH('.checkbox-resources-default').attr('checked', flag);
        });
    }

    /**
     * DOCKER EXTRA IMAGES
     */
    if ($$.findId('checkbox-extra-images-none')) {
        jH('#checkbox-extra-images-none').check('click', function() {
            let flag = jH('#checkbox-extra-images-none').isOn({type: "checked", value: true}, 0);
            jH('#input-text-extra-images').attr('disabled', flag);
        });
    }

    /**
     * PHP VERSION
     */
    if ($$.findId('checkbox-php-version-all')) {
        jH('#checkbox-php-version-all').check('click', function() {
            let flag = jH('#checkbox-php-version-all').isOn({type: "checked", value: true}, 0);
            jH('.checkbox-php-version').attr('checked', flag);
        });
    }

    /**
     * JAVA VERSION
     */
    if ($$.findId('checkbox-java-version-all')) {
        jH('#checkbox-java-version-all').check('click', function() {
            let flag = jH('#checkbox-java-version-all').isOn({type: "checked", value: true}, 0);
            jH('.checkbox-java-version').attr('checked', flag);
        });
    }

    /**
     * PYTHON VERSION
     */
    if ($$.findId('checkbox-python-version-all')) {
        jH('#checkbox-python-version-all').check('click', function() {
            let flag = jH('#checkbox-python-version-all').isOn({type: "checked", value: true}, 0);
            jH('.checkbox-python-version').attr('checked', flag);
        });
    }

    /**
     * NODEJS VERSION
     */
    if ($$.findId('checkbox-nodejs-version-all')) {
        jH('#checkbox-nodejs-version-all').check('click', function() {
            let flag = jH('#checkbox-nodejs-version-all').isOn({type: "checked", value: true}, 0);
            jH('.checkbox-nodejs-version').attr('checked', flag);
        });
    }

    /**
     * BUTTON SUBMIT FORM
     */
    if ($$.findId('button-submit')) {
        jH('#button-submit').on('click', function() {
            if (checkSubmit()) {
                $$.confirm({
                    title: "Warning",
                    question: "Are you sure that you want to save the data ?",
                    theme: dockerized_theme,
                    buttons: ["Yes", "No"]
                }, function(args){
                    saveSetup();
                }, "myArgs");
            }
        });
    }

    /**
     * BUTTON WHEN CONFIG IS AVAILABLE
     */
    if ($$.findId('button-config')) {
        jH('#button-config').on('click', function() {
            $$.redirect("/?content=configurations");
        });
    }

    /**
     * BUTTON CANCEL FORM
     */
    if ($$.findId('button-reset')) {
        jH('#button-reset').on('click', function() {

            $$.confirm({
                title: "Warning",
                question: "Are you sure that you want to cancel and reset all data ?",
                theme: dockerized_theme,
                buttons: ["Yes", "No"]
            }, function(args){
                jH('#generic-form').reset();
                jH('#table-git-projects').html("");
                seq = 0;
            }, "myArgs");

            /*if (confirm('Are you sure that you want to cancel and reset all data ?')) {
                jH('#generic-form').reset();
                jH('#table-git-projects').html("");
                seq = 0;
            }*/
        });
    }

    /**
     * CLOSE MESSAGE (SUCCESS/ERROR)
     */
    if ($$.findId('a-close-message-success')) {
        jH('#a-close-message-success').on('click', function() {
            jH('#div-message-process-success').hide();
        });
    }

    if ($$.findId('a-close-message-error')) {
        jH('#a-close-message-error').on('click', function() {
            jH('#div-message-process-error').hide();
        });
    }

    /**
     * BUTTON LOAD DEFAULT SETUP
     */
    if ($$.findId('button-load-setup')) {
        jH("#button-load-setup").on('click', function() {
            requestSetup();
        });
    }

    /**
     * COOKIE SAVE SETUP HANDLER
     */
    if ($$.cookie("save_setup").get() === "ok") {
        jH('#div-message-process-success').show();
        $$.cookie("save_setup").remove();
    }

    if ($$.cookie("save_setup").get() === "error") {
        jH('#div-message-process-error').show();
        $$.cookie("save_setup").remove();
    }

    /**
     * BUTTON GENERATE CONFIGURATION
     */
    if ($$.findId('button-generate-configurations')) {
        jH('#button-generate-configurations').on('click', function() {
            $$.confirm({
                title: "Warning",
                question: "Are you sure that want generate configuration now ?",
                theme: dockerized_theme,
                buttons: ["Yes", "No"]
            }, function(args){
                jH('#button-generate-configurations').attr('disabled', true);
                generateConfiguration();
            }, "myArgs");
        });
    }

    /**
     * BUTTON VIEW CONFIGURATION
     */
    if ($$.findId('button-view-configurations')) {
        jH('#button-view-configurations').on('click', function() {
            $$.redirect("?content=configurations&action=view");
        });
    }

    /**
     * BUTTON DELETE CONFIGURATION
     */
    if ($$.findId('button-delete-configurations')) {
        jH('#button-delete-configurations').on('click', function() {
            $$.confirm({
                title: "Warning",
                question: "Are you sure that want delete the current configuration ?",
                theme: dockerized_theme,
                buttons: ["Yes", "No"]
            }, function(args){
                jH('#button-delete-configurations').attr('disabled', true);
                deleteConfiguration();
            }, "myArgs");
        });
    }

    /**
     * BUTTON SETUP YOUR SYSTEM
     */
    if ($$.findId('button-welcome')) {
        jH('#button-welcome').on('click', function() {
            $$.redirect("/?content=setup");
        });
    }

    /**
     * BUTTON ICON JSHUNTER HAMBURGER - MENU HEADER
     */
    if ($$.findId("div-icon-hamburger")) {
        jH("#div-icon-hamburger").html(
            $$.icons({
                icon: "close",
                id: "icon-menu-header-close",
                size: "s-32",
                data: "menu-header",
                color: "#FFFFFF"
            }).draw()
        );
        activeClose();
    }

    /**
     * AUTOMATIC LOAD SETUP
     */
    if (window.location.href.search('load_setup') !== -1) {
        console.log("REQUEST");
        requestSetup();
    }

    /**
     * RESUME CONFIGURATION TOGGLE
     */
    if ($$.findId(("resume-config-toggle"))) {
        resume_height = jH("#div-resume-config").height()[0];
        jH("#div-resume-config").height(resume_height);
        jH("#resume-config-toggle").on('click', function () {
            if (jH("#div-resume-config").height()[0] === "35px") {
                jH("#div-resume-config").height(resume_height);
            } else {
                jH("#div-resume-config").height("35px");
            }
        });
    }

    /**
     * SERVICES CONFIGURATION TOGGLE
     */
    if ($$.findId(("div-services-config"))) {
        jH("[data-service-toggle]").on('click', function() {
            let target = "#div-service-config-" + $$this.dataset.content;
            if (jH(target).height()[0] === "35px") {
                jH(target).height("auto");
            } else {
                jH(target).height("35px");
            }
        });
    }

    /**
     * SELECT CONTROLS: LANGUAGES X SERVERS
     */
    if ($$.findId(("div-services-config"))) {
        jH('[data-select-language]').on('change', function () {
            let lang = $$this.value;
            let service = $$this.dataset.content;
            if (checkOthersAppSettings(service) && $$this.value) {
                $$.confirm({
                    title: "Warning",
                    question: "Are you sure you want to change the language ?",
                    theme: dockerized_theme,
                    buttons: ["Yes", "No"]
                }, function(args){
                    resetApplicationSettings(service);
                    requestLanguageVersion(lang, service);
                }, "myArgs");
            } else {
                requestLanguageVersion(lang, service);
            }
        });

        jH('[data-select-server]').on('change', function() {
            let server = $$this.value;
            let service = $$this.dataset.content;

            requestServerVersion(server, service);
        });

        jH('[data-button-app]').on('click', function() {
            addApplicationSettingsLine($$this.id, $$this.dataset.content);
        });
    }

});
