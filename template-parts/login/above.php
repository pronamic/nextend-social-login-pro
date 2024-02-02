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

        var innerContainer = container.querySelector('.nsl-container');
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-login-layout-above');
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

    #nsl-custom-login-form-main .nsl-container-login-layout-above {
        padding: 0 0 20px;
    }
</style>