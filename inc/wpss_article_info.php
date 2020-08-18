<?php
/**
 * @param $cat_tax
 * @param $cat_name
 * @param $tag_tax
 * @param $tag_name
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

function wpss_article_info( $cat_tax, $cat_name, $tag_tax, $tag_name ) {

	$article_info = '';

	$article_info .= '<div class="wpss-article-info">';
	$article_info .= '<div class="row">';
	$article_info .= '<div class="col-md-6">';
	if ( ! is_null( $cat_tax ) ):
		$article_info .= wpss_get_terms(
			get_the_ID(),
			$cat_tax,
			$cat_name,
			'<ul class="list-inline">'
		);
	endif;
	$article_info .= '</div>';
	$article_info .= '<div class="col-md-6">';
	if ( ! is_null( $tag_tax ) ):
		$article_info .= wpss_get_terms(
			get_the_ID(),
			$tag_tax,
			$tag_name,
			'<ul class="list-inline">'
		);
	endif;
	$article_info .= '</div>';
	$article_info .= '</div>'; // .row
	$article_info .= '</div>'; // .wpss-article-info

	return $article_info;
}