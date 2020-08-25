<!DOCTYPE html>
<html <?php language_attributes(); ?> class="h-100">
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <title><?= get_bloginfo('name') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="profile" href="https://gmpg.org/xfn/11"/>
    <?php wp_head();

    global $wpss_option;
    $background = $wpss_option['wpss_maintenance_background'];
    $bg_color = $background['background-color'];
    $bg_repeat = $background['background-repeat'];
    $bg_size = $background['background-size'];
    $bg_attach = $background['background-attachment'];
    $bg_position = $background['background-position'];
    $bg_image = $background['background-image'];
    $title = $wpss_option['wpss_maintenance_title'];
    $description = $wpss_option['wpss_maintenance_description'];
    $login_box = $wpss_option['wpss_maintenance_login'];
    ?>
    <style>
        body.wpss-login-page {
            background-color: <?=$bg_color?>;
            background-repeat: <?=$bg_repeat?>;
            background-size: <?=$bg_size?>;
            background-attachment: <?=$bg_attach?>;
            background-position: <?=$bg_position?>;
            background-image: url("<?=$bg_image?>");
        }
        .wpss-login-box {max-width: 660px; margin: 0 auto 0 auto;}
    </style>
</head>
<body class="wpss-login-page h-100">

<div class="h-100 d-flex flex-column justify-content-center">
    <div class="container">

        <div class="row wpss-login-box bg-white rounded">

            <?php if(!empty($title)): ?>
                <div class="col-md-12 text-center p-2">
                    <h4><?= $title; ?></h4>
                    <hr class="p-0 m-0">
                </div>
            <?php endif; ?>

            <?php if($login_box): ?>
                <div class="col-md-4 align-self-center text-center p-2">
                    <?= wpss_site_logo(); ?>
                </div>

                <div class="col-md-8">

                    <form method="post" action="<?= esc_url(site_url('wp-login.php', 'login_post')); ?>" class="h-100 p-3">
                        <label for="user"><strong><?php _e('User', 'wpss'); ?></strong>:</label>
                        <input type="text" name="log" id="user" placeholder="<?php _e('User Login', 'wpss'); ?>" class="form-control mb-2" required="required">

                        <label for="password"><strong><?php _e('Password', 'wpss'); ?></strong>:</label>
                        <input type="password" name="pwd" id="password" placeholder="<?php _e('User Password', 'wpss'); ?>" class="form-control" required="required">

                        <div class="form-group mt-2">
                            <div class="form-check">
                                <input id="rememberme" type="checkbox" value="forever" name="rememberme" class="form-check-input">
                                <label class="form-check-label" for="rememberme"><?php _e('Remember me', 'wpss'); ?></label>
                            </div>
                        </div>
                        <div class="text-center">
                            <input type="submit" name="wp-submit" value="<?php _e('Login', 'wpss'); ?>" class="btn btn-outline-dark mt-3">
                            <input type="hidden" name="redirect_to" value="<?= home_url() . '?logged_in'; ?>">
                            <input type="hidden" name="wpss_login_action">
                            <input type="hidden" name="testcookie" value="1">
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['logout'])): ?>
                <div class="col-md-12">
                    <div class="alert alert-info p-3 mt-3">
                        <p class="text-center m-0">
                            <strong><?php _e('User successfully logged out', 'wpss'); ?></strong>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($description)): ?>
                <div class="col-md-12 p-2">
                    <?php if($login_box): ?>
                        <hr class="pb-1 m-0">
                    <?php endif; ?>
                    <p class="m-0"><?= $description; ?></p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

</body>
</html>