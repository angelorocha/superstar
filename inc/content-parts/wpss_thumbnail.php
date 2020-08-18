<?php
/**
 * @param string $size - Thumbnail image size
 *
 * @param bool $image_link
 *
 * @return string
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 *
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 */

function wpss_thumbnail( $size = 'wpss_thumbnail', $image_link = true ) {
	$permalink       = get_permalink();
	$title           = esc_html( get_the_title() );
	$thumbnail_image = wpss_image_src( get_the_ID(), 'full' );

	$url = ( is_single() || is_page() ? $thumbnail_image : $permalink );

	$thumbnail = ( $image_link ? "<a href='$url' title='$title'>" : "" );

	$thumbnail .= "<img class='img-fluid' src='" . wpss_image_src( get_the_ID(), $size ) . "' alt='" . $title . "'>";

	$thumbnail .= ( $image_link ? "</a>" : "" );

	return $thumbnail;
}