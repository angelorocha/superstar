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

function wpss_post_content() {

	global $wpss_show_thumbnail;
	$wpss_show_thumbnail = true;

	if ( have_posts() ):

		while ( have_posts() ): the_post();

			echo "<article id='wpss-article' class='wpss-article-content'>";

			do_action( 'wpss_content_begin' );

			if ( $wpss_show_thumbnail && has_post_thumbnail() ):
				echo "<div class='wpss-post-thumbnail'>" . wpss_thumbnail( 'post_cover' ) . "</div>";
			endif;

			echo "<h3 aria-labelledby='wpss-article' class='wpss-article-title text-center'>" . get_the_title() . "</h3>";

			echo "<div class='wpss-article-text'>";

			echo wpss_article_meta();

			do_action( 'wpss_inside_content_begin' );

			the_content();

			do_action( 'wpss_inside_content_end' );

			echo "</div>";

			do_action( 'wpss_content_end' );

			echo "</article>";

		endwhile;

	endif;
}