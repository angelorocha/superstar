jQuery(function ($) {

    let login_action = $('.wpss-login-action');
    let modal_container = $('.wpss-modal-login-container');
    let document_body = $('body');

    login_action.on('click', function () {
        modal_container.toggleClass('wpss-modal-login-container-show');
        document_body.toggleClass('wpss-body-collapse');
    });
    $('.wpss-login-close').on('click', function () {
        modal_container.removeClass('wpss-modal-login-container-show');
        document_body.removeClass('wpss-body-collapse');
    });
});