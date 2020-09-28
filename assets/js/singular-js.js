/** Native WordPress Light Gallery */
jQuery(function ($) {
    $('.wpss-article-text .gallery').each(function () {
        let gallery_id = $('#' + $(this).attr('id'));
        let get_figure = gallery_id.find('figure');

        let gallery_class = gallery_id.attr('class');

        let get_width;
        let col_width;
        for (let i = 1; i <= 12; i++) {
            if (gallery_class.search('gallery-columns-' + i) > 0) {
                get_width = gallery_class.replace('gallery-columns-' + i, 'col-md-' + i);
                col_width = get_width.split(' ')[2];
            }
        }
        let col_md = 'col-md-' + (12 / parseInt(col_width.split('-')[2]));

        gallery_id.addClass('row');
        get_figure.each(function () {
            $(this).addClass(col_md);
            $(this).attr('data-src', $(this).find('a').attr('href'));
            $(this).find('div').attr('data-src', $(this).find('a').attr('href'));
        });
        gallery_id.lightGallery({
            thumbnail: true,
            animateThumb: true,
            showThumbByDefault: true,
            cssEasing: 'cubic-bezier(0.680, -0.550, 0.265, 1.550)'
        });
    });
});