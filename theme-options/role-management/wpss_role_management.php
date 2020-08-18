<?php
/**
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2019
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

add_action( 'admin_menu', 'wpss_user_role_management' );
function wpss_user_role_management() {
	add_submenu_page(
		'users.php',
		__('Role Management','wpss'),
        __('Role Management','wpss'),
		'administrator',
		'wpss-role-management',
		'wpss_user_role_management_cotent',
		6
	);
}

function wpss_user_role_management_cotent() {
	$is_admin_menu = $_GET['page'] === 'wpss-role-management';

	if ( $is_admin_menu ):
        wp_enqueue_script( 'admin-select2', _WPSS_JS_DIR . 'select2.js', array( 'jquery' ), _WPSS_FILE_VERSION, true );
        wp_enqueue_style( 'admin-select2', _WPSS_CSS_DIR . 'select2.css', '', _WPSS_FILE_VERSION, 'all' );
        wp_enqueue_style( 'user-management', _WPSS_ASSETS_DIR . 'theme-options/css/user-management.css', '', _WPSS_FILE_VERSION, 'all' );
        wp_enqueue_script( 'user-management', _WPSS_ASSETS_DIR . 'theme-options/js/user-management.js', array( 'jquery' ), _WPSS_FILE_VERSION, true );
	endif;

	$count      = 0;
	$admin_home = menu_page_url( 'wpss-role-management', false );
	$menu_nav   = array(
		'caps'    => 'Add or Remove Role',
		'roles'   => 'Roles List',
	);
	?>
    <div class="wpss-admin-container">
        <h3><?php _e('Role Management','wpss'); ?></h3>

        <div class="wpss-admin">

            <ul class="wpss-admin-nav">
				<?php foreach ( $menu_nav as $key => $link ):
					$count ++;

					if ( ! isset( $_GET['screen'] ) && $count === 1 ):
						$active = 'active';
					else:
						$active = ( $key === $_GET['screen'] ? 'active' : '' );
					endif;
					?>
                    <li id="<?= $key; ?>" class="<?= $active; ?>">
                        <a href="<?= $admin_home . '&screen=' . $key; ?>" title="<?= $link; ?>">
							<?= $link; ?>
                        </a>
                    </li>
				<?php endforeach; ?>
            </ul>

            <div class="wpss-admin-caps wpss-tab">
				<?php wpss_manage_capabilities(); ?>
            </div><!-- .wpss-admin-users -->

            <div class="wpss-admin-roles wpss-tab">
				<?php wpss_manage_roles(); ?>
            </div><!-- .wpss-admin-users -->
        </div>
    </div>
	<?php
}