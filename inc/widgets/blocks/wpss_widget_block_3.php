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

function wpss_widget_block_3() {
	echo "<div class='wpss-widget-block-3 col-md-4'>";
	echo "<div class='wpss-block-container'>";
	$post_image    = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'post-thumbnail' )[0];
	$defaul_thumb   = _WPSS_IMAGES_DIR . "no-thumbnail.png";
	$post_thumbnail = ( has_post_thumbnail() ? $post_image : $defaul_thumb );
	$permalink      = get_permalink();
	$title          = get_the_title();
	echo "<a href='$permalink' title='$title'><img src='$post_thumbnail' alt='image-fluid'></a>";
	echo "<h4><a href='$permalink' title='$title'>$title";
	echo "<small>Em: " . get_the_date( "d-m-Y" );
	echo ", por: @" . get_the_author_meta( 'user_login', get_post( get_the_ID() )->post_author );
	echo "</small></a></h4>";
	echo "<a href='" . get_permalink() . "' title='" . get_the_title() . "' class='wpss-block-btn btn btn-outline-light'>" . __( 'Continue reading...', 'wpss' ) . "</a>";
	echo "</div>";
	echo "</div>";
}