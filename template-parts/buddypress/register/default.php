<?php
$style = '
    {{containerID}} {
        margin: 20px 0;
        clear: both;
    }';
?>
<style type="text/css">
    <?php echo str_replace('{{containerID}}','#' . $containerID, $style); ?>
</style>
