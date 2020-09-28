<?php
/**
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_restrict_login(){
    ?>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="alert alert-danger text-center">
                <strong><?= __('Restrict Content'); ?></strong>
                <p class="m-0">
                    <?= __('To access this area login is required', 'wpss'); ?>
                </p>
            </div>
            <form method="post" action="<?= esc_url(site_url('wp-login.php', 'login_post')); ?>" class="">
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
                    <input type="hidden" name="redirect_to" value="<?= home_url('/downloads'); ?>">
                    <input type="hidden" name="wpss_login_action">
                </div>
            </form>
        </div>
    </div>
    <?php
}