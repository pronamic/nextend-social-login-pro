<script type="text/javascript">
    window._nslDOMReady(function () {
        const container = document.getElementById('<?php echo $containerID; ?>'),
            form = container.closest('form');

        const innerContainer = container.querySelector(".nsl-container");
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-edd-checkout-layout-below');
            innerContainer.style.display = 'block';
        }

        form.appendChild(container);
    });
</script>
<?php
$style = '
    {{containerID}} {
        margin-top: 20px;
    }
    
    {{containerID}} .nsl-container {
        display: none;
    }

    {{containerID}} .nsl-container-edd-checkout-layout-below{
        clear: both;
        padding: 20px 0 0;
    }';
?>
<style type="text/css">
    <?php echo str_replace('{{containerID}}','#' . $containerID, $style); ?>
</style>
<?php
$style = '
    {{containerID}} .nsl-container {
        display: block;
    }';
?>