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

/*** Creat a json with get parameter */
add_action( 'wpss_before_head', 'wpss_rest_api' );
function wpss_rest_api() {
	if ( isset( $_GET['wpss_rest'] ) ):

		header( "Content-Type:application/json; charset=utf-8" );

		if ( empty( $_GET['wpss_rest'] ) ):
			echo wp_json_encode( __( 'Choose a post type...', 'wpss' ) );
			exit;
		endif;

		$rest            = new WPSSRest( array( '::1' ) );
		$rest->post_type = $_GET['wpss_rest'];
		$rest->limit     = 10;
		$rest->offset    = 0;
		echo $rest->wpss_rest_get_posts();
		exit;
	endif;
}