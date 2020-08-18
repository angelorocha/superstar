<?php
/**
 * @param $element
 * Define element class or ID
 * @param $mask
 * Define mask format
 * @param $dateformat
 * Define date format
 *
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_datepicker( $element, $mask = '', $dateformat = '' ) {
	wp_enqueue_script( 'wpss-masks', _WPSS_JS_DIR . 'jquery.mask.min.js', array( 'jquery' ), _WPSS_FILE_VERSION, true );
	wp_enqueue_script( 'wpss-datepicker', _WPSS_JS_DIR . 'jquery-ui-datepicker.js', array( 'jquery' ), _WPSS_FILE_VERSION, true );
	wp_enqueue_style( 'wpss-datepicker', _WPSS_CSS_DIR . 'jquery-ui-datepicker.css', '', _WPSS_FILE_VERSION, 'all' );

	$input_mask       = ( empty( $mask ) ? __( '0000-00-00', 'wpss' ) : $mask );
	$input_dateformat = ( empty( $dateformat ) ? __( 'yy-mm-dd', 'wpss' ) : $dateformat );

	$months   = array( __( 'January', 'wpss' ), __( 'February', 'wpss' ), __( 'March', 'wpss' ), __( 'April', 'wpss' ), __( 'May', 'wpss' ), __( 'June', 'wpss' ), __( 'July', 'wpss' ), __( 'August', 'wpss' ), __( 'September', 'wpss' ), __( 'October', 'wpss' ), __( 'November', 'wpss' ), __( 'December', 'wpss' ) );
	$days     = array( __( 'Sunday', 'wpss' ), __( 'Monday', 'wpss' ), __( 'Tuesday', 'wpss' ), __( 'Wednesday', 'wpss' ), __( 'Thursday', 'wpss' ), __( 'Friday', 'wpss' ), __( 'Saturday', 'wpss' ) );
	$days_min = array( __( 'Sun', 'wpss' ), __( 'Mon', 'wpss' ), __( 'Tue', 'wpss' ), __( 'Wed', 'wpss' ), __( 'Thu', 'wpss' ), __( 'Fri', 'wpss' ), __( 'Sat', 'wpss' ) );
	?>
    <script>
        jQuery(function ($) {
            $('<?= $element; ?>').mask('<?= $input_mask; ?>').datepicker({
                dateFormat: "<?= $input_dateformat; ?>",
                monthNames: <?= wp_json_encode( $months ); ?>,
                dayNames: <?= wp_json_encode( $days ); ?>,
                dayNamesMin: <?= wp_json_encode( $days_min ); ?>,
                autoclose: true,
            });
        });
    </script>
	<?php

}