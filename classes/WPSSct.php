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

final class WPSSct {

	public $param_ct_key;
	public $param_ct_name;
	public $param_new_tax_item;
	public $param_all_tax;
	public $param_tax_rewrite;
	public $param_cpt_keys = array();
	public $param_hierarchical = true;
	public $param_tax_add_cap = array();
	public $param_tax_remove_cap = array();

	public function __construct() {
		add_action( 'admin_init', array( $this, 'wpss_add_tax_caps' ) );
		add_action( 'admin_init', array( $this, 'wpss_remove_tax_caps' ) );
		add_filter( 'template_include', array( $this, 'wpss_tax_template' ) );
	}

	public function wpss_make_custom_tax() {
		register_taxonomy( $this->param_ct_key, $this->param_cpt_keys, self::wpss_tax_args() );
	}

	public function wpss_tax_args() {
		$args = array(
			'labels'            => self::wpss_tax_labels(),
			'hierarchical'      => $this->param_hierarchical,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'query_var'         => $this->param_ct_key,
			'rewrite'           => self::wpss_tax_rewrite(),
			'capabilities'      => self::wpss_tax_caps(),
			'show_in_rest'      => true,
		);

		return $args;
	}

	public function wpss_tax_labels() {
		$labels = array(
			'name'                       => sprintf( __( '%s', 'wpss' ), $this->param_ct_name ),
			'singular_name'              => sprintf( __( '%s', 'wpss' ), $this->param_ct_name ),
			'menu_name'                  => sprintf( __( '%s', 'wpss' ), $this->param_ct_name ),
			'all_items'                  => sprintf( __( 'All %s', 'wpss' ), ( ! empty( $this->param_all_tax ) ? $this->param_all_tax : $this->param_ct_name ) ),
			'parent_item'                => sprintf( __( 'Parent %s', 'wpss' ), $this->param_ct_name ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'wpss' ), $this->param_ct_name ),
			'new_item_name'              => sprintf( __( 'New %s name', 'wpss' ), ( ! empty( $this->param_new_tax_item ) ? $this->param_new_tax_item : $this->param_ct_name ) ),
			'add_new_item'               => sprintf( __( 'Add %s', 'wpss' ), ( ! empty( $this->param_new_tax_item ) ? $this->param_new_tax_item : $this->param_ct_name ) ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'wpss' ), $this->param_ct_name ),
			'update_item'                => sprintf( __( 'Update %s', 'wpss' ), $this->param_ct_name ),
			'view_item'                  => sprintf( __( 'View %s', 'wpss' ), $this->param_ct_name ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'wpss' ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'wpss' ), $this->param_ct_name ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wpss' ),
			'popular_items'              => sprintf( __( 'Popular %s', 'wpss' ), $this->param_ct_name ),
			'search_items'               => sprintf( __( 'Search %s', 'wpss' ), $this->param_ct_name ),
			'not_found'                  => __( 'Not Found', 'wpss' ),
			'no_terms'                   => sprintf( __( 'No %s', 'wpss' ), $this->param_ct_name ),
			'items_list'                 => sprintf( __( '%s list', 'wpss' ), $this->param_ct_name ),
			'items_list_navigation'      => sprintf( __( '%s list navigation', 'wpss' ), $this->param_ct_name ),
		);

		return $labels;
	}

	public function wpss_tax_caps() {
		return array(
			'manage_terms' => 'manage_' . $this->param_ct_key,
			'edit_terms'   => 'edit_' . $this->param_ct_key,
			'delete_terms' => 'delete_' . $this->param_ct_key,
			'assign_terms' => 'assign_' . $this->param_ct_key
		);
	}

	public function wpss_add_tax_caps() {
		if ( ! empty( $this->param_tax_add_cap ) ):
			foreach ( $this->param_tax_add_cap as $role ):
				foreach ( self::wpss_tax_caps() as $cap ):
					get_role( $role )->add_cap( $cap );
				endforeach;
			endforeach;

		endif;
	}

	public function wpss_remove_tax_caps() {
		if ( ! empty( $this->param_tax_remove_cap ) ):
			foreach ( $this->param_tax_remove_cap as $role ):
				foreach ( self::wpss_tax_caps() as $cap ):
					get_role( $role )->remove_cap( $cap );
				endforeach;
			endforeach;
		endif;
	}

	public function wpss_tax_rewrite() {
		$rewrite = array(
			'slug'         => sanitize_title( $this->param_ct_name ),
			'with_front'   => true,
			'hierarchical' => $this->param_hierarchical,
		);

		return $rewrite;
	}

	public function wpss_tax_template( $original_template ) {
		$taxonomy_template = locate_template( '/tax-templates/taxonomy-' . $this->param_ct_key . '.php' );
		if ( file_exists( $taxonomy_template ) ):
			if ( is_tax( $this->param_ct_key ) ):
				return $taxonomy_template;
			endif;
		else:
			return $original_template;
		endif;
	}
}