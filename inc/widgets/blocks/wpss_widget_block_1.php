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

function wpss_widget_block_1( $excerpt = 20 ) {
	echo "<div class='col-md-4 col-sm-12 wpss-widget-block-1'>";
	echo "<div class='wpss-block'>";

	echo "<a href='" . get_permalink() . "' title='" . get_the_title() . "'>";

	echo "<img src='" . wpss_image_src( get_the_ID(), 'wpss_thumbnail' ) . "' alt='" . get_the_title() . "' class='img-fluid'>";

	$post_date = get_the_date( 'd-m-Y' );
	$views     = wpss_get_post_views( get_the_ID() );
	echo "<small><span><i class='fa fa-eye'></i> $views</span> <span class='pull-right'><i class='fa fa-calendar'></i> $post_date</span></small>";
	echo "<h4>" . get_the_title() . "</h4>";
	echo "<p class='text-muted text-justify'>" . wpss_excerpt( $excerpt ) . "</p>";
	echo "</a>";
	echo "</div>";
	echo "</div>";// .col-md-4

}