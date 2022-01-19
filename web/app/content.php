<?php

$content = $_GET['content'] ?? 'welcome';
$include_content = "src/{$content}.php";
$post_content = "save_".$content;

?>

<div id="div-content">

    <form id="generic-form" method="POST" action="?content=<?=$content?>" enctype="application/x-www-form-urlencoded">

        <input type="hidden" name="post" id="post" value="save_<?=$content?>" />
        <input type="hidden" name="form_type" id="form-type" value="<?=$content?>" />

        <?php
        include($include_content);
        ?>

    </form>

</div>
