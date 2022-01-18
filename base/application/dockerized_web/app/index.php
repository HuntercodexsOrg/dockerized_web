<?php

/*autoload*/
$class = glob("./src/class/*.php", GLOB_BRACE);

foreach ($class as $inc) {
    require_once $inc;
}

include "./document.php";
include "./header.php";
include "./menu.php";
include "./content.php";
include "./footer.php";
