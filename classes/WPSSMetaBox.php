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

final class WPSSMetaBox {

	public $metabox_id = '';
	public $metabox_title = '';
	public $post_type = array();
	public $context = 'normal';
	public $show_names = true;
	public $styles = true;
	public $closed = false;
	public $fields = array();
	public $option_key = null;
	public $position = null;
	public $icon_url = null;
	public $save_button = null;
	public $parent_slug = null;
	public $capability = 'manage_options';

	public function __construct() {
		add_action( 'cmb2_admin_init', array( $this, 'wpss_make_metabox' ) );
	}

	public function wpss_make_metabox() {
		$wpss_meta = new_cmb2_box( array(
			'id'           => $this->metabox_id,
			'title'        => __( "$this->metabox_title", "wpss" ),
			'object_types' => $this->post_type,
			'context'      => $this->context,
			'priority'     => 'high',
			'show_names'   => $this->show_names,
			'cmb_styles'   => $this->styles,
			'closed'       => $this->closed,
			'option_key'   => $this->option_key,
			'position'     => $this->position,
			'icon_url'     => $this->icon_url,
			'save_button'  => $this->save_button,
			'parent_slug'  => $this->parent_slug,
			'capability'   => $this->capability
		) );

		foreach ( $this->fields as $key => $field ):
			$wpss_meta->add_field( $field );

			foreach ( $field as $k => $val ):
				if ( $val === 'group' ):
					foreach ( $field['group_fields'] as $group_field ):
						$wpss_meta->add_group_field( $field['id'], $group_field );
					endforeach;
				endif;
			endforeach;

		endforeach;

	}

	public static function wpss_option( $key = '', $option_key = '', $default = false ) {
		if ( function_exists( 'cmb2_get_option' ) ) {
			return cmb2_get_option( $option_key, $key, $default );
		}
		$opts = get_option( $option_key, $default );
		$val  = $default;
		if ( 'all' == $key ) {
			$val = $opts;
		} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}

		return $val;
	}
}