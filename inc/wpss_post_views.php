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

/**
 * @param $post_id - Get post id
 *
 * @return string
 */
function wpss_get_post_views( $post_id ) {

	$meta_key = '_wpss_post_views';

	$views = get_post_meta( $post_id, $meta_key, true );

	if ( $views === '' ):
		delete_post_meta( $post_id, $meta_key );
		add_post_meta( $post_id, $meta_key, '0' );
	endif;

	return $views . __( ' views', 'wpsss' );
}

/**
 * @param $post_id - Get post id
 */
add_action( 'wpss_content_begin', 'wpss_set_post_views' );
function wpss_set_post_views() {

	$meta_key = '_wpss_post_views';
	$views    = get_post_meta( get_the_ID(), $meta_key, true );

	if ( $views === '' ):
		$views = 0;
		delete_post_meta( get_the_ID(), $meta_key );
		add_post_meta( get_the_ID(), $meta_key, '0' );
	else:
		$views ++;
		update_post_meta( get_the_ID(), $meta_key, $views );
	endif;
}

/**
 * @return mixed
 */
add_filter( 'manage_posts_columns', 'wpss_post_views_column' );
function wpss_post_views_column( $defaults ) {
	$defaults['post_views_column'] = __( 'Views', 'wpss' );

	return $defaults;
}

/**
 * @param $column_name
 * @param $id
 */
add_action( 'manage_posts_custom_column', 'wpss_post_views_set_column' );
function wpss_post_views_set_column( $column_name ) {
	if ( $column_name === 'post_views_column' ):
		echo wpss_get_post_views( get_the_ID() );
	endif;
}