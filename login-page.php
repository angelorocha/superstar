<!DOCTYPE html>
<html <?php language_attributes(); ?> class="h-100">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <title><?= get_bloginfo( 'name' ) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="profile" href="https://gmpg.org/xfn/11"/>
	<?php wp_head(); ?>
</head>
<body class="wpss-login-page h-100">

<div class="h-100 d-flex flex-column justify-content-center">
    <div class="container">

        <div class="row wpss-login-box">

            <div class="col-md-4">
                <?php bloginfo( 'name' ); ?>
            </div>

            <div class="col-md-8">

                <form method="post" action="<?= esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" class="bg-white rounded h-100 p-3">
                    <label for="user"><strong><?php _e('User','wpss'); ?></strong>:</label>
                    <input type="text" name="log" id="user" placeholder="<?php _e('User Login','wpss'); ?>" class="form-control mb-2" required="required">

                    <label for="password"><strong><?php _e('Password','wpss'); ?></strong>:</label>
                    <input type="password" name="pwd" id="password" placeholder="<?php _e('User Password','wpss'); ?>" class="form-control" required="required">

                    <div class="form-group mt-2">
                        <div class="form-check">
                            <input id="rememberme" type="checkbox" value="forever" name="rememberme" class="form-check-input">
                            <label class="form-check-label" for="rememberme"><?php _e('Remember me','wpss'); ?></label>
                        </div>
                    </div>
                    <input type="submit" name="wp-submit" value="<?php _e('Login','wpss'); ?>" class="btn btn-login btn-block mt-3">
                    <input type="hidden" name="redirect_to" value="<?= home_url() . '?logged_in'; ?>">
                    <input type="hidden" name="wpss_login_action">
                    <input type="hidden" name="testcookie" value="1">
                </form>

            </div>

			<?php if ( isset( $_GET['logout'] ) ): ?>
                <div class="col-md-12">
                    <div class="alert alert-info p-3 mt-3">
                        <p class="text-center m-0">
                            <strong><?php _e('User successfully logged out','wpss'); ?></strong>
                        </p>
                    </div>
                </div>
			<?php endif; ?>

        </div>

    </div>
</div>

</body>
</html>