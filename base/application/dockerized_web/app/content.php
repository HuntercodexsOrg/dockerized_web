<?php

$content = $_GET['content'] ?? 'welcome';
$include_content = "src/{$content}.php";
$post_content = "save_".$content;

?>

<div id="div-content">

    <?php
    /*Request Save Content*/
    if (isset($_POST['post']) && $_POST['post'] == $post_content) {

        $save = match ($post_content) {
            'save_setup' => Dockerized\SaveContent::saveSetup($_POST),
            default => false,
        };

        if ($post_content === "save_setup") {
            if ($save) {

                echo "<script>document.cookie = 'save_setup=ok';</script>";

            } else {

                echo "<script>document.cookie = 'save_setup=error';</script>";

            }
            echo "<script>window.location.href = '?content=setup&load_setup=1';</script>";
        }
    }
    ?>

    <div id="div-message-process-success">
        Data save successful
        <a class="a-close" id="a-close-message-success">X</a>
    </div>

    <div id="div-message-process-error">
        Error on trying save data !
        <a class="a-close" id="a-close-message-error">X</a>
    </div>

    <form id="generic-form" method="POST" action="?content=<?=$content?>" enctype="application/x-www-form-urlencoded">

        <input type="hidden" name="post" id="post" value="save_<?=$content?>" />
        <input type="hidden" name="form-type" id="form-type" value="<?=$content?>" />

        <?php
        include($include_content);
        ?>

    </form>

</div>
