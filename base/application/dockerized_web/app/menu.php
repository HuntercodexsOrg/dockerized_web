<?php
$content        = $_GET['content'] ?? "";
$setup          = $_GET['content'] == 'setup' ? 'active': 'no-active' ;
$configurations = $_GET['content'] == 'configurations' ? 'active': 'no-active' ;
$nginx          = $_GET['content'] == 'nginx' ? 'active': 'no-active' ;
$apache         = $_GET['content'] == 'apache' ? 'active': 'no-active' ;
$tomcat         = $_GET['content'] == 'tomcat' ? 'active': 'no-active' ;
$supervisor     = $_GET['content'] == 'supervisor' ? 'active': 'no-active' ;
$documentation  = $_GET['content'] == 'documentation' ? 'active': 'no-active' ;

$configuration_display = Dockerized\Reader::readerSetup('CONFIGURATION_SETUP');
$nginx_display         = Dockerized\Reader::readerSetup('NGINX_SETUP');
$apache_display        = Dockerized\Reader::readerSetup('APACHE_SETUP');
$tomcat_display        = Dockerized\Reader::readerSetup('TOMCAT_SETUP');
$supervisor_display    = Dockerized\Reader::readerSetup('SUPERVISOR_SETUP');
?>

<div id="div-menu-top">
    <!--MENU TOP-->
    <ul id="ul-menu-top">
        <li>
            <a class="<?=$setup;?>" id="a-setup" href="?content=setup">
                Setup
            </a>
        </li>
        <li id="config-display-menu" <?=$configuration_display;?>>
            <a class="<?=$configurations;?>" id="a-configurations" href="?content=configurations">
                Config
            </a>
        </li>
        <li id="nginx-display-menu" <?=$nginx_display;?>>
            <a class="<?=$nginx;?>" id="a-nginx" href="?content=nginx">
                Nginx
            </a>
        </li>
        <li id="apache-display-menu" <?=$apache_display;?>>
            <a class="<?=$apache;?>" id="a-apache" href="?content=apache">
                Apache
            </a>
        </li>
        <li id="supervisor-display-menu" <?=$supervisor_display;?>>
            <a class="<?=$supervisor;?>" id="a-supervisor" href="?content=supervisor">
                Supervisor
            </a>
        </li>
        <li id="tomcat-display-menu" <?=$tomcat_display;?>>
            <a class="<?=$tomcat;?>" id="a-tomcat" href="?content=tomcat">
                Tomcat
            </a>
        </li>
        <li>
            <a class="<?=$documentation;?>" id="a-documentation" href="?content=documentation">
                About
            </a>
        </li>
    </ul>
</div>

<div id="div-menu-itens">

    <?php
    $content = $_GET['content'] ?? "welcome";
    $include = "src/{$content}_menu.php";
    include($include);
    ?>

</div>
