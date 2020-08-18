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

final class WPSScpt extends WPSSCptMenu {

	public $param_cpt_key;
	public $param_cpt_name;
	public $param_cpt_new;
	public $param_cpt_all;
	public $param_cpt_hierarchical = false;
	public $param_cpt_public = true;
	public $param_supports = array( 'title', 'editor', 'thumbnail' );
	public $param_menu_position = 5;
	public $param_show_in_menu = '';
	public $param_taxonomies = array();
	public $param_custom_input = '';
	public $param_rewrite = '';
	public $param_redirect_archive = false;
	public $param_redirect_single = false;
	public $param_menu_icon = '';
	public $param_remove_cpt_columns = false;
	public $param_add_cap = array();
	public $param_remove_cap = array();
	public $param_custom_cpt_js = false;
	public $param_custom_cpt_css = false;
	public $param_cpt_custom_menu = false;
	public $param_cpt_custom_sidebar = false;
	public $param_cpt_contact_form = false;
	public $param_cpt_admin_notice = false;
	public $param_cpt_cap_type = false;

	public function __construct() {

		parent::__construct();

		add_action( 'admin_init', array( $this, 'wpss_add_cpt_caps' ) );
		add_action( 'admin_init', array( $this, 'wpss_remove_cpt_caps' ) );
		add_action( 'admin_init', array( $this, 'wpss_remove_default_columns_filter' ) );
		add_action( 'after_switch_theme', array( $this, 'wpss_flush_rewrite_rules' ) );
		add_action( 'archive_template', array( $this, 'wpss_ctp_custom_archive' ) );
		add_action( 'single_template', array( $this, 'wpss_cpt_custom_single' ) );
		add_action( 'enter_title_here', array( $this, 'wpss_cpt_custom_input' ) );
		add_action( 'template_redirect', array( $this, 'wpss_cpt_redirect_archive' ) );
		add_action( 'template_redirect', array( $this, 'wpss_cpt_redirect_single' ) );
		add_action( 'admin_print_scripts-post-new.php', array( $this, 'wpss_cpt_admin_scripts_js' ), 20, 1 );
		add_action( 'admin_print_scripts-post.php', array( $this, 'wpss_cpt_admin_scripts_js' ), 20, 1 );
		add_action( 'admin_print_scripts-post-new.php', array( $this, 'wpss_cpt_admin_scripts_css' ), 20, 1 );
		add_action( 'admin_print_scripts-post.php', array( $this, 'wpss_cpt_admin_scripts_css' ), 20, 1 );
		add_action( 'current_screen', array( $this, 'wpss_cpt_admin_notices' ) );
		add_action( 'wpss_after_header', array( $this, 'wpss_cpt_custom_menu_frontend' ) );
		add_action( 'wpss_sidebar_before', array( $this, 'wpss_cpt_custom_sidebar_frontend' ) );
	}

	public function wpss_make_cpt() {
		/**
		 * Make a new post type
		 */
		register_post_type( $this->param_cpt_key, self::wpss_cpt_args() );

		/**
		 * Define a post type menu
		 */
		self::wpss_cpt_custom_menu();

		/**
		 * Define post type sidebar
		 */
		self::wpss_cpt_custom_sidebar();

		/**
		 * Define a contact form feature to post type
		 */
		self::wpss_cpt_contact_form();
	}

	/**
	 * Post type general definition
	 * @return array
	 */
	public function wpss_cpt_args() {

		$args = array(
			'label'               => __( 'Post Type', 'wpss' ),
			'description'         => __( 'Post Type Description', 'wpss' ),
			'labels'              => self::wpss_cpt_labels(),
			'supports'            => $this->param_supports,
			'taxonomies'          => ( $this->param_taxonomies ? $this->param_taxonomies : array() ),
			'hierarchical'        => $this->param_cpt_hierarchical,
			'public'              => $this->param_cpt_public,
			'show_ui'             => true,
			'show_in_menu'        => ( ! empty( $this->param_show_in_menu ) ? $this->param_show_in_menu : true ),
			'menu_position'       => $this->param_menu_position,
			'menu_icon'           => ( ! empty( $this->param_menu_icon ) ? $this->param_menu_icon : _WPSS_THEME_DIR_URI . '/docs/images/default_cpt_icon.png' ),
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => $this->param_cpt_key,
			'rewrite'             => self::wpss_cpt_rewrite(),
			#'capabilities'        => self::wpss_cpt_caps(),
			'show_in_rest'        => true,
		);

		if ( $this->param_cpt_cap_type ):
			$args['capability_type'] = $this->param_cpt_cap_type;
		else:
			$args['capabilities'] = self::wpss_cpt_caps();
		endif;

		return $args;
	}

