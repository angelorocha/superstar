<?php
/**
 * @param $tax
 * @param bool $list
 * @param bool $link
 * @param bool $array
 *
 * @param bool $hide_empty
 *
 * @return array|string
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_get_tax_terms( $tax, $list = true, $link = true, $array = false, $hide_empty = false ) {

	$args  = array(
		'hide_empty' => $hide_empty,
		'taxonomy'   => $tax,
	);
	$terms = get_terms( $args );

	$items    = '';
	$array_op = array();
	foreach ( $terms as $term ):
		$term_name                  = ( $link ? "<a href='" . get_term_link( $term->term_id ) . "' title='$term->name'>$term->name</a>" : "$term->name" );
		$item_list                  = ( $list ? "<li>$term_name</li>" : "$term_name" );
		$items                      .= $item_list;
		$array_op[ $term->term_id ] = $term->name;
	endforeach;

	return ( $array ? $array_op : $items );
}