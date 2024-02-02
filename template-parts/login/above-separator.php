<script type="text/javascript">
    window._nslDOMReady(function () {
        var container = document.getElementById('nsl-custom-login-form-main'),
            form = document.querySelector('#loginform,#registerform,#front-login-form,#setupform');

        if (!form) {
            form = container.closest('form');
            if (!form) {
                form = container.parentNode;
            }
        }

        if (container && form) {
            var clear = document.createElement('div');
            clear.classList.add('nsl-clear');
            form.insertBefore(clear, form.firstChild);

            var separatorToRemove = container.querySelector('.nsl-separator');
            if (separatorToRemove) {
                separatorToRemove.remove();
            }

            var separator = document.createElement('div');
            separator.classList.add('nsl-separator');
            separator.innerHTML = '<?php _e('OR', 'nextend-facebook-connect'); ?>';
            container.appendChild(separator);
        }

        var innerContainer = container.querySelector(".nsl-container");
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-login-layout-above-separator');
            innerContainer.style.display = 'block';
        }

        form.insertBefore(container, form.firstChild);

        var jetpackSSO = document.getElementById('jetpack-sso-wrap__action');
        if (jetpackSSO) {
            var clonedContainer = container.cloneNode(true);
            clonedContainer.id = 'nsl-custom-login-form-jetpack-sso';
            jetpackSSO.insertBefore(clonedContainer, jetpackSSO.firstChild);
        }
    });
</script>
<style type="text/css">
    #nsl-custom-login-form-main .nsl-container {
        display: none;
    }

    .nsl-clear {
        clear: both;
    }

    #nsl-custom-login-form-main .nsl-separator,
    #nsl-custom-login-form-jetpack-sso .nsl-separator {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        color: #777;
        margin: 20px -8px 20px;
    }

    #nsl-custom-login-form-main .nsl-separator::before,
    #nsl-custom-login-form-main .nsl-separator::after,
    #nsl-custom-login-form-jetpack-sso .nsl-separator::before,
    #nsl-custom-login-form-jetpack-sso .nsl-separator::after {
        content: "";
        flex-grow: 1;
        background: #dddddd;
        height: 1px;
        font-size: 0;
        line-height: 0;
        margin: 0 8px;
    }

    #nsl-custom-login-form-main .nsl-container-login-layout-above-separator {
        clear: both;
    }
</style>