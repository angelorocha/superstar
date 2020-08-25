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

add_action('wpss_after_inside_head', 'wpss_default_favicon');
function wpss_default_favicon(){
    if(!get_option('site_icon')):
        echo '<link rel="icon" href="' . _WPSS_IMAGES_DIR . 'favicon.png" sizes="32x32" />'."\n";
        echo '<link rel="icon" href="' . _WPSS_IMAGES_DIR . 'favicon.png" sizes="192x192" />'."\n";
        echo '<link rel="apple-touch-icon" href="' . _WPSS_IMAGES_DIR . 'favicon.png" />'."\n";
        echo '<meta name="msapplication-TileImage" content="' . _WPSS_IMAGES_DIR . 'favicon.png" />'."\n";
    endif;
}