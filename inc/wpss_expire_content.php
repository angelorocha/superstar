<?php
/**
 * @param $id
 * @param $post_type
 * @param $metabox_name
 * @param $metabox_op_title
 * @param bool $expire_meta
 * @param string $context
 *
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

function wpss_expire_metabox( $id, $post_type, $metabox_name, $metabox_op_title, $expire_meta = true, $context = 'normal' ) {
	$expire                = new WPSSMetaBox();
	$expire->metabox_id    = $id;
	$expire->metabox_title = $metabox_name;
	$expire->context       = $context;
	$expire->post_type     = $post_type;
	$expire->fields        = array(
		array(
			'name'    => $metabox_op_title,
			'id'      => 'wpss_expire_content',
			'type'    => 'radio_inline',
			'options' => array(
				'yes' => __('Yes', 'wpss'),
				'no'  => __('No', 'wpss'),
			),
			'default' => 'no'
		),
		array(
			'name'        => __("Select Date", "wpss"),
			'id'          => 'wpss_expire_date',
			'type'        => 'text_datetime_timestamp',
			'date_format' => 'd-m-Y',
			'time_format' => 'H:i',
			'default'     => current_time( 'd-m-Y H:i' ) . ( '+1 day' )
		),
		array(
			'name'    => '',
			'id'      => 'wpss_expire_type',
			'type'    => 'hidden',
			'default' => ( $expire_meta ? 'meta' : 'post' )
		)
	);
}

function wpss_get_posts_to_expire() {
	global $wpdb;
	$query    = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'wpss_expire_content' AND meta_value = 'yes';";
	$post_ids = array();

	foreach ( $wpdb->get_results( $query, ARRAY_A ) as $id ):
		$post_ids[] = (int) implode( $id );
	endforeach;

	return $post_ids;
}

function wpss_expire_content() {
	$current_time = current_time( 'Y-m-d H:i' );

	if ( ! empty( wpss_get_posts_to_expire() ) ):

		foreach ( wpss_get_posts_to_expire() as $post_id ):

			$expire_time = date( 'Y-m-d H:i', get_post_meta( $post_id, 'wpss_expire_date', true ) );

			if ( $expire_time <= $current_time ):

				/*** Expire Slide */
				if ( metadata_exists( 'post', $post_id, 'wpss_slide_show' ) ):
					update_post_meta( $post_id, 'wpss_slide_show', 'no' );
				endif;

				/*** Expire post types */
				if ( metadata_exists( 'post', $post_id, 'wpss_expire_type' ) ):
					if ( get_post_meta( $post_id, 'wpss_expire_type', true ) === 'post' ):
						wp_update_post(
							array(
								'ID'          => $post_id,
								'post_status' => 'draft'
							)
						);
					endif;
				endif;

			endif;

		endforeach;

	endif;
}

add_action( 'wpss_expire_content_hook', 'wpss_expire_content' );
if ( ! wp_next_scheduled( 'wpss_expire_content_hook' ) ) {
	wp_schedule_event( time(), 'minute', 'wpss_expire_content_hook' );
}