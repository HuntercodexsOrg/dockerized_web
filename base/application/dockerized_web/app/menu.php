<div id="div-menu-top">
    <!--MENU TOP-->
    <ul id="ul-menu-top">
        <li>
            <a>
                Setup
            </a>
        </li>
        <li>
            <a>
                Configurations
            </a>
        </li>
        <li>
            <a>
                Nginx
            </a>
        </li>
        <li>
            <a>Documentation</a>
        </li>
    </ul>
</div>

<div class="clear-fix"></div>

<div id="div-menu-itens">

    <!--MENU ITENS: CONFIGURATIONS-->
    <ul id="ul-menu-itens--configurations">

        <?php

        $conf_dir1 = glob("/data/dockerized_web/setup/*.tpl", GLOB_BRACE);
        $conf_dir2 = glob("/data/dockerized_web/setup/services/*.tpl", GLOB_BRACE);

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

        ?>

    </ul>

    <!--MENU ITENS: NGINX-->
    <ul id="ul-menu-itens--nginx">

        <?php

        $configs = glob("/data/dockerized_web/setup/nginx/*.tpl", GLOB_BRACE);

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

        ?>

    </ul>

</div>
