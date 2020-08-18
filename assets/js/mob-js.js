jQuery(function ($) {
    var close_btn = $('.wpss-mob-menu-container > ul ~ a.close');
    var open_btn = $('.wpss-mob-nav');

    close_btn.on('click', function () {
        $('.wpss-mob-menu-container').toggleClass('wpss-show-menu');
    });

    open_btn.on('click', function () {
        $('.wpss-mob-menu-container').toggleClass('wpss-show-menu');
    });
});