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

add_action( 'admin_menu', 'wpss_content_statistics' );
function wpss_content_statistics() {
	add_menu_page(
		__('Post Statistics','wpss'),
        __('Post Statistics','wpss'),
		'administrator',
		'wpss-content-statistics',
		'wpss_content_statistics_content',
		'dashicons-chart-bar',
        59
	);
}

function wpss_content_statistics_content() {
	$action = menu_page_url( 'wpss-content-statistics', false );
    $is_admin_menu = $_GET['page'] === 'wpss-content-statistics';
    if ( $is_admin_menu ):
        wp_enqueue_style( 'wpss-post-views', _WPSS_ASSETS_DIR . 'theme-options/css/post-views.css', '', _WPSS_FILE_VERSION, 'all' );
    endif;
	?>
    <div class="wpss-container">
        <h1 class="header"><?php _e('Post Statistics','wpss'); ?></h1>
        <form method="get" action="<?= $action; ?>" class="wpss-form">
            <input type="hidden" name="page" value="wpss-content-statistics">
            <div class="wpss-col">
                <label for="wpss_cpt_select"><?php _e('Post Type:','wpss'); ?></label>
                <select name="wpss_cpt_select" id="wpss_cpt_select">

					<?php
					foreach ( wpss_get_statistics_post_type() as $key => $cpt ):
						$selected = ( $_GET['wpss_cpt_select'] === $key ? " selected" : "" );
						echo "<option value='$key'$selected>$cpt</option>";
					endforeach;
					?>
                    <option value=""><?php _e('All','wpss'); ?></option>
                </select>
            </div>

            <div class="wpss-col">
				<?php
				$get_start_date = ( ! empty( $_GET['wpss_start_date'] ) ? $_GET['wpss_start_date'] : '' );
				?>
                <label><?php _e('Start Date:','wpss'); ?></label>
                <input type="date" name="wpss_start_date" autocomplete="off" value="<?= $get_start_date; ?>">
            </div>
            <div class="wpss-col">
				<?php
				$get_end_date = ( ! empty( $_GET['wpss_end_date'] ) ? $_GET['wpss_end_date'] : '' );
				?>
                <label><?php _e('End Date','wpss'); ?></label>
                <input type="date" name="wpss_end_date" autocomplete="off" value="<?= $get_end_date; ?>">
            </div>
            <div class="wpss-col">
                <label><?php _e('Limit:','wpss'); ?></label>
                <select name="wpss_limit">
					<?php
					$limit_op = array( 10, 30, 60, 90, 'All' );
					foreach ( $limit_op as $limit ):
						$selected = ( $_GET['wpss_limit'] == $limit ? ' selected' : '' );
						echo "<option value='$limit'$selected>$limit</option>";
					endforeach;
					?>
                </select>
            </div>
            <div class="wpss-col">
                <label><?php _e('Order:','wpss'); ?></label>
                <select name="wpss_order_by">
					<?php
					$order_by_op = array( 'views' => 'Views', 'post_date' => __('Date','wpss') );
					foreach ( $order_by_op as $key => $op ):
						$selected = ( $_GET['wpss_order_by'] === $key ? ' selected' : '' );
						echo "<option value='$key'$selected>$op</option>";
					endforeach;
					?>
                </select>
            </div>
            <div class="wpss-col wpss-align-self-end">
                <select name="wpss_order">
					<?php
					$order_op = array( 'DESC' => __('DESC','wpss'), 'ASC' => __('ASC','wpss') );
					foreach ( $order_op as $key => $op ):
						$selected = ( $_GET['wpss_order'] === $key ? ' selected' : '' );
                        echo "<option value='$key'$selected>$op</option>";
					endforeach;
					?>
                </select>
            </div>
            <div class="wpss-col wpss-align-self-end text-right">
                <input type="submit" value="<?php _e('Submit','wpss'); ?>" class="">
            </div>
        </form>

		<?php

		$cpt        = ( ! isset( $_GET['wpss_cpt_select'] ) ? 'post' : sanitize_text_field($_GET['wpss_cpt_select'] ));
        $start_date = sanitize_text_field($_GET['wpss_start_date']);
		$end_date   = sanitize_text_field($_GET['wpss_end_date']);
		$order_by   = ( ! isset( $_GET['wpss_order_by'] ) ? 'views' : sanitize_text_field($_GET['wpss_order_by'] ));
		$order      = ( ! isset( $_GET['wpss_order'] ) ? 'DESC' : sanitize_text_field($_GET['wpss_order'] ));
		$limit      = ( ! isset( $_GET['wpss_limit'] ) ? 10 : sanitize_text_field($_GET['wpss_limit'] ));

		echo "<table class='wpss-table'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th width='120'>".__('Post Type','wpss')."</th>";
		echo "<th>".__('Title','wpss')."</th>";
		echo "<th width='120'>".__('Date','wpss')."</th>";
		echo "<th width='80'>".__('URL','wpss')."</th>";
		echo "<th width='70'>".__('Views','wpss')."</th>";
		echo "</tr>";
		echo "</thead>";
		foreach ( wpss_statistics_query( $cpt, $start_date, $end_date, $order_by, $order, $limit ) as $val ):
			$edit_link = '<a href="' . get_edit_post_link( $val['id'] ) . '" title="'.__('Edit','wpss').'" class="float-right">'.__('Edit','wpss').'</a>';
			echo "<tr>";
			echo "<td>" . get_post_type_object( $val['post_type'] )->label . "</td>";
			echo "<td>[" . $val['id'] . '] ' . $val['title'] . $edit_link . "</td>";
			echo "<td class='text-center'>" . $val['date'] . "</td>";
			echo "<td><a href='" . $val['url'] . "' title='".__('Go to URL','wpss')."' target='_blank' class='wpss-button'>".__('URL','wpss')."</a></td>";
			echo "<td class='text-center'>" . $val['views'] . "</td>";
			echo "</tr>";
		endforeach;
		echo "</table>";
		?>
    </div>
	<?php
}

