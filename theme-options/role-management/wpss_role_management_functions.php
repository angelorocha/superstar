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

/**
 * Get user and caps
 */
function wpss_manage_capabilities() {
	?>
    <form method="post">
        <label for="user_selected"><?php _e('Select User: ','wpss'); ?></label>
        <select name="user_selected" id="user_selected" required="required" onchange="this.form.submit()">
            <option value="">------</option>
			<?php
			foreach ( WPSSquery::wpss_query_users() as $key => $user ):
				$selected = '';
				if ( isset( $_POST['user_selected'] ) ):
					$selected = ( $_POST['user_selected'] == $key ? " selected" : '' );
				endif;
				echo "<option value='$key'$selected>$user</option>";
			endforeach;
			?>
        </select>

		<?php
		if ( isset( $_POST['user_selected'] ) ):
			global $wp_roles;
			$primitive_roles = array(
				'editor',
				'author',
				'contributor',
			);
			$user            = get_userdata( $_POST['user_selected'] );
			$roles           = get_editable_roles();
			$user_roles      = $user->roles;

			$groups_select = $user_roles;

			if ( isset( $_POST['wpss-change-user-roles'] ) ):
				if ( ! is_null( $_POST['wpss_user_role'] ) ):
					$gp_remove = array_diff( array_unique( $groups_select ), $_POST['wpss_user_role'] );
					foreach ( $_POST['wpss_user_role'] as $group ):
						if ( ! in_array( $group, $groups_select ) ):
							$groups_select[] = $group;
						endif;
						if ( $gp_remove ):
							foreach ( $gp_remove as $key_gp ):
								unset( $groups_select[ array_search( $key_gp, $groups_select ) ] );
							endforeach;
						endif;
					endforeach;
				else:
					$groups_select = array();
				endif;
			endif;
			$groups = implode( ", ", $groups_select );

			echo "<hr>";
			echo "<ul>";
			echo "<li><strong>ID:</strong> " . $user->ID . "</li>";
			echo "<li><strong>".__('User Login: ','wpss').":</strong> " . $user->user_login . "</li>";
			echo "<li><strong>".__('Display Name: ','wpss')."</strong> " . $user->display_name . "</li>";
			echo "<li><strong>".__('User Email: ','wpss')."</strong> " . $user->user_email . "</li>";
			echo "<li><strong>".__('Registered on: ','wpss')."</strong> " . date( __('Y-m-d H:i','wpss'), strtotime( $user->user_registered ) ) . "</li>";
			echo "<li><strong>".__('Roles: ','wpss')."</strong> $groups</li>";
			echo "</ul>";
			echo "<hr>";

			if ( isset( $_POST['wpss-change-user-roles'] ) ):
				$user_id      = $_POST['user_selected'];
				$roles_action = $_POST['wpss_user_role'];

				$user_object = new WP_User( $user_id );

				if ( is_null( $roles_action ) ):
					echo "<div class='wpss-alert-warning'><strong>".__('Removed Capabilities: ','wpss')."</strong> " . implode( ', ', $user_roles ) . "</div>";
					foreach ( $user->roles as $key => $remove_to_role ):
						$user_object->remove_role( $remove_to_role );
					endforeach;
				else:
					echo "<div class='wpss-alert-success'>";
					echo "<strong>".__('Added Capabilities: ','wpss')."</strong>" . implode( ',', $roles_action );

					foreach ( $roles_action as $key => $add_role ):

						if ( ! in_array( $add_role, $user_roles ) ):
							$user_object->add_role( $add_role );
						endif;

						foreach ( $user_roles as $remove ):
							if ( ! in_array( $remove, $roles_action ) ):
								$user_object->remove_role( $remove );
							endif;
						endforeach;

					endforeach;
					echo "</div>";
				endif;

			endif;

			echo "<table class='wpss-table'>";
			echo "<thead><tr><th colspan='2'>".__('Add or Remove User Capabilities', 'wpss')."</th></tr></thead>";
			foreach ( $roles as $key => $role ):
				$role_id     = $key;
				$role_name   = $wp_roles->roles[ $key ]['name'];
				$get_checked = $user_roles;
				if ( isset( $_POST['wpss-change-user-roles'] ) ):
					if ( ! is_null( $_POST['wpss_user_role'] ) ):
						$to_remove = array_diff( array_unique( $get_checked ), $_POST['wpss_user_role'] );
						foreach ( $_POST['wpss_user_role'] as $to_check ):
							$get_checked[] = $to_check;
							if ( $to_remove ):
								foreach ( $to_remove as $key_remove ):
									unset( $get_checked[ array_search( $key_remove, $get_checked ) ] );
								endforeach;
							endif;
						endforeach;
					else:
						$get_checked = array();
					endif;
				endif;
				$checked = ( in_array( $key, $get_checked ) ? ' checked' : '' );

				if ( ! in_array( $key, $primitive_roles ) ):
					echo "<tr><td style='width:100%'>$role_name</td><td><input type='checkbox' name='wpss_user_role[]' value='$role_id'$checked></td></tr>";
				endif;
			endforeach;
			echo "<tfoot><tr class='text-center'><td colspan='2'><input type='submit' name='wpss-change-user-roles' value='Salvar Alterações' class='wpss-button'></td></tr></tfoot>";
			echo "</table>";
		endif;
		?>

    </form>
	<?php
}

