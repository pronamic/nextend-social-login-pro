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
            form.insertBefore(clear, null);

            var separatorToRemove = container.querySelector(".nsl-separator");
            if (separatorToRemove) {
                separatorToRemove.remove();
            }

            var separator = document.createElement('div');
            separator.classList.add('nsl-separator');
            separator.innerHTML = '<?php _e('OR', 'nextend-facebook-connect'); ?>';
            container.insertBefore(separator, container.firstChild);
        }

        var innerContainer = container.querySelector(".nsl-container");
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-woocommerce-billing-layout-below-separator');
            innerContainer.style.display = 'block';
        }

        form.appendChild(container);
    });
</script>
<?php
$style = '
    .nsl-clear {
        clear: both;
    }
    
    {{containerID}} .nsl-container {
        display: none;
    }

    {{containerID}} .nsl-separator {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        color: #72777c;
        margin: 20px 0 20px;
        font-weight: bold;
    }

    {{containerID}} .nsl-separator::before,
    {{containerID}} .nsl-separator::after {
        content: "";
        flex-grow: 1;
        background: #dddddd;
        height: 1px;
        font-size: 0;
        line-height: 0;
        margin: 0 8px;
    }

    {{containerID}} .nsl-container-woocommerce-billing-layout-below-separator {
        clear: both;
    }

    .login form {
        padding-bottom: 20px;
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