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
 *
 * @param $sizes
 *
 * @return array
 */

add_filter( 'image_size_names_choose', 'wpss_custom_galley_image_names' );
function wpss_custom_galley_image_names( $sizes ) {
	$custom_names = array(
		'wpss_thumbnail'  => __( 'Thumbnail', 'wpss' ),
		'wpss_post_cover'      => __( 'Post Cover', 'wpss' ),
		'wpss_container_cover' => __( 'Container Cover', 'wpss' ),
		'wpss_full_width_cover' => __( 'Full Width Image', 'wpss' )
	);

	return array_merge( $sizes, $custom_names );
}