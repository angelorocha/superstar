jQuery(function ($) {
    $('#user_selected').select2();
});

jQuery(function ($) {
    let nav_menu = $('.wpss-admin-nav > li.active');
    nav_menu.each(function () {
        let item_id = $(this).attr('id');
        $('.wpss-admin-' + item_id).addClass('wpss-tab-active');
    });
});

jQuery(function ($) {
    $('#wpss-show-op').on('click', function () {
        $('#special_perms').toggleClass('wpss_show');
        $('span.wpss-show-btn').toggle();
    });
});