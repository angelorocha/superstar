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

final class WPSSPostsOrder {

	public $objects = array();

	public function __construct() {
		add_action( 'admin_init', array( $this, 'refresh' ) );
		add_action( 'admin_init', array( $this, 'update_options' ) );
		add_action( 'admin_init', array( $this, 'load_script_css' ) );
		add_action( 'wp_ajax_update-menu-order', array( $this, 'update_menu_order' ) );
		add_action( 'wp_ajax_update-menu-order-tags', array( $this, 'update_menu_order_tags' ) );
		add_action( 'pre_get_posts', array( $this, 'scporder_pre_get_posts' ) );
		add_filter( 'get_previous_post_where', array( $this, 'scporder_previous_post_where' ) );
		add_filter( 'get_previous_post_sort', array( $this, 'scporder_previous_post_sort' ) );
		add_filter( 'get_next_post_where', array( $this, 'scporder_next_post_where' ) );
		add_filter( 'get_next_post_sort', array( $this, 'scporder_next_post_sort' ) );
		add_filter( 'get_terms_orderby', array( $this, 'scporder_get_terms_orderby' ), 10, 3 );
		add_filter( 'wp_get_object_terms', array( $this, 'scporder_get_object_terms' ), 10, 3 );
		add_filter( 'get_terms', array( $this, 'scporder_get_object_terms' ), 10, 3 );
		add_action( 'wp_ajax_scporder_dismiss_notices', array( $this, 'dismiss_notices' ) );
	}

	public function dismiss_notices() {
		if ( ! check_admin_referer( 'scporder_dismiss_notice', 'scporder_nonce' ) ) {
			wp_die( 'nok' );
		}
		update_option( 'scporder_notice', '1' );
		wp_die( 'ok' );
	}

	public function _check_load_script_css() {
		$active  = false;
		$objects = $this->get_scporder_options_objects();
		$tags    = $this->get_scporder_options_tags();
		if ( empty( $objects ) && empty( $tags ) ) {
			return false;
		}
		if ( isset( $_GET['orderby'] ) || strstr( $_SERVER['REQUEST_URI'], 'action=edit' ) || strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' ) ) {
			return false;
		}
		if ( ! empty( $objects ) ) {
			if ( isset( $_GET['post_type'] ) && ! isset( $_GET['taxonomy'] ) && in_array( $_GET['post_type'], $objects ) ) {
				$active = true;
			}
		}
		if ( ! empty( $tags ) ) {
			if ( isset( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], $tags ) ) {
				$active = true;
			}
		}

		return $active;
	}

	public function load_script_css() {
		if ( $this->_check_load_script_css() ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'wpss-posts-order', _WPSS_JS_DIR . 'admin-posts-order.js', array( 'jquery' ), null, true );
			wp_enqueue_style( 'wpss-posts-order', _WPSS_CSS_DIR . 'admin-posts-order.css', array(), null );
		}
	}

	public function refresh() {
		global $wpdb;
		$objects = $this->get_scporder_options_objects();
		$tags    = $this->get_scporder_options_tags();
		if ( ! empty( $objects ) ) {
			foreach ( $objects as $object ) {
				$result = $wpdb->get_results( "
                    SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min
                    FROM $wpdb->posts
                    WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
                " );
				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}
				// Here's the optimization
				$wpdb->query( "SET @row_number = 0;" );
				$wpdb->query( "UPDATE $wpdb->posts as pt JOIN (
                  SELECT ID, (@row_number:=@row_number + 1) AS `rank`
                  FROM $wpdb->posts
                  WHERE post_type = '$object' AND post_status IN ( 'publish', 'pending', 'draft', 'private', 'future' )
                  ORDER BY menu_order ASC
                ) as pt2
                ON pt.id = pt2.id
                SET pt.menu_order = pt2.`rank`;" );
			}
		}
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $taxonomy ) {
				$result = $wpdb->get_results( "
                    SELECT count(*) as cnt, max(term_order) as max, min(term_order) as min
                    FROM $wpdb->terms AS terms
                    INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
                    WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
                " );
				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}
				$results = $wpdb->get_results( "
                    SELECT terms.term_id
                    FROM $wpdb->terms AS terms
                    INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
                    WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
                    ORDER BY term_order ASC
                " );
				foreach ( $results as $key => $result ) {
					$wpdb->update( $wpdb->terms, array( 'term_order' => $key + 1 ), array( 'term_id' => $result->term_id ) );
				}
			}
		}
	}

	public function update_menu_order() {
		global $wpdb;
		parse_str( $_POST['order'], $data );
		if ( ! is_array( $data ) ) {
			return false;
		}
		$id_arr = array();
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = $id;
			}
		}
		$menu_order_arr = array();
		foreach ( $id_arr as $key => $id ) {
			$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval( $id ) );
			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->menu_order;
			}
		}
		sort( $menu_order_arr );
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[ $position ] ), array( 'ID' => intval( $id ) ) );
			}
		}
	}

	public function update_menu_order_tags() {
		global $wpdb;
		parse_str( $_POST['order'], $data );
		if ( ! is_array( $data ) ) {
			return false;
		}
		$id_arr = array();
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = $id;
			}
		}
		$menu_order_arr = array();
		foreach ( $id_arr as $key => $id ) {
			$results = $wpdb->get_results( "SELECT term_order FROM $wpdb->terms WHERE term_id = " . intval( $id ) );
			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->term_order;
			}
		}
		sort( $menu_order_arr );
		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update( $wpdb->terms, array( 'term_order' => $menu_order_arr[ $position ] ), array( 'term_id' => intval( $id ) ) );
			}
		}
	}

	public function update_options() {
		global $wpdb;
		if ( ! isset( $_POST['scporder_submit'] ) ) {
			return false;
		}
		check_admin_referer( 'nonce_scporder' );
		$input_options            = array();
		$input_options['objects'] = isset( $_POST['objects'] ) ? $_POST['objects'] : '';
		$input_options['tags']    = isset( $_POST['tags'] ) ? $_POST['tags'] : '';
		update_option( 'scporder_options', $input_options );
		$objects = $this->get_scporder_options_objects();
		$tags    = $this->get_scporder_options_tags();
		if ( ! empty( $objects ) ) {
			foreach ( $objects as $object ) {
				$result = $wpdb->get_results( "
                    SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min
                    FROM $wpdb->posts
                    WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
                " );
				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}
				if ( $object == 'page' ) {
					$results = $wpdb->get_results( "
                        SELECT ID
                        FROM $wpdb->posts
                        WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
                        ORDER BY post_title ASC
                    " );
				} else {
					$results = $wpdb->get_results( "
                        SELECT ID
                        FROM $wpdb->posts
                        WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
                        ORDER BY post_date DESC
                    " );
				}
				foreach ( $results as $key => $result ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => $result->ID ) );
				}
			}
		}
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $taxonomy ) {
				$result = $wpdb->get_results( "
                    SELECT count(*) as cnt, max(term_order) as max, min(term_order) as min
                    FROM $wpdb->terms AS terms
                    INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
                    WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
                " );
				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}
				$results = $wpdb->get_results( "
                    SELECT terms.term_id
                    FROM $wpdb->terms AS terms
                    INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
                    WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
                    ORDER BY name ASC
                " );
				foreach ( $results as $key => $result ) {
					$wpdb->update( $wpdb->terms, array( 'term_order' => $key + 1 ), array( 'term_id' => $result->term_id ) );
				}
			}
		}
		wp_redirect( 'admin.php?page=scporder-settings&msg=update' );
	}

	public function scporder_previous_post_where( $where ) {
		global $post;
		$objects = $this->get_scporder_options_objects();
		if ( empty( $objects ) ) {
			return $where;
		}
		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$where = preg_replace( "/p.post_date < \'[0-9\-\s\:]+\'/i", "p.menu_order > '" . $post->menu_order . "'", $where );
		}

		return $where;
	}

	public function scporder_previous_post_sort( $orderby ) {
		global $post;
		$objects = $this->get_scporder_options_objects();
		if ( empty( $objects ) ) {
			return $orderby;
		}
		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order ASC LIMIT 1';
		}

		return $orderby;
	}

	public function scporder_next_post_where( $where ) {
		global $post;
		$objects = $this->get_scporder_options_objects();
		if ( empty( $objects ) ) {
			return $where;
		}
		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$where = preg_replace( "/p.post_date > \'[0-9\-\s\:]+\'/i", "p.menu_order < '" . $post->menu_order . "'", $where );
		}

		return $where;
	}

	public function scporder_next_post_sort( $orderby ) {
		global $post;
		$objects = $this->get_scporder_options_objects();
		if ( empty( $objects ) ) {
			return $orderby;
		}
		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order DESC LIMIT 1';
		}

		return $orderby;
	}

	public function scporder_pre_get_posts( $wp_query ) {
		$objects = $this->get_scporder_options_objects();
		if ( empty( $objects ) ) {
			return false;
		}
		if ( is_admin() ) {
			if ( isset( $wp_query->query['post_type'] ) && ! isset( $_GET['orderby'] ) ) {
				if ( in_array( $wp_query->query['post_type'], $objects ) ) {
					$wp_query->set( 'orderby', 'menu_order' );
					$wp_query->set( 'order', 'ASC' );
				}
			}
		} else {
			$active = false;
			if ( isset( $wp_query->query['post_type'] ) ) {
				if ( ! is_array( $wp_query->query['post_type'] ) ) {
					if ( in_array( $wp_query->query['post_type'], $objects ) ) {
						$active = true;
					}
				}
			}
			if ( ! $active ) {
				return false;
			}
			if ( isset( $wp_query->query['suppress_filters'] ) ) {
				if ( $wp_query->get( 'orderby' ) == 'date' ) {
					$wp_query->set( 'orderby', 'menu_order' );
				}
				if ( $wp_query->get( 'order' ) == 'DESC' ) {
					$wp_query->set( 'order', 'ASC' );
				}
			} else {
				if ( ! $wp_query->get( 'orderby' ) ) {
					$wp_query->set( 'orderby', 'menu_order' );
				}
				if ( ! $wp_query->get( 'order' ) ) {
					$wp_query->set( 'order', 'ASC' );
				}
			}
		}
	}

	public function scporder_get_terms_orderby( $orderby, $args ) {
		if ( is_admin() ) {
			return $orderby;
		}
		$tags = $this->get_scporder_options_tags();
		if ( ! isset( $args['taxonomy'] ) ) {
			return $orderby;
		}
		$taxonomy = $args['taxonomy'];
		if ( ! in_array( $taxonomy, $tags ) ) {
			return $orderby;
		}
		$orderby = 't.term_order';

		return $orderby;
	}

	public function scporder_get_object_terms( $terms ) {
		$tags = $this->get_scporder_options_tags();
		if ( is_admin() && isset( $_GET['orderby'] ) ) {
			return $terms;
		}
		foreach ( $terms as $key => $term ) {
			if ( is_object( $term ) && isset( $term->taxonomy ) ) {
				$taxonomy = $term->taxonomy;
				if ( ! in_array( $taxonomy, $tags ) ) {
					return $terms;
				}
			} else {
				return $terms;
			}
		}
		usort( $terms, array( $this, 'taxcmp' ) );

		return $terms;
	}

	public function taxcmp( $a, $b ) {
		if ( $a->term_order == $b->term_order ) {
			return 0;
		}

		return ( $a->term_order < $b->term_order ) ? - 1 : 1;
	}

	public function get_scporder_options_objects() {
		$scporder_options = get_option( 'scporder_options' ) ? get_option( 'scporder_options' ) : array();
		$objects          = isset( $scporder_options['objects'] ) && is_array( $scporder_options['objects'] ) ? $scporder_options['objects'] : array();

		return (array) $this->objects;
	}

	public function get_scporder_options_tags() {
		$scporder_options = get_option( 'scporder_options' ) ? get_option( 'scporder_options' ) : array();
		$tags             = isset( $scporder_options['tags'] ) && is_array( $scporder_options['tags'] ) ? $scporder_options['tags'] : array();

		return $tags;
	}
}