/**
 * Roles management
 */
function wpss_manage_roles() {
	$roles           = get_editable_roles();
	$primitive_roles = array(
		'administrator',
		'editor',
		'author',
		'contributor',
		'subscriber',
	);

    if ( !isset( $_GET['wpss_role_delete'] ) ): ?>
    <form method="post">
        <label for="add_new_role"><?php _e('Role name: ','wpss'); ?></label>
        <input type="text" name="add_new_role" id="add_new_role" value="" placeholder="<?php _e('Type role name','wpss'); ?>" required="required">
        <input class="wpss-button" type="submit" value="<?php _e('Add Role','wpss'); ?>">
    </form>
    <hr>
	<?php
    endif;
	global $wp_roles;
	$post_types    = get_post_types();
	if ( isset( $_POST['add_new_role'] ) && ! empty( $_POST['add_new_role'] ) ):
        $sanitize_name = sanitize_text_field($_POST['add_new_role']);
	    if($sanitize_name === ''):
            $new_role = null;
	    else:
            $new_role = add_role( sanitize_title( preg_replace( '/\s+/', '', $_POST['add_new_role'] ) ), $sanitize_name, array( 'read' => true ) );
        endif;
		if ( ! is_null( $new_role ) ):
            echo "<div class='wpss-alert-success'>" . __('Role successfully created', 'wpss') . "</div>";
		else:
			echo "<div class='wpss-alert-warning'>" . __('Something went wrong, try again', 'wpss') . "</div>";
		endif;
		echo "<hr>";
	endif;

	/*** Delete role */
	if ( isset( $_GET['wpss_role_delete'] ) ):
		$delete_role = $_GET['wpss_role_delete'];
		if ( ! isset( $_POST['wpss_role_delete'] ) ):
			?>
            <form method="post">
                <p class="text-center">
                    <strong><?php _e('Delete Role', 'wpss'); ?> <?= $delete_role; ?></strong><br>
                    <small style="color:#F00;"><?php _e('This action cannot be undone', 'wpss'); ?></small>
                    <br><br>
                    <input type="submit" name="wpss_role_delete" class="wpss-button danger" value="<?php _e('Delete', 'wpss'); ?>">
                </p>
            </form>
		<?php
		endif;
		if ( isset( $_POST['wpss_role_delete'] ) ):
			$role_name = $wp_roles->roles[ $delete_role ]['name'];
            echo "<div class='wpss-alert-warning'><strong>$role_name</strong> " . __(' successfully deleted!', 'wpss') . "</div>";
			remove_role( $delete_role );
		endif;
	endif;

	/*** Edit role capabilities */
	if ( isset( $_GET['wpss_role_edit'] ) ):
		$edit_role = $_GET['wpss_role_edit'];
		$role_name = $wp_roles->roles[ $edit_role ]['name'];
		?>
        <form method="post">
            <label><?php _e('Select Role Capabilities: ','wpss'); ?></label>

			<?php
			if ( isset( $_POST['wpss_add_cap_to_role'] ) ):

				$add_caps  = $_POST['wpss_add_cpt_cap'];
				$role_caps = get_role( $edit_role )->capabilities;

				if ( ! is_null( $add_caps ) ):
					echo "<div class='wpss-alert-success mt-10 mb-10'>";
					$to      = array();
					$removed = array();
					foreach ( $add_caps as $to_role ):
						if ( ! get_role( $edit_role )->has_cap( $to_role ) && ! is_null( $add_caps ) ):
							get_role( $edit_role )->add_cap( $to_role );
							$to[] = $to_role;
						endif;

						foreach ( $role_caps as $key_remove => $remove_cap ):
							if ( ! in_array( $key_remove, $add_caps ) ):
								get_role( $edit_role )->remove_cap( $key_remove );
								$removed[] = $key_remove;
							endif;
						endforeach;

					endforeach;

					if ( ! empty( $to ) ):
                        echo "<p><strong>" . __('Added Capabilities: ', 'wpss') . "</strong>" . implode(', ', $to) . "</p>";
					endif;
					if ( ! empty( $removed ) ):
						echo "<p><strong>" . __('Removed Capabilities: ', 'wpss') . "</strong>" . implode( ', ', array_unique( $removed ) ) . "</p>";
					endif;

					if ( empty( $removed ) && empty( $to ) ):
						echo "<p class='text-center'><strong>" . __('No Changes...', 'wpss') . "</strong></p>";
					endif;

					echo "</div>";
				endif;

				if ( is_null( $add_caps ) ):
					foreach ( $post_types as $cpt_roles ):
						foreach ( get_post_type_object( $cpt_roles )->cap as $remove_cap ):
							get_role( $edit_role )->remove_cap( $remove_cap );
						endforeach;
					endforeach;
					echo "<div class='wpss-alert-warning'><p class='text-center'><strong>" . __('All capabilities has been removed...', 'wpss') . "</strong></p></div>";
				endif;

			endif;
			?>
            <p class="text-right mt-10">
                <input type="submit" value="Salvar" class="wpss-button" name="wpss_add_cap_to_role">
            </p>
            <table class="wpss-table">
                <thead>
                <tr>
                    <th colspan="2" class="text-center"><?= $role_name; ?></th>
                </tr>
                </thead>
				<?php
				$special_perms = array(
					'activate_plugins',
					'edit_dashboard',
					'list_users',
					'manage_links',
					'manage_options',
					'moderate_comments',
					'promote_users',
					'remove_users',
					'switch_themes',
					'unfiltered_upload',
					'update_core',
					'update_plugins',
					'edit_theme_options',
					'update_themes',
					'install_plugins',
					'install_themes',
					'delete_themes',
					'delete_plugins',
					'edit_plugins',
					'edit_themes',
					'edit_files',
					'edit_users',
					'create_users',
					'delete_users',
					'unfiltered_html',
				);
				?>
                <tr>
                    <td style="vertical-align:top;">
                        <p><strong><?php _e('Special Capabilities','wpss'); ?></strong></p>
                        <small>
                            <?php _e('These capabilities only apply in special cases', 'wpss'); ?>
                        </small>
                    </td>
                    <td>
                        <p class="text-center mb-10">
                            <a href="javascript:" class="wpss-button" id="wpss-show-op">
                                <span class="wpss-show-btn"><?php _e('Show', 'wpss'); ?></span>
                                <span class="wpss-show-btn" style="display: none"><?php _e('Hide', 'wpss'); ?></span>
                            </a>
                        </p>
                        <ul class="wpss_hide" id="special_perms">
							<?php
							foreach ( $special_perms as $perm ):
								$ep_checked = "";
								if ( get_role( $edit_role )->has_cap( $perm ) ):
									$ep_checked = " checked";
								endif;
								echo "<li>";
								echo "<label for='$perm'>";
								echo "<input type='checkbox' name='wpss_add_cpt_cap[]' id='$perm' value='$perm'$ep_checked> $perm";
								echo "</label>";
								echo "</li>";
							endforeach;
							?>
                        </ul>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="text-center"><strong><?php _e('Taxonomies', 'wpss'); ?></strong></td>
                </tr>

				<?php
				$tax_unset      = array(
					'nav_menu',
					'link_category',
					'post_format',
				);
				$get_taxonomies = array_diff( get_taxonomies(), $tax_unset );
				?>
				<?php foreach ( $get_taxonomies as $taxonomy ):
					$tax_caps = (array) get_taxonomy( $taxonomy )->cap;
					$field_id = get_taxonomy( $taxonomy )->name;
					?>
                    <tr>
                        <td style="vertical-align:top;">
                            <label for="<?= $field_id; ?>">
                                <input type="checkbox" id="<?= $field_id; ?>">
								<?= get_taxonomy( $taxonomy )->label; ?>
                            </label>
                        </td>
                        <td>
                            <script>
                                jQuery(function ($) {
                                    $('#<?=$field_id?>').click(function () {
                                        $('.<?=$field_id?>').prop('checked', this.checked);
                                    });
                                });
                            </script>
                            <ul>
								<?php
								foreach ( $tax_caps as $tax_cap ):
									$tax_checked = "";
									if ( get_role( $edit_role )->has_cap( $tax_cap ) ):
										$tax_checked = " checked";
									endif;

									echo "<li>";
									echo "<label for='$tax_cap'>";
									echo "<input type='checkbox' name='wpss_add_cpt_cap[]' id='$tax_cap' value='$tax_cap' class='$field_id'$tax_checked> $tax_cap";
									echo "</label>";
									echo "</li>";
								endforeach;
								?>
                            </ul>
                        </td>
                    </tr>
				<?php endforeach; ?>

                <tr>
                    <td colspan="2" class="text-center"><strong><?php _e('Post Types', 'wpss'); ?></strong></td>
                </tr>
				<?php
				/*** Get all post types and your caps */
				foreach ( $post_types as $k_posts => $posts ):
					?>
                    <script>
                        jQuery(function ($) {
                            $('#check-<?=$posts?>').click(function () {
                                $('.<?=$posts?>').prop('checked', this.checked);
                            });
                        });
                    </script>
					<?php
					if ( get_post_type_object( $posts )->public ):
						echo "<tr>";
						echo "<td style='vertical-align:top; width:30%;'>";
						echo "<label for='check-$posts'>";
						echo "<input type='checkbox' id='check-$posts' class='$posts'> " . get_post_type_object( $posts )->label . "</label>";
						echo "</td>";
						echo "<td>";
						echo "<ul>";
						$caps_list = (array) get_post_type_object( $posts )->cap;

						foreach ( $caps_list as $cap ):
							$checked = "";
							if ( get_role( $edit_role )->has_cap( $cap ) ):
								$checked = " checked";
							endif;
							echo "<li>";
							if ( $k_posts == 'attachment' ):
								if ( ! isset( $caps_list[ $cap ] ) ):
									echo "<label for='$cap'><input type='checkbox' name='wpss_add_cpt_cap[]' value='$cap' id='$cap' class='$posts'$checked>";
									echo " $cap</label>";
								endif;
							else:
								echo "<label for='$cap'><input type='checkbox' name='wpss_add_cpt_cap[]' value='$cap' id='$cap' class='$posts'$checked>";
								echo " $cap</label>";
							endif;
							echo "</li>";
						endforeach;
						echo "</ul>";
						echo "</td>";
						echo "</tr>";
					endif;
				endforeach;
				/*** End of post types and caps */
				?>
            </table>
            <p class="text-right">
                <input type="submit" value="<?php _e('Save', 'wpss'); ?>" class="wpss-button" name="wpss_add_cap_to_role">
            </p>
        </form>
	<?php
	endif;

	if ( ! isset( $_GET['wpss_role_edit'] ) && ! isset( $_GET['wpss_role_delete'] ) && ! isset( $_POST['add_new_role'] ) ):
		$request = $_SERVER['REQUEST_URI'];
		echo "<table class='wpss-table'>";
		echo "<tr>";
		echo "<td colspan='3' class='text-center'><strong>".__('Available Custom Roles', 'wpss')."</strong></td>";
		echo "</tr>";
		foreach ( $roles as $key => $role ):
			if ( ! in_array( $key, $primitive_roles ) ):
				echo "<tr>";
				echo "<td>" . $role['name'] . "</td>";
				echo "<td width='20'><a href='$request&wpss_role_edit=$key' title='' class='wpss-button'>".__('Edit', 'wpss')."</a></td>";
				echo "<td width='20'><a href='$request&wpss_role_delete=$key' title='' class='wpss-button danger'>".__('Delete', 'wpss')."</a></td>";
				echo "</tr>";
			endif;
		endforeach;
		echo "</table>";
	endif;
}