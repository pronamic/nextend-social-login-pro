<script type="text/javascript">
    window._nslDOMReady(function () {
        var container = document.getElementById('<?php echo $containerID; ?>'),
            form = document.getElementById('customer_details');

        if (!form) {
            form = container.closest('form');
            if (!form) {
                form = container.parentNode;
            }
        }

        if (container && form) {
            var clear = document.createElement('div');
            clear.classList.add('nsl-clear');
            form.appendChild(clear);
        }

        var innerContainer = container.querySelector(".nsl-container");
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-woocommerce-billing-layout-below');
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

    {{containerID}} .nsl-container-woocommerce-billing-layout-below {
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