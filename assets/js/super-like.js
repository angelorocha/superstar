jQuery(function ($) {
    $('.wpss-post-like').on('click', function (event) {
        event.preventDefault();
        heart = jQuery(this);
        post_id = heart.data("post_id");
        heart.html("<i class='fa fa-thumbs-o-up'></i>&nbsp;<i class='fa fa-cog fa-spin'></i>");
        $.ajax({
            type: "post",
            url: ajax_var.url,
            data: "action=wpss-post-like&nonce=" + ajax_var.nonce + "&wpss_post_like=&post_id=" + post_id,
            success: function (count) {
                if (count.indexOf("already") !== -1) {
                    let lecount = count.replace("already", "");
                    if (lecount == 0) {
                        let lecount = "Curtir!";
                    }
                    heart.prop('title', 'Curtir!');
                    heart.removeClass("liked");
                    heart.html("<i class='fa fa-thumbs-o-up'></i>&nbsp;" + lecount);
                } else {
                    heart.prop('title', 'Deixar de Curtir');
                    heart.addClass("liked");
                    heart.html("<i class='fa fa-thumbs-up'></i>&nbsp;" + count);
                }
            }
        });
    });
});

jQuery(function ($) {
    $('.wpss-post-like-content').on('click', function (event) {
        event.preventDefault();
        heart = jQuery(this);
        post_id = heart.data("post_id");
        heart.html("<i class='fa fa-cog fa-spin'></i>");
        $.ajax({
            type: "post",
            url: ajax_var.url,
            data: "action=wpss-post-like&nonce=" + ajax_var.nonce + "&wpss_post_like=&post_id=" + post_id,
            success: function (count) {
                if (count.indexOf("already") !== -1) {
                    var lecount = count.replace("already", "");
                    heart.removeClass("liked");
                    heart.prop('title', 'Curtir!');
                    heart.html(lecount + " Curtir!");
                } else {
                    heart.prop('title', 'Deixar de Curtir');
                    heart.addClass("liked");
                    heart.html(count);
                }
            }
        });
    });
});

jQuery(function ($) {
    $('.wpss-like-already, .wpss-post-like').tooltip();
});