function wpss_get_statistics_post_type() {
	$cpt_select = array();
	foreach ( get_post_types() as $post_type ):
		if ( get_post_type_object( $post_type )->public ):
			$cpt_select[ $post_type ] = get_post_type_object( $post_type )->label;
		endif;
	endforeach;

	return $cpt_select;
}

function wpss_statistics_query( $post_type, $start_date, $end_date, $order_by, $order, $limit ) {

	$limit = ( $limit !== 'all' ? " LIMIT $limit" : "" );

	global $wpdb;
	$query = "SELECT ";
	$query .= "$wpdb->posts.ID AS 'id',";
	$query .= "$wpdb->posts.post_type AS 'post_type',";
	$query .= "$wpdb->posts.post_title AS 'title',";
	$query .= "DATE_FORMAT($wpdb->posts.post_date, \"%d-%m-%Y %H:%i\") AS 'date',";
	$query .= "$wpdb->posts.post_name AS 'url',";
	$query .= "$wpdb->postmeta.meta_value AS 'views'";
	$query .= "FROM $wpdb->posts ";
	$query .= "INNER JOIN ";
	$query .= "$wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID ";
	$query .= "WHERE ";
	$query .= "$wpdb->posts.post_status = 'publish' ";

	if ( ! empty( $post_type ) ):
		$query .= "AND ";
		$query .= "$wpdb->posts.post_type = '$post_type' ";
	endif;

	$query .= "AND ";
	$query .= "$wpdb->postmeta.meta_key = '_wpss_post_views' ";

	if ( ! empty( $start_date ) || ! empty( $end_date ) ):

		if(empty($start_date)):
            $start_date = date( 'Y-m-d', strtotime( $end_date . '-1 day' ) ). ' 00:00';
		else:
            $start_date = $start_date . ' 00:00';
        endif;

        if(empty($end_date)):
            $end_date = current_time( 'Y-m-d' ) . ' 23:59';
        else:
            $end_date = $end_date . ' 23:59';
        endif;

		$query      .= "AND ";
		$query      .= "$wpdb->posts.post_date BETWEEN '$start_date' AND '$end_date' ";
	endif;

	if ( $order_by !== 'views' ):
		$query .= "ORDER BY $wpdb->posts.$order_by $order $limit;";
	else:
		$query .= "ORDER BY ABS($wpdb->postmeta.meta_value) $order $limit;";
	endif;

	return $wpdb->get_results( $query, ARRAY_A );
}