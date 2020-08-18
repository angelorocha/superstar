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

function wpss_article_meta() {

	$edit_link = get_edit_post_link( get_the_ID(), false );

	$article_meta = '<ul class="list-unstyled wpss-article-meta text-center">';
	$article_meta .= '<li><i class="far fa-calendar-alt"></i> ' . get_the_date( 'd-m-Y' ) . '</li>';
	$article_meta .= '<li>' . getPostLikeLink( get_the_ID() ) . '</li>';
	$article_meta .= '<li><i class="far fa-eye"></i> ' . wpss_get_post_views( get_the_ID() ) . '</li>';
	if ( current_user_can( 'edit_' . get_post_type() ) ):
		$article_meta .= '<li><a href="' . $edit_link . '" title="Editar"><i class="fas fa-edit"></i> Editar</a></li>';
	endif;

	if ( is_singular( array( 'post', 'clipping' ) ) ):
		$article_meta .= "<li><a href='" . get_permalink() . '?print_content=' . get_the_ID() . "' title='Imprimir' target='_blank'><i class='fas fa-print'></i> Imprimir</a></li>";
	endif;

	$article_meta .= '</ul>';

	return $article_meta;
}