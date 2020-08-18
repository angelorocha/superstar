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

final class WPSSquery {

	/**
	 * @param $post_type
	 * @param int $limit
	 * @param string $callback_content
	 * @param bool $pagination
	 * @param array $params
	 * @param bool $post_views
	 * @param string $no_posts_msg
	 */
	public static function wpss_make_query( $post_type, $limit = 12, $callback_content = '', $pagination = false, $params = array(), $post_views = false, $no_posts_msg = '' ) {
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $limit
		);
		if ( $pagination ):
			$args['paged'] = $paged;
		endif;

		$wpss_query = new WP_Query( array_merge( $args, $params ) );

		if ( $wpss_query->have_posts() ):

			while ( $wpss_query->have_posts() ): $wpss_query->the_post();
				if ( $post_views ):
					wpss_set_post_views();
				endif;
				call_user_func( $callback_content );
			endwhile;

			wp_reset_postdata();

			if ( $pagination ):
				echo wpss_pagination( $wpss_query );
			endif;

		else:
			if ( ! empty( $no_posts_msg ) ):
				echo __( '<h4 class="text-center">No information yet...</h4>', 'wpss' );
			endif;
		endif;

	}

	public static function wpss_count_posts( $post_type, $query = '' ) {
		global $wpdb;

		if ( empty( $query ) ):
			$count = "SELECT count(*) FROM $wpdb->posts WHERE post_type = '$post_type' AND post_status = 'publish'";
		else:
			$count = $query;
		endif;

		return (int) $wpdb->get_var( $count );
	}

	public static function wpss_get_query_ids( $post_type, $limit = '' ) {
		global $wpdb;
		$limit    = ( ! empty( $limit ) ? " LIMIT $limit" : '' );
		$query    = "SELECT ID FROM $wpdb->posts WHERE post_type = '$post_type' AND post_status = 'publish'$limit";
		$post_ids = array();
		foreach ( $wpdb->get_results( $query, ARRAY_A ) as $id ):
			$post_ids[] = (int) implode( $id );
		endforeach;

		return $post_ids;
	}

	public static function wpss_get_post_ids( $post_type, $limit = '' ) {

		global $wpdb;
		$limit = ( ! empty( $limit ) ? " LIMIT $limit" : '' );

		$post_ids  = array();
		$get_posts = $wpdb->get_results( "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = '$post_type' AND post_status = 'publish'$limit", ARRAY_A );

		foreach ( $get_posts as $key => $post ):
			$post_ids[ $post['ID'] ] = $post['post_title'];
		endforeach;

		return $post_ids;
	}

	public static function wpss_query_users() {
		global $wpdb;
		$query = "SELECT ID, user_login, display_name FROM $wpdb->users;";

		$user_list = array();

		foreach ( $wpdb->get_results( $query, ARRAY_A ) as $key => $val ):
			$user_list[ $val['ID'] ] = '[' . $val['user_login'] . '] ' . $val['display_name'];
		endforeach;

		return $user_list;
	}
}