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

add_action( 'wpss_body_end', 'wpss_modal_login' );
function wpss_modal_login() {
	$redirect = $_SERVER["REQUEST_URI"];
	?>
    <div class="wpss-modal-login-container d-flex flex-column align-items-center justify-content-center">
        <div class="wpss-modal-login p-3">
            <span class="wpss-login-close">&times;</span>
            <form method="post" action="<?= esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>">
                <div class="form-group">
                    <label for="log"><strong><?php _e('User: ','wpss'); ?></strong></label>
                    <input type="text" name="log" id="log" placeholder="Digite seu usuário de rede" class="form-control">
                </div>

                <div class="form-group">
                    <label for="pwd"><strong><?php _e('Password: ','wpss'); ?></strong></label>
                    <input type="password" name="pwd" id="pwd" placeholder="Digite sua senha de rede" class="form-control">
                </div>

                <div class="form-group form-check">
                    <input id="rememberme" type="checkbox" value="forever" name="rememberme" class="form-check-input">
                    <label class="form-check-label" for="rememberme"><?php _e('Remember','wpss'); ?></label>
                </div>

                <div class="text-right">
                    <input type="submit" name="wp-submit" value="<?php _e('Login','wpss'); ?>" class="btn btn-login mt-3">
                </div>

                <input type="hidden" name="redirect_to" value="<?= $redirect . '?logged_in'; ?>">
                <input type="hidden" name="wpss_login_action">
                <input type="hidden" name="testcookie" value="1">
            </form>
        </div>
    </div>
	<?php
}