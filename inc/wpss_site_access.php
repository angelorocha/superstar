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

add_action('wpss_before_head', 'wpss_site_access');
function wpss_site_access(){
    global $wpss_option;
    $site_access = $wpss_option['wpss_maintenance'];
    if($site_access):
        if(!is_user_logged_in()):
            require_once _WPSS_THEME_DIR . '/login-page.php';
            exit;
        endif;
    endif;
}