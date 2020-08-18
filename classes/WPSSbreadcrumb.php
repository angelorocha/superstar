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

final class WPSSbreadcrumb {

	public $show_on_home = 0;
	public $delimiter = "";
	public $before_item = "<li class='breadcrumb-item' aria-current='page'>";
	public $after_item = "</li>";

	public function __construct() {

	}

	public function wpss_bc_fronend() {
		$home_delimiter = ( ! is_front_page() || ! is_home() ? $this->delimiter : "" );
		$breadcrumb     = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
		$breadcrumb     .= "$this->before_item<a href='" . _WPSS_SITE_URL . "' title='" . __( 'Home', 'wpss' ) . "'>" . __( 'Home', 'wpss' ) . "</a>$home_delimiter $this->after_item";
		$breadcrumb     .= self::wpss_object_type();
		$breadcrumb     .= '</ol></nav>';

		return $breadcrumb;
	}

	public function wpss_object_type() {
		$id          = get_queried_object_id();
		$object_type = "";

		if ( is_singular( array( 'post', 'page', 'is_attachment' ) ) ):
			$name        = get_post( $id )->post_title;
			$parent      = get_post( $id )->post_parent;
			$object_type = ( ! empty( $parent ) ? $this->before_item . "<a href='" . get_permalink( $parent ) . "' title='" . get_the_title( $parent ) . "'>" . get_the_title( $parent ) . "</a> " . $this->delimiter . $this->after_item . "$this->before_item $name $this->after_item" : "$this->before_item $name $this->after_item" );
		endif;

		if ( is_category() || is_tag() ):
			$object_type = $this->before_item . get_term( $id )->name . $this->after_item;
		endif;

		if ( is_search() ):
			$object_type = $this->before_item . __( 'Search results for: ', 'wpss' ) . get_search_query() . $this->after_item;
		endif;

		if ( is_404() ):
			$object_type = $this->before_item . __( '404 not found', 'wpss' ) . $this->after_item;
		endif;

		if ( is_post_type_archive() ):
			$object_type = $this->before_item . get_post_type_object( get_post( $id )->post_type )->labels->name . $this->after_item;
		endif;

		if ( is_singular() && ! is_singular( array( 'post', 'page' ) ) ):
			$post_type_name = get_post_type_object( get_post( $id )->post_type )->labels->name;
			$post_type_url  = get_post_type_archive_link( get_post( $id )->post_type );
			$name           = get_post( $id )->post_title;
			$object_type    = "$this->before_item <a href='$post_type_url' title='$post_type_name'>$post_type_name</a>$this->delimiter $this->after_item $this->before_item $name $this->after_item";
		endif;

		return $object_type;
	}
}