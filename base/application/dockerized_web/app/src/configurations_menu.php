
<!--MENU ITENS: CONFIGURATIONS-->

<ul id="ul-menu-itens--configurations">

    <?php

    $conf_dir1 = glob("/data/dockerized_web/setup/*.conf", GLOB_BRACE);
    $conf_dir2 = glob("/data/dockerized_web/setup/services/*.conf", GLOB_BRACE);

    $configs = array_merge($conf_dir1, $conf_dir2);

    foreach ($configs as $config) {
        $file_path = $config;
        $config_name = basename($config);
        echo "
                <li>
                    <a>
                        {$config_name}
                    </a>
                </li>";
    }

    if (count($configs) == 0) {
        echo "
                <li>
                    <a>
                        GENERATE CONFIGURATION
                    </a>
                </li>";
    }

    ?>

</ul>
