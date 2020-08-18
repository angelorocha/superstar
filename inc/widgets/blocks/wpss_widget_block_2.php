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

function wpss_widget_block_2( $excerpt ) {
	echo "<div class='col-md-12 wpss-widget-block-2'>";
	echo "<div class='wpss-box-image'>";
	echo "<a href='" . get_permalink() . "' title='" . get_the_title() . "'>";
	if ( has_post_thumbnail() ):
		echo wpss_thumbnail( 'post_cover' );
	else:
		echo "<img src=" . _WPSS_IMAGES_DIR . "'no-thumbnail.png' alt='" . get_the_title() . "' class='img-fluid'>";
	endif;
	echo "</a>";
	echo "</div>";

	$post_date = get_the_date( 'd-m-Y' );
	$views     = wpss_get_post_views( get_the_ID() );
	echo "<div class='wpss-box-info'>";
	echo "<small><span><i class='fa fa-eye'></i> $views</span> <span class='pull-right'><i class='fa fa-calendar'></i> $post_date</span></small>";
	echo "<h4><a href='" . get_permalink() . "' title='" . get_the_title() . "'>" . get_the_title() . "</a></h4>";
	echo "<p class='text-muted text-justify'>" . wpss_excerpt( $excerpt ) . "</p>";
	echo "</div>";
	echo "</div>";
}