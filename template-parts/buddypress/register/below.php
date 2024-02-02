<script type="text/javascript">
    window._nslDOMReady(function () {
        var container = document.getElementById('<?php echo $containerID; ?>'),
            form = document.querySelector('#signup-form');

        if (!form) {
            form = container.closest('form');
            if (!form) {
                form = container.parentNode;
            }
        }

        var innerContainer = container.querySelector(".nsl-container");
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-buddypress-register-layout-below');
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

    {{containerID}} .nsl-container-buddypress-register-layout-below {
        clear: both;
        padding: 20px 0 0;
    }';
?>