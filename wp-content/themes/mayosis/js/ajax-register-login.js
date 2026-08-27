class MayosisLogAuth {
    constructor() {
        this.initAuthForms();
    }

    initAuthForms() {
        jQuery(document).ready(($) => {
            $('form#mayosis-login, form#mayosis-register').on('submit', (e) => this.handleSubmit(e, $));
        });
    }

    handleSubmit(e, $) {
        e.preventDefault();
        const form = $(e.currentTarget);
        const formId = form.attr('id');
        const statusElement = `${formId} p.status`;
        const msgStatusElement = `${formId} p.mayosis-msg-status`;

        $(statusElement, form).show().text(ajax_auth_object.loadingmessage);

        let action, username, password, password2, email, security;
        if (formId === 'mayosis-register') {
            action = 'mayosis_ajaxregister';
            username = $('#reg-username').val();
            password = $('#reg-password').val();
            password2 = $('#reg-password2').val();
            email = $('#reg-email').val();
            security = $('#signonsecurity').val();
        } else {
            action = 'mayosis_ajaxlogin';
            username = $('#username').val();
            password = $('#password').val();
            security = $('#security').val();
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_auth_object.ajaxurl,
            data: {
                action,
                username,
                password,
                password2,
                email,
                security
            },
            success: (data) => {
                $(msgStatusElement).text(data.message);
                if (data.loggedin) {
                    $('.mayosis-msg-status').removeClass('loginerror').addClass('loginsucess');
                    document.location.href = formId === 'mayosis-register' ? ajax_auth_object.register_redirect : ajax_auth_object.redirecturl;
                } else {
                    $('.mayosis-msg-status').addClass('loginerror');
                }
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new MayosisLogAuth();
});