	/**
	 * Post type default capabilities
	 * @return array
	 */
	public function wpss_cpt_caps() {
		$capabilities = array(
			'edit_post'              => 'edit_' . sanitize_title( $this->param_cpt_key ),
			'read_post'              => 'read_' . sanitize_title( $this->param_cpt_key ),
			'delete_post'            => 'delete_' . sanitize_title( $this->param_cpt_key ),
			'edit_posts'             => 'edit_' . sanitize_title( $this->param_cpt_key ) . 's',
			'edit_others_posts'      => 'edit_others_' . sanitize_title( $this->param_cpt_key ) . 's',
			'publish_posts'          => 'publish_' . sanitize_title( $this->param_cpt_key ) . 's',
			'delete_posts'           => 'delete_' . sanitize_title( $this->param_cpt_key ) . 's',
			'delete_private_posts'   => 'delete_private_' . sanitize_title( $this->param_cpt_key ) . 's',
			'delete_published_posts' => 'delete_published_' . sanitize_title( $this->param_cpt_key ) . 's',
			'delete_others_posts'    => 'delete_others_' . sanitize_title( $this->param_cpt_key ) . 's',
			'read_private_posts'     => 'read_private_' . sanitize_title( $this->param_cpt_key ) . 's',
			'edit_published_posts'   => 'edit_published_' . sanitize_title( $this->param_cpt_key ) . 's',
			'edit_private_posts'     => 'edit_private_' . sanitize_title( $this->param_cpt_key ) . 's',
		);

		return $capabilities;
	}

	/**
	 * Add post type capabilities to roles
	 */
	public function wpss_add_cpt_caps() {
		if ( ! empty( $this->param_add_cap ) ):

			foreach ( $this->param_add_cap as $role ):
				foreach ( self::wpss_cpt_caps() as $cap ):
					get_role( $role )->add_cap( $cap );
				endforeach;
			endforeach;

		endif;
	}

	/**
	 * Remove post type capabilities from role
	 */
	public function wpss_remove_cpt_caps() {
		if ( ! empty( $this->param_remove_cap ) ):

			foreach ( $this->param_remove_cap as $role ):
				foreach ( self::wpss_cpt_caps() as $cap ):
					get_role( $role )->remove_cap( $cap );
				endforeach;
			endforeach;

		endif;
	}

