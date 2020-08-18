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

function wpss_archive_title() {
	$archive_title = '';

	if ( is_post_type_archive() ):
		$archive_title = post_type_archive_title( '', false );
	endif;

	if ( is_tag() || is_tax() ):
		$archive_title = single_tag_title( '', false );
	endif;

	if ( is_category() ):
		$archive_title = single_tag_title( '', false );
	endif;

	if ( is_search() ):
		$archive_title = __( 'Search results for: ', 'wpss' ) . get_search_query();
	endif;

	if ( is_404() ):
		$archive_title = __( '404 - Content not found', 'wpss' );
	endif;

	if ( is_day() || is_month() || is_year() ):
		$archive_title = get_the_archive_title();
	endif;

	if ( is_author() ):
		$archive_title = get_the_author();
	endif;

	return $archive_title;
}