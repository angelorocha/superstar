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
 * Remove default wordpress thumbnail sizes. Sorry...
 *
 * @param $sizes
 * @return mixed
 */

add_filter( 'intermediate_image_sizes_advanced', 'wpss_revome_default_thumbnail_sizes', 1, 999 );
function wpss_revome_default_thumbnail_sizes( $sizes ){
	unset( $sizes['thumbnail'] );
	unset( $sizes['medium'] );
	unset( $sizes['medium_large'] );
	unset( $sizes['large'] );

	return $sizes;
}