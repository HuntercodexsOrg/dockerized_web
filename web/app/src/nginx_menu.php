
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
