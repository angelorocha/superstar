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

add_action( 'wpss_body_begin', 'wpss_scroll_to_top' );
function wpss_scroll_to_top() {
	?>
    <span id="wpss-top"></span>
    <div class="wpss-scroll-top">
        <i class="fas fa-chevron-circle-up wpss-top-anchor"></i>
    </div>
	<?php
}