	/**
	 * Post type default labels
	 * @return array
	 */
	public function wpss_cpt_labels() {
		$labels = array(
			'name'                  => sprintf( __( "%s", 'wpss' ), $this->param_cpt_name ),
			'singular_name'         => sprintf( __( "%s", 'wpss' ), $this->param_cpt_name ),
			'menu_name'             => sprintf( __( "%s", 'wpss' ), $this->param_cpt_name ),
			'name_admin_bar'        => sprintf( __( "%s", 'wpss' ), $this->param_cpt_name ),
			'archives'              => sprintf( __( '%s Archives', 'wpss' ), $this->param_cpt_name ),
			'attributes'            => sprintf( __( '%s Attributes', 'wpss' ), $this->param_cpt_name ),
			'parent_item_colon'     => __( 'Parent Item:', 'wpss' ),
			'all_items'             => sprintf( __( '%s', 'wpss' ), ( ! empty( $this->param_cpt_all ) ? $this->param_cpt_all : $this->param_cpt_name ) ),
			'add_new_item'          => sprintf( __( 'Add %s', 'wpss' ), ( ! empty( $this->param_cpt_new ) ? $this->param_cpt_new : $this->param_cpt_name ) ),
			'add_new'               => sprintf( __( 'Add %s', 'wpss' ), ( ! empty( $this->param_cpt_new ) ? $this->param_cpt_new : $this->param_cpt_name ) ),
			'new_item'              => sprintf( __( 'New %s', 'wpss' ), ( ! empty( $this->param_cpt_new ) ? $this->param_cpt_new : $this->param_cpt_name ) ),
			'edit_item'             => sprintf( __( 'Edit %s', 'wpss' ), $this->param_cpt_name ),
			'update_item'           => sprintf( __( 'Update %s', 'wpss' ), $this->param_cpt_name ),
			'view_item'             => sprintf( __( 'View %s', 'wpss' ), $this->param_cpt_name ),
			'view_items'            => sprintf( __( 'View %s' . 's', 'wpss' ), $this->param_cpt_name ),
			'search_items'          => sprintf( __( 'Search %s', 'wpss' ), $this->param_cpt_name ),
			'not_found'             => __( 'Not found', 'wpss' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wpss' ),
			'featured_image'        => __( 'Featured Image', 'wpss' ),
			'set_featured_image'    => __( 'Set featured image', 'wpss' ),
			'remove_featured_image' => __( 'Remove featured image', 'wpss' ),
			'use_featured_image'    => __( 'Use as featured image', 'wpss' ),
			'insert_into_item'      => __( 'Insert in item', 'wpss' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'wpss' ),
			'items_list'            => __( 'Items list', 'wpss' ),
			'items_list_navigation' => __( 'Items list navigation', 'wpss' ),
			'filter_items_list'     => __( 'Filter items list', 'wpss' ),
		);

		return $labels;
	}

	/**
	 * Post type rewrite rule
	 * @return array
	 */
	public function wpss_cpt_rewrite() {
		$rewrite = array(
			'slug'       => ( ! empty( $this->param_rewrite ) ? $this->param_rewrite : sanitize_title( $this->param_cpt_name ) ),
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		return $rewrite;
	}

	/**
	 * Define post type default input to new post
	 *
	 * @param $input
	 *
	 * @return string|void
	 */
	public function wpss_cpt_custom_input( $input ) {
		global $post_type;
		if ( ! empty( $this->param_custom_input ) ):
			if ( is_admin() && $this->param_cpt_key == $post_type ):
				return __( "$this->param_custom_input", "wpss" );
			endif;
		endif;

		return $input;
	}

	/**
	 * Auto add post type custom archive template, see folder "cpt-templates"
	 *
	 * @param $archive_template
	 *
	 * @return string
	 */
	public function wpss_ctp_custom_archive( $archive_template ) {
		if ( is_post_type_archive( $this->param_cpt_key ) ):
			if ( file_exists( locate_template( 'cpt-templates/archive-' . $this->param_cpt_key . '.php' ) ) ):
				$archive_template = locate_template( 'cpt-templates/archive-' . $this->param_cpt_key . '.php' );
			endif;
		endif;

		return $archive_template;
	}

	/**
	 * Auto add post type custom single template, see folder "cpt-templates"
	 *
	 * @param $single_template
	 *
	 * @return string
	 */
	public function wpss_cpt_custom_single( $single_template ) {
		if ( is_singular( $this->param_cpt_key ) ):
			if ( file_exists( locate_template( 'cpt-templates/single-' . $this->param_cpt_key . '.php' ) ) ):
				$single_template = locate_template( 'cpt-templates/single-' . $this->param_cpt_key . '.php' );
			endif;
		endif;

		return $single_template;
	}

	/**
	 * Redirect post type archive if is applicable
	 */
	public function wpss_cpt_redirect_archive() {
		if ( $this->param_redirect_archive ):
			if ( is_post_type_archive( $this->param_cpt_key ) ):
				wp_redirect( home_url( $this->param_redirect_archive ), 301 );
				exit;
			endif;
		endif;
	}

	/**
	 * Redirect post type single if is applicable
	 */
	public function wpss_cpt_redirect_single() {
		if ( $this->param_redirect_single ):
			if ( is_singular( $this->param_cpt_key ) ):
				wp_redirect( home_url( $this->param_redirect_single ), 301 );
				exit;
			endif;
		endif;
	}

	/**
	 * Remove post type default columns if is applicable
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function wpss_remove_default_columns( $columns ) {

		if ( $this->param_remove_cpt_columns ):
			if ( is_array( $this->param_remove_cpt_columns ) ):
				foreach ( $this->param_remove_cpt_columns as $column ):
					unset( $columns[ $column ] );
				endforeach;
			else:
				unset( $columns['date'] );
				unset( $columns['post_views_column'] );
			endif;
		endif;

		return $columns;
	}

	/**
	 * Filter to remove default post type columns
	 */
	public function wpss_remove_default_columns_filter() {
		add_filter( 'manage_' . $this->param_cpt_key . '_posts_columns', array(
			$this,
			'wpss_remove_default_columns'
		) );
	}

	/**
	 * Detect custom admin script to post type "new post", see folder "cpt-templates/cpt-scripts"
	 */
	public function wpss_cpt_admin_scripts_js() {
		$js = _WPSS_THEME_DIR . '/cpt-templates/cpt-scripts/' . $this->param_cpt_key . '.js';
		global $post_type;
		if ( $this->param_cpt_key == $post_type ):
			if ( file_exists( $js ) ):
				wp_enqueue_script( $this->param_cpt_key . '-admin-script', _WPSS_THEME_DIR_URI . '/cpt-templates/cpt-scripts/' . $this->param_cpt_key . '.js' );
			endif;
			if ( $this->param_custom_cpt_js ):
				foreach ( (array) $this->param_custom_cpt_js as $file_js ):
					wp_enqueue_script( $file_js . '-admin-script', _WPSS_THEME_DIR_URI . '/js/' . $file_js . '.js' );
				endforeach;
			endif;
		endif;
	}

	/**
	 * Detect custom admin style to post type "new post", see folder "cpt-templates/cpt-scripts"
	 */
	public function wpss_cpt_admin_scripts_css() {
		$css = _WPSS_THEME_DIR . '/cpt-templates/cpt-scripts/' . $this->param_cpt_key . '.css';
		global $post_type;
		if ( $this->param_cpt_key == $post_type ):
			if ( file_exists( $css ) ):
				wp_enqueue_style( $this->param_cpt_key . '-admin-style', _WPSS_THEME_DIR_URI . '/cpt-templates/cpt-scripts/' . $this->param_cpt_key . '.css' );
			endif;
			if ( $this->param_custom_cpt_css ):
				foreach ( (array) $this->param_custom_cpt_css as $file_css ):
					wp_enqueue_script( $file_css . '-admin-script', _WPSS_THEME_DIR_URI . '/css/' . $file_css . '.css' );
				endforeach;
			endif;
		endif;
	}

	/**
	 * Define a custom menu to post type archive and singular templates
	 */
	public function wpss_cpt_custom_menu() {
		if ( $this->param_cpt_custom_menu ):
			$this->param_cpt = $this->param_cpt_key;
			$this->param_cap = $this->param_add_cap;
			$this->wpss_menu_cpt();
			$this->wpss_menu_metaboxes();
		endif;
	}

	public function wpss_cpt_custom_menu_frontend() {
		if ( $this->param_cpt_custom_menu ):
			if ( is_singular( $this->param_cpt_custom_menu ) || is_post_type_archive( $this->param_cpt_custom_menu ) || is_tax( $this->param_cpt_custom_menu ) ):
				$this->wpss_menu_frontend();
			endif;
		endif;
	}

	/**
	 * Define a custom sidebar to post type archive and singular templates
	 */
	public function wpss_cpt_custom_sidebar() {
		if ( $this->param_cpt_custom_sidebar ):
			$this->param_sdb_cpt = $this->param_cpt_key;
			$this->param_sdb_cap = $this->param_add_cap;
			$this->wpss_sidebar_cpt();
			$this->wpss_sidebar_metaboxes();
		endif;
	}

	/**
	 * Embed custom sidebar on custom post types singular and archive templates
	 */
	public function wpss_cpt_custom_sidebar_frontend() {

		if ( $this->param_cpt_custom_sidebar ):
			if ( is_singular( $this->param_cpt_custom_sidebar ) || is_post_type_archive( $this->param_cpt_custom_sidebar ) || is_tax( $this->param_cpt_custom_sidebar ) ):
				global $wpss_custom_sidebar;
				$wpss_custom_sidebar = true;
				$this->wpss_sidebar_frontend();
			endif;
		endif;
	}

	/**
	 * Add support to post type contact form
	 */
	public function wpss_cpt_contact_form() {
		if ( $this->param_cpt_contact_form ):
			$contact            = new WPSSCptContact();
			$contact->param_cpt = $this->param_cpt_key;
			$contact->param_cap = $this->param_add_cap;
			$contact->wpss_cpt_contact_init();
		endif;
	}

	/**
	 * Custom post type admin notices
	 */
	public function wpss_cpt_admin_notices() {
		if ( $this->param_cpt_admin_notice ):
			$admin_screen = get_current_screen()->id;
			if ( $admin_screen === 'edit-' . $this->param_cpt_key ):
				add_action( 'admin_footer', array( $this, 'wpss_cpt_admin_footer_js' ) );
			endif;
		endif;
	}

	public function wpss_cpt_admin_notice_message() {
		return "<div style='background:#b9f6ca; color:#558b2f; border:1px solid #a5d6a7; margin:30px auto 0 auto; padding:30px; font-size:1.2rem; max-width:880px; text-align:center;'>" . $this->param_cpt_admin_notice . "</div>";
	}

	public function wpss_cpt_admin_footer_js() {
		$message = self::wpss_cpt_admin_notice_message();
		?>
        <script>
            jQuery(function ($) {
                $('#posts-filter').after("<?=$message;?>");
            });
        </script>
		<?php
	}

	/**
	 * Flush rewrite rules
	 */
	public function wpss_flush_rewrite_rules() {
		flush_rewrite_rules();
	}

}