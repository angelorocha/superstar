<?php
/**
 * @param $select_id
 *
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 * @author              Angelo Rocha
 */

function wpss_select2( $select_id ) {
	wp_enqueue_script( 'wpss-select2', _WPSS_JS_DIR . 'select2.js', array( 'jquery' ), _WPSS_FILE_VERSION, true );
	wp_enqueue_style( 'wpss-select2', _WPSS_CSS_DIR . 'select2.css', '', _WPSS_FILE_VERSION, 'all' );
	?>
    <script>
        jQuery(function ($) {
            $('#<?=$select_id;?>').select2();
        });
    </script>
	<?php
}