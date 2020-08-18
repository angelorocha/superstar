<?php
/**
 * @param $post_id - Get post id
 * @param string $image_size - Set the image size, default is thumbnail
 * @param string $thumbnail_id - Set attachment ID, default is thumbnail_id
 * @param bool $is_thumbnail
 *
 * @return string
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_image_src( $post_id, $image_size = 'thumbnail', $thumbnail_id = '_thumbnail_id', $is_thumbnail = true ) {
	$image_src = wp_get_attachment_image_src( get_post_meta( $post_id, $thumbnail_id, true ), $image_size )[0];
	#$image_check = file_exists( wp_upload_dir()['path'] . '/' . pathinfo( $image_src )['basename'] );

	if ( ! has_post_thumbnail( $post_id ) && $is_thumbnail ):
		$image_src = _WPSS_IMAGES_DIR . $image_size . ".png";
	endif;

	if ( is_null( $thumbnail_id ) ):
		$image_src = wp_get_attachment_image_src( $post_id, $image_size )[0];
	endif;

	return $image_src;
}