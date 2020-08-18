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


add_action( 'wpss_loop_begin', 'wpss_is_fix_template' );
function wpss_is_fix_template() {
	if ( is_author() ):
		return true;
	endif;

	return false;
}

add_action( 'pre_get_posts', 'wpss_include_cpt_in_loop' );
function wpss_include_cpt_in_loop( $query ) {
	if ( wpss_is_fix_template() && ! is_admin() ):
		$query->set( 'post_type', get_post_types() );
	endif;

	return $query;
}