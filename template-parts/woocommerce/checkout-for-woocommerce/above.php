<script type="text/javascript">
    window._nslDOMReady(function () {
        var container = document.getElementById('<?php echo $containerID; ?>'),
            form = document.getElementById('cfw-login-details');

        if (!form) {
            form = container.closest('form');
            if (!form) {
                form = container.parentNode;
            }
        }

        var innerContainer = container.querySelector(".nsl-container");
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-woocommerce-cfw-layout-above');
            innerContainer.style.display = 'block';
        }

        form.insertBefore(container, form.firstChild);
    });
</script>
<?php
$style = '   
    {{containerID}} .nsl-container {
        display: none;
        margin-top: 20px;
    }

    {{containerID}} {
        padding-bottom: 20px;
    }
    
    {{containerID}} .nsl-container-woocommerce-cfw-layout-below {
        clear: both;
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