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

class WPSSCptSidebar {

	public $param_sdb_cpt;
	public $param_sdb_cap;

	public function __construct() {

	}

	/**
	 * Make a custom sidebar
	 */
	public function wpss_sidebar_cpt() {
		$sidebar                           = new WPSScpt();
		$sidebar->param_cpt_key            = self::wpss_sidebar_cpt_key();
		$sidebar->param_cpt_name           = 'Sidebar';
		$sidebar->param_cpt_new            = 'Widget';
		$sidebar->param_cpt_all            = 'Sidebar';
		$sidebar->param_show_in_menu       = "edit.php?post_type=$this->param_sdb_cpt";
		$sidebar->param_cpt_public         = false;
		$sidebar->param_supports           = array( 'title' );
		$sidebar->param_custom_input       = 'Digite o título do widget';
		$sidebar->param_taxonomies         = null;
		$sidebar->param_rewrite            = 'sidebar-' . $this->param_sdb_cpt;
		$sidebar->param_redirect_archive   = basename( get_post_type_archive_link( $this->param_sdb_cpt ) );
		$sidebar->param_redirect_single    = basename( get_post_type_archive_link( $this->param_sdb_cpt ) );
		$sidebar->param_remove_cpt_columns = true;
		$sidebar->param_add_cap            = $this->param_sdb_cap;
		$sidebar->param_cpt_admin_notice   = 'Posicione os items arrastando e soltando na ordem desejada.';
		$sidebar->param_cpt_cap_type       = $this->param_sdb_cpt;
		$sidebar->wpss_make_cpt();

		/**
		 * Enable sidebar post order feature
		 */

		$sidebar_order          = new WPSSPostsOrder();
		$sidebar_order->objects = $sidebar->param_cpt_key;
	}

	/**
	 * Define sidebar metabox
	 */
	public function wpss_sidebar_metaboxes() {
		$sidebar_meta                = new WPSSMetaBox();
		$sidebar_meta->metabox_id    = self::wpss_sidebar_cpt_key() . '_metabox';
		$sidebar_meta->metabox_title = 'Items do Widget';
		$sidebar_meta->post_type     = array( self::wpss_sidebar_cpt_key() );
		$sidebar_meta->fields        = array(
			array(
				'id'          => 'wpss_custom_sdb_group',
				'type'        => 'group',
				'description' => 'Selecione os items do widget.',
				'options'     => array(
					'group_title'    => 'Link {#}',
					'add_button'     => 'Adicionar Link',
					'remove_button'  => 'Remover Link',
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => 'Remover link?'
				),

				'group_fields' => array(

					array(
						'name'    => 'Tipo de Link',
						'id'      => 'wpss_sdb_type',
						'type'    => 'radio',
						'default' => 'page',
						'options' => array(
							'page'  => "Página",
							'link'  => "Link/Arquivo",
							'text'  => "Texto",
							'image' => "Imagem",
						)
					),
					/*** If is a page link */
					array(
						'name'       => 'Selecionar Página',
						'id'         => 'wpss_sdb_page',
						'type'       => 'select',
						'options_cb' => array( $this, 'wpss_sidebar_select_cb' ),
						'attributes' => array(
							'required'               => true,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_sdb_group', 'wpss_sdb_type' ) ),
							'data-conditional-value' => 'page',
						),
					),

					/*** If is a external link or file */
					array(
						'name'       => 'Titulo do Arquivo',
						'id'         => 'wpss_sdb_file_name',
						'type'       => 'text',
						'attributes' => array(
							'required'               => true,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_sdb_group', 'wpss_sdb_type' ) ),
							'data-conditional-value' => 'link',
						),
					),
					array(
						'name'       => 'URL/Link do Arquivo',
						'id'         => 'wpss_sdb_file_url',
						'type'       => 'file',
						'options'    => array(
							'add_upload_file_text' => 'Selecionar Arquivo',
						),
						'attributes' => array(
							'required'               => true,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_sdb_group', 'wpss_sdb_type' ) ),
							'data-conditional-value' => 'link',
						),
					),

					/*** If is a text input */
					array(
						'name'       => 'Digite o conteúdo',
						'id'         => 'wpss_sdb_textarea',
						'type'       => 'textarea',
						'attributes' => array(
							'required'               => true,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_sdb_group', 'wpss_sdb_type' ) ),
							'data-conditional-value' => 'text',
						),
					),

					/*** If is a image */
					array(
						'name'       => 'URL <sup>(opcional)</sup>',
						'id'         => 'wpss_sdb_image_url',
						'type'       => 'text',
						'attributes' => array(
							'required'               => false,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_sdb_group', 'wpss_sdb_type' ) ),
							'data-conditional-value' => 'image',
						),
					),

					array(
						'name'         => 'Imagem',
						'id'           => 'wpss_sdb_image',
						'type'         => 'file',
						'options'      => array(
							'url' => false,
						),
						'text'         => array(
							'add_upload_file_text' => 'Selecionar Imagem',
						),
						'query_args'   => array(
							'type' => array(
								'image/gif',
								'image/jpeg',
								'image/png',
							),
						),
						'preview_size' => 'wpss_thumbnail',
						'attributes'   => array(
							'required'               => true,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_sdb_group', 'wpss_sdb_type' ) ),
							'data-conditional-value' => 'image'
						),
					),

				),
			)
		);
	}

	public function wpss_sidebar_select_cb() {
		return WPSSquery::wpss_get_post_ids( $this->param_sdb_cpt );
	}

	/**
	 * Sidebar frontend
	 */
	public function wpss_sidebar_frontend() {
		WPSSquery::wpss_make_query(
			self::wpss_sidebar_cpt_key(),
			'-1',
			array( $this, 'wpss_sidebar_frontend_cb' )
		);
	}

	public function wpss_sidebar_frontend_cb() {
		$group = get_post_meta( get_the_ID(), 'wpss_custom_sdb_group', true );
		$item  = '';
		foreach ( $group as $key => $val ):

			$type = $val['wpss_sdb_type'];

			if ( $type === 'page' ):
				$item .= '<li><a href="' . get_permalink( $val['wpss_sdb_page'] ) . '" title="' . get_the_title( $val['wpss_sdb_page'] ) . '">' . get_the_title( $val['wpss_sdb_page'] ) . '</a></li>';
			endif;

			if ( $type === 'link' ):
				$item .= '<li><a href="' . $val['wpss_sdb_file_url'] . '" title="' . $val['wpss_sdb_file_name'] . '">' . $val['wpss_sdb_file_name'] . '</a></li>';
			endif;

			if ( $type === 'text' ):
				$item .= '<p class="text-muted">' . do_shortcode( $val['wpss_sdb_textarea'] ) . '</p>';
			endif;

			if ( $type === 'image' ):
				$image     = wpss_image_src( $val['wpss_sdb_image_id'], 'wpss_thumbnail', null );
				$image_id  = $val['wpss_sdb_image_id'];
				$image_url = $val['wpss_sdb_image_url'];

				if ( ! empty( $image_url ) ):
					$item .= '<a href="' . $image_url . '" title="' . get_the_title( $image_id ) . '"><img src="' . $image . '" alt="' . get_the_title( $image_id ) . '" /></a>';
				else:
					$item .= '<img class="img-fluid" src="' . $image . '" alt="' . get_the_title( $image_id ) . '" />';
				endif;
			endif;

		endforeach;
		?>
        <aside>
            <h3><?= get_the_title(); ?></h3>
            <ul>
				<?= $item; ?>
            </ul>
        </aside>
		<?php
	}

	/**
	 * Custom sidebar post type key
	 * @return string
	 */
	public function wpss_sidebar_cpt_key() {
		$cpt_key = $this->param_sdb_cpt;
		if ( strlen( $this->param_sdb_cpt ) > 10 ):
			$cpt_key = substr( $this->param_sdb_cpt, 0, 10 );
		endif;

		return $cpt_key . '_sdb';
	}
}