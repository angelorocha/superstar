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

/*** Get terms from taxonomy as link list*/
add_shortcode( 'tax_list', 'wpss_taxonomy_list' );
function wpss_taxonomy_list( $tax_id ) {
	$taxonomy = $tax_id['tax_id'];
	$tax_id   = shortcode_atts( array(
		'id'   => $taxonomy,
		'type' => 'list'
	), $tax_id, 'tax_list' );

	$terms_list = wpss_get_tax_terms( $taxonomy, true, true, false );

	$terms_select = "<form method='post'><select id='wpss-term-select' class='form-control mt-2'>";
	foreach ( wpss_get_tax_terms( $tax_id['id'], false, true, true ) as $key => $term ):
		$terms_select .= "<option value='" . get_term_link( $key ) . "'>$term</option>";
	endforeach;
	$terms_select .= "</select></form>";

	return ( $tax_id['type'] === 'list' ? $terms_list : $terms_select );
}

/*** Get a custom post type posts calendar */
add_shortcode( 'cpt_calendar', 'wpss_cpt_calendar' );
function wpss_cpt_calendar( $post_type ) {
	ob_start();
	$post_type = shortcode_atts( array(
		'cpt' => '',
	), $post_type, 'cpt_calendar' );

	the_widget( 'WPSSCPTCalendar', "posttype=" . $post_type['cpt'] . "", "before_title=<h3 style='display:none;'>" );

	$widget_content = ob_get_contents();

	ob_end_clean();

	return $widget_content;
}