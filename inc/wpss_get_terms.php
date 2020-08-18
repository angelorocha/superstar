<?php
/**
 * @param $post_id
 * @param $taxonomy
 * @param bool $container_title
 * @param string $before_container
 * @param string $after_container
 * @param string $before_item
 * @param string $after_item
 * @param bool $term_url
 *
 * #@return string
 *
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_get_terms( $post_id, $taxonomy, $container_title = false, $before_container = '<ul>', $after_container = '</ul>', $before_item = '<li>', $after_item = '</li>', $term_url = true ) {

	/**
	 * term_id
	 * name
	 * slug
	 * term_group
	 * term_taxonomy_id
	 * taxonomy
	 * description
	 * parent
	 * count
	 * filter
	 */

	$terms  = get_the_terms( $post_id, $taxonomy );
	$output = '';

	if ( $container_title ):
		$output .= "<h4>$container_title</h4>";
	endif;

	$output .= "$before_container";

	if ( $terms ):
		foreach ( $terms as $key => $term ) :
			if ( $term_url ):
				$term_link = get_term_link( $term->term_id, $taxonomy );
				$output    .= "$before_item<a href='$term_link' title='$term->name'>$term->name</a>$after_item";
			endif;

			if ( ! $term_url ):
				$output .= "$before_item $term->name $after_item";
			endif;
		endforeach;
	endif;

	$output .= "$after_container";

	return $output;
}