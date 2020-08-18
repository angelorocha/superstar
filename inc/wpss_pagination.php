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
 * @param string $query - Return wp_query results
 *
 * @return string
 */

function wpss_pagination( $query = "" ) {

	global $wp_query;

	$pagination = ( empty( $query ) ? $wp_query : $query );

	$count = 999999999;
	$pages = paginate_links( array(
		'base'         => str_replace( $count, '%#%', esc_url( get_pagenum_link( $count ) ) ),
		'format'       => '?page=%#%',
		'current'      => max( 1, get_query_var( 'paged' ) ),
		'show_all'     => false,
		'end_size'     => 2,
		'mid_size'     => 1,
		'prev_text'    => __( '&laquo;', 'omni' ),
		'next_text'    => __( '&raquo;', 'omni' ),
		'type'         => 'array',
		'total'        => $pagination->max_num_pages
	) );

	$html = "";
	if ( is_array( $pages ) ):
		#$paged = ( get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' );
		$html = '<nav class="text-center" aria-label="' . __( 'Posts navigation', 'wpss' ) . '">';
		$html .= "<ul class='pagination justify-content-center'>";
		foreach ( $pages as $page ):
			$html .= "<li class='page-item" . ( strpos( $page, 'current' ) ? ' active' : '' ) . "'>" . str_replace( 'page-numbers', 'page-link', $page ) . "</li>";
		endforeach;
		$html .= "</ul>";
		$html .= "</nav>";
	endif;

	return $html;
}