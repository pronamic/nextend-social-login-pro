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

        var jetpackSSO = document.getElementById('jetpack-sso-wrap');
        if (jetpackSSO) {
            form = jetpackSSO;
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


        var innerContainer = container.querySelector('.nsl-container');
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-login-layout-below-separator');
            innerContainer.style.display = 'block';
        }
        form.insertBefore(container, null);
    });
</script>
<style type="text/css">
    #nsl-custom-login-form-main .nsl-container {
        display: none;
    }

    .nsl-clear {
        clear: both;
    }

    #nsl-custom-login-form-main .nsl-separator {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        color: #777;
        margin: 20px -8px 20px;
    }

    #nsl-custom-login-form-main .nsl-separator::before,
    #nsl-custom-login-form-main .nsl-separator::after {
        content: "";
        flex-grow: 1;
        background: #E5E5E5;
        height: 1px;
        font-size: 0;
        line-height: 0;
        margin: 0 8px;
    }

    #nsl-custom-login-form-main .nsl-container-login-layout-below-separator {
        clear: both;
    }

    .login form {
        padding-bottom: 20px;
    }
</style>