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

class WPSSMetaFilter {

	public $filter_label;
	public $post_type;
	public $meta_key;

	public function __construct() {

	}

	public function wpss_make_column_filter() {
		add_action( 'current_screen', function () {
			$screen = explode( '-', get_current_screen()->id )[1];
			if ( is_admin() && $screen == $this->post_type ):
				add_filter( 'request', array( $this, 'wpss_meta_column_get' ), 10, 1 );
				add_filter( 'restrict_manage_posts', array( $this, 'wpss_meta_column_select' ) );
			endif;
		} );
	}

	/**
	 * Get meta key
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function wpss_meta_column_get( $request ) {
		if ( isset( $_GET[ $this->meta_key ] ) && ! empty( $_GET[ $this->meta_key ] ) ):
			$request['meta_key']   = $this->meta_key;
			$request['meta_value'] = $_GET[ $this->meta_key ];
		endif;

		return $request;
	}

	/**
	 * The select filter
	 */
	public function wpss_meta_column_select() {
		$select = "<select name='$this->meta_key' id='$this->meta_key'>";
		$select .= "<option value=''>$this->filter_label</option>";
		foreach ( self::wpss_meta_column_query() as $option ):
			$selected = ( isset( $_GET[ $this->meta_key ] ) && ! empty( $_GET[ $this->meta_key ] ) ? selected( $_GET['$this->meta_key'], $option ) : '' );
			$select   .= "<option value='" . esc_attr( $option['meta_value'] ) . "$selected'>" . get_post_meta( $option['post_id'], $this->meta_key, true ) . "</option>";
		endforeach;
		$select .= "</select>";

		echo $select;
	}

	/**
	 * Get meta key to filter
	 * @return array
	 */
	public function wpss_meta_column_query() {
		global $wpdb;
		$query_group    = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'wpss_show_in_rest' GROUP BY (meta_value);", ARRAY_A );
		$query_distinct = $wpdb->get_col( "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '$this->meta_key' ORDER BY meta_value" );

		return $query_group;
	}
}