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

        var floatingForm = document.createElement('form');
        floatingForm.classList.add('nsl-floating-form');
        form.parentElement.insertBefore(floatingForm, form.nextElementSibling);

        var innerContainer = container.querySelector('.nsl-container');
        if (innerContainer) {
            innerContainer.classList.add('nsl-container-login-layout-below-floating');
            innerContainer.style.display = 'block';
        }

        floatingForm.insertBefore(container, floatingForm.firstChild);
    });
</script>
<style type="text/css">
    #nsl-custom-login-form-main .nsl-container {
        display: none;
    }

    form.nsl-floating-form {
        padding: 26px 24px;
    }
</style>