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

class WPSSCptMenu extends WPSSCptSidebar {

	public $param_cpt;
	public $param_cap;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Menu custom post type
	 */
	public function wpss_menu_cpt() {
		$menu                           = new WPSScpt();
		$menu->param_cpt_key            = self::wpss_menu_cpt_key();
		$menu->param_cpt_name           = __('Menu', 'wpss');
		$menu->param_cpt_new            = __('Link', 'wpss');
		$menu->param_cpt_all            = __('Menu', 'wpss');
		$menu->param_show_in_menu       = "edit.php?post_type=$this->param_cpt";
		$menu->param_cpt_public         = false;
		$menu->param_supports           = array( 'title' );
		$menu->param_custom_input       = __('Type link title', 'wpss');
		$menu->param_taxonomies         = null;
		$menu->param_rewrite            = 'menu-' . $this->param_cpt;
		$menu->param_redirect_archive   = basename( get_post_type_archive_link( $this->param_cpt ) );
		$menu->param_redirect_single    = basename( get_post_type_archive_link( $this->param_cpt ) );
		$menu->param_remove_cpt_columns = true;
		$menu->param_add_cap            = $this->param_cap;
		$menu->param_cpt_admin_notice   = __('Drag the menu items in the desired order.', 'wpss');
		$menu->param_cpt_cap_type       = $this->param_cpt;
		$menu->wpss_make_cpt();

		/**
		 * Enable sidebar post order feature
		 */

		$menu_order          = new WPSSPostsOrder();
		$menu_order->objects = $menu->param_cpt_key;
	}

	/**
	 * Define menu metaboxes
	 */
	public function wpss_menu_metaboxes() {
		$menu_meta                = new WPSSMetaBox();
		$menu_meta->metabox_id    = self::wpss_menu_cpt_key() . '_metabox';
		$menu_meta->metabox_title = __('Menu Items', 'wpss');
		$menu_meta->post_type     = array( self::wpss_menu_cpt_key() );
		$menu_meta->fields        = array(
			array(
				'id'          => 'wpss_custom_menu_group',
				'type'        => 'group',
				'description' => __('Add items from this menu', 'wpss'),
				'options'     => array(
					'group_title'    => 'Link {#}',
					'add_button'     => __('Add Link', 'wpss'),
					'remove_button'  => __('Remove Link', 'wpss'),
					'sortable'       => true,
					'closed'         => false,
					'remove_confirm' => __('Are you sure?', 'wpss')
				),

				'group_fields' => array(

					array(
						'name'    => __('Link Type', 'wpss'),
						'id'      => 'wpss_menu_type',
						'type'    => 'radio',
						'default' => 'page',
						'options' => array(
							'page' => __('Page', 'wpss'),
							'link' => __('Link/File', 'wpss')
						)
					),
					array(
						'name'       => __('Select Page', 'wpss'),
						'id'         => 'wpss_menu_page',
						'type'       => 'select',
						'options_cb' => array( $this, 'wpss_menu_select_cb' ),
						'attributes' => array(
							'required'               => true,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_menu_group', 'wpss_menu_type' ) ),
							'data-conditional-value' => 'page',
						),
					),
					array(
						'name'       => __('File Title', 'wpss'),
						'id'         => 'wpss_menu_file_name',
						'type'       => 'text',
						'attributes' => array(
							'required'               => true,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_menu_group', 'wpss_menu_type' ) ),
							'data-conditional-value' => 'link',
						),
					),
					array(
						'name'       => __('File URL', 'wpss'),
						'id'         => 'wpss_menu_file_url',
						'type'       => 'file',
						'options'    => array(
							'add_upload_file_text' => __('Send From PC', 'wpss'),
						),
						'attributes' => array(
							'required'               => true,
							'data-conditional-id'    => wp_json_encode( array( 'wpss_custom_menu_group', 'wpss_menu_type' ) ),
							'data-conditional-value' => 'link',
						),
					),


				),
			)
		);
	}

	/**
	 * Get posts to select field
	 * @return array
	 */
	public function wpss_menu_select_cb() {
		return WPSSquery::wpss_get_post_ids( $this->param_cpt );
	}

	/**
	 * Custom post type menu frontend
	 */
	public function wpss_menu_frontend() {
		?>
        <div class="container">
            <nav class="navbar wpss-cpt-navmenu navbar-expand-md p-0" role="navigation">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#wpss-cpt-menu" aria-controls="wpss-cpt-menu" aria-expanded="false" aria-label="<?php _e( 'Toggle navigation', 'wpss' ); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div id="wpss-cpt-menu" class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto">
						<?php
						WPSSquery::wpss_make_query(
							self::wpss_menu_cpt_key(),
							'-1',
							array( $this, 'wpss_menu_frontend_cb' )
						);
						?>
                    </ul>
                </div><!-- #wpss-cpt-menu -->
            </nav><!-- .wpss-cpt-navmenu -->
        </div><!-- .container -->
		<?php
	}

	public function wpss_menu_frontend_cb() {
		$menu_items = get_post_meta( get_the_ID(), 'wpss_custom_menu_group', true );
		global $wp;

		$menu_title = "";
		$menu_link  = "";
		$nav_item   = "";

		$count_items = 0;

		foreach ( $menu_items as $key => $item ):
			$menu_type = $item['wpss_menu_type'];

			$count_items ++;

			if ( $menu_type === 'page' ):
				$menu_title = get_the_title( $item['wpss_menu_page'] );
				$menu_link  = get_the_permalink( $item['wpss_menu_page'] );
			endif;

			if ( $menu_type === 'link' ):
				$menu_title = $item['wpss_menu_file_name'];
				$menu_link  = $item['wpss_menu_file_url'];
			endif;

			$item_active = ( home_url( $wp->request . '/' ) === $menu_link ? "active" : "" );

			if ( count( $menu_items ) <= 1 ):
				$nav_item .= "<li class='nav-item $item_active'>";
				$nav_item .= "<a href='$menu_link' title='$menu_title' class='nav-link'>$menu_title</a>";
				$nav_item .= "</li>";
			endif;

			if ( count( $menu_items ) > 1 ):
				if ( $count_items == 1 ):
					$nav_item .= "<li class='nav-item dropdown'>";
					$nav_item .= "<a href='#' title='" . get_the_title() . "' id='wpss-cpt-menu-dropdown' class='nav-link dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" . get_the_title() . "</a>";
					$nav_item .= "<div class='dropdown-menu m-0 border-0 rounded-0' aria-labelledby='wpss-cpt-menu-dropdown'>";
				endif;

				if ( $count_items >= 1 && $count_items <= count( $menu_items ) ):
					$nav_item .= "<a href='$menu_link' title='$menu_title' class='dropdown-item rounded-0'>$menu_title</a>";
				endif;

				if ( $count_items == count( $menu_items ) ):
					$nav_item .= "</div>";
					$nav_item .= "</li>";
				endif;

			endif;

		endforeach;

		$count_items = 0;

		echo $nav_item;
	}

	/**
	 * Define custom post type key
	 * @return string
	 */
	public function wpss_menu_cpt_key() {
		$cpt_key = $this->param_cpt;
		if ( strlen( $this->param_cpt ) > 10 ):
			$cpt_key = substr( $this->param_cpt, 0, 10 );
		endif;

		return $cpt_key . '_menu';
	}
}