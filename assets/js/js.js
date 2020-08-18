/*** Bootstrap dropdown slide */
/*jQuery(function ($) {
    let dropdown = $('.dropdown');
    dropdown.on('show.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    });

    dropdown.on('hide.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
    });
});*/

/*** Scroll Top */
jQuery(window).bind('scroll', function () {
    if (jQuery(this).scrollTop() > 200) {
        jQuery(".wpss-scroll-top").fadeIn(400);
    } else {
        jQuery('.wpss-scroll-top').fadeOut(400);
    }
});

jQuery(function ($) {
    $('.wpss-top-anchor').on('click', function () {
        $('html, body').animate({
            scrollTop: $("#wpss-top").offset().top
        }, 1000)
    });
});

/*** Light Gallery */
jQuery(function ($) {
    $('.wpss-post-thumbnail').lightGallery();
});

/*** Content Functions */
jQuery(function ($) {
    let bodylinks = $('body a');
    bodylinks.each(function () {
        if (!$(this).attr('title')) {
            $(this).attr('title', $(this).text())
        }
    });
});

/*** Sidebar Functions */
jQuery(function ($) {
    let sidebarhref = $('.wpss-main-sidebar aside a');
    sidebarhref.each(function () {
        if ($(this).attr('href') === '#') {
            $(this).attr('href', 'javascript:;')
        }
    });

    sidebarhref.tooltip({
        placement: 'left'
    });

    let sidebarhaschild = $('.wpss-main-sidebar li.menu-item-has-children');
    sidebarhaschild.each(function () {
        $(this).on('click', function () {
            if ($(this).find('ul.sub-menu').hasClass('wpss-sidebar-submenu')) {
                $(this).find('ul.sub-menu').removeClass('wpss-sidebar-submenu');
            } else {
                $('ul.sub-menu').removeClass('wpss-sidebar-submenu');
                $(this).find('ul.sub-menu').addClass('wpss-sidebar-submenu');
            }
        });
    });
});

/*** Footer Function */
jQuery(function ($) {
    let footerhref = $('.wpss-footer a');
    footerhref.each(function () {
        if ($(this).attr('href') === '#') {
            $(this).attr('href', 'javascript:;')
        }
    });

    footerhref.tooltip({
        placement: 'right'
    });

    let footerhaschild = $('.wpss-footer aside .menu-item-has-children');
    $('.wpss-footer aside .menu-item-has-children > a').attr('href', 'javascript:;');

    footerhaschild.each(function () {
        $(this).on('click', function () {
            if ($(this).find('ul.sub-menu').hasClass('wpss-footer-submenu')) {
                $(this).find('ul.sub-menu').removeClass('wpss-footer-submenu');
            } else {
                $('ul.sub-menu').removeClass('wpss-footer-submenu');
                $(this).find('ul.sub-menu').addClass('wpss-footer-submenu');
            }
        });
    });

});