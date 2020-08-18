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

add_action( 'widgets_init', 'wpss_widget_blocks' );
function wpss_widget_blocks() {
	register_widget( 'WPSSWidgetBlocks' );
}

class WPSSWidgetBlocks extends WP_Widget {

	public function __construct() {
		$options = array(
			'description' => __( 'Show a content list', 'wpss' ),
			'classname'   => 'wpss-widget-blocks-container'
		);
		parent::__construct( 'wpss_widget_blocks', __( '(WPSS) Last Posts', 'wpss' ), $options );
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		/*** Get widget content*/
		self::wpss_widget_frontend(
			$instance['wpss_post_type'],
			$instance['wpss_post_limit'],
			$instance['wpss_excerpt_limit'],
			$instance['wpss_post_order_by'],
			$instance['wpss_post_order'],
			$instance['wpss_block_type'],
			$instance['widget_id'],
			$instance['wpss_widget_ajax']
		);

		if ( $instance['wpss_widget_ajax'] === 'yes' ):
			self::wpss_widget_ajax_script(
				$instance['wpss_post_type'], // post type
				$instance['widget_id'], // button id
				$instance['wpss_post_limit'], // posts per page
				$instance['wpss_excerpt_limit'], // post excerpt
				$instance['wpss_post_order_by'], // order by
				$instance['wpss_post_order'], // order
				$instance['wpss_block_type'] // block type
			);
		endif;

		echo $args['after_widget'];
	}

	public function form( $instance ) {

		/*** Widget ID */
		if ( isset( $instance['widget_id'] ) ) {
			$widget_id = $instance['widget_id'];
		} else {
			$widget_id = $this->id;
		}

		/*** Block Title */
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Last Posts', 'wpss' );
		}

		/*** Block Post Type */
		if ( isset( $instance['wpss_post_type'] ) ) {
			$wpss_post_type = $instance['wpss_post_type'];
		} else {
			$wpss_post_type = 'post';
		}

		/*** Block Post Limit */
		if ( isset( $instance['wpss_post_limit'] ) ) {
			$posts_limit = $instance['wpss_post_limit'];
		} else {
			$posts_limit = 3;
		}

		/*** Excerpt Limit */
		if ( isset( $instance['wpss_excerpt_limit'] ) ) {
			$excerpt_limit = $instance['wpss_excerpt_limit'];
		} else {
			$excerpt_limit = 20;
		}

		/*** Block Order By*/
		$orderby_op = array(
			'date'  => __( 'Date', 'wpss' ),
			'rand'  => __( 'Rand', 'wpss' ),
			'title' => __( 'Título', 'wpss' ),
		);
		if ( isset( $instance['wpss_post_order_by'] ) ) {
			$post_order_by = $instance['wpss_post_order_by'];
		} else {
			$post_order_by = 'date';
		}

		/*** Block Order*/
		$order_op = array(
			'desc' => __( 'DESC', 'wpss' ),
			'asc'  => __( 'ASC', 'wpss' ),
		);
		if ( isset( $instance['wpss_post_order'] ) ) {
			$post_order = $instance['wpss_post_order'];
		} else {
			$post_order = 'desc';
		}

		/*** Block Style */
		$block_type_list = array(
			'block_1' => __( 'Block 1', 'wpss' ),
			'block_2' => __( 'Block 2', 'wpss' ),
			'block_3' => __( 'Block 3', 'wpss' ),
			'block_4' => __( 'Block 4', 'wpss' ),
			'block_5' => __( 'Block 5', 'wpss' ),
			'block_6' => __( 'Block 6', 'wpss' ),
			'block_7' => __( 'Block 7', 'wpss' ),
		);
		if ( isset( $instance['wpss_block_type'] ) ) {
			$block_type = $instance['wpss_block_type'];
		} else {
			$block_type = 'block_1';
		}

		/*** Ajax Load Posts */
		$widget_ajax_op = array(
			'yes' => __( 'Yes', 'wpss' ),
			'no'  => __( 'No', 'wpss' )
		);
		if ( isset( $instance['wpss_widget_ajax'] ) ) {
			$widget_ajax = $instance['wpss_widget_ajax'];
		} else {
			$widget_ajax = 'no';
		}

		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wpss' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'wpss_post_type' ); ?>"><?php _e( 'Post Type:', 'wpss' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'wpss_post_type' ); ?>" name="<?php echo $this->get_field_name( 'wpss_post_type' ); ?>" type="text" value="<?php echo esc_attr( $wpss_post_type ); ?>"/>
            <small><?php _e( 'Eg: post', 'wpss' ); ?></small>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'wpss_post_limit' ); ?>"><?php _e( 'Limit Posts:', 'wpss' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'wpss_post_limit' ); ?>" name="<?php echo $this->get_field_name( 'wpss_post_limit' ); ?>" type="number" value="<?php echo esc_attr( $posts_limit ); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'wpss_excerpt_limit' ); ?>"><?php _e( 'Excerpt Limit (Words):', 'wpss' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'wpss_excerpt_limit' ); ?>" name="<?php echo $this->get_field_name( 'wpss_excerpt_limit' ); ?>" type="number" value="<?php echo esc_attr( $excerpt_limit ); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'wpss_block_type' ); ?>"><?php _e( 'Block Type:', 'wpss' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'wpss_block_type' ); ?>" name="<?php echo $this->get_field_name( 'wpss_block_type' ); ?>">
				<?php foreach ( $block_type_list as $key => $value ): ?>
					<?php
					$selected = ( $block_type === $key ? " selected" : "" );
					echo "<option value='$key'$selected>$value</option>";
					?>
				<?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'wpss_post_order_by' ); ?>"><?php _e( 'Order By:', 'wpss' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'wpss_post_order_by' ); ?>" name="<?php echo $this->get_field_name( 'wpss_post_order_by' ); ?>">
				<?php foreach ( $orderby_op as $key => $value ): ?>
					<?php
					$selected = ( $post_order_by === $key ? " selected" : "" );
					echo "<option value='$key'$selected>$value</option>";
					?>
				<?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'wpss_post_order' ); ?>"><?php _e( 'Order:', 'wpss' ); ?></label><br>
			<?php foreach ( $order_op as $key => $value ): ?>
				<?php
				$checked = ( $post_order === $key ? " checked" : "" );
				echo "<input type='radio' name='" . $this->get_field_name( 'wpss_post_order' ) . "' id='" . $this->get_field_id( 'wpss_post_order' ) . "' value='$key'$checked>$value";
				?>
			<?php endforeach; ?>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'wpss_widget_ajax' ); ?>"><?php _e( 'Ajax Load:', 'wpss' ); ?></label><br>
			<?php foreach ( $widget_ajax_op as $key => $value ): ?>
				<?php
				$checked_ajax = ( $widget_ajax === $key ? " checked" : "" );
				echo "<input type='radio' name='" . $this->get_field_name( 'wpss_widget_ajax' ) . "' id='" . $this->get_field_id( 'wpss_widget_ajax' ) . "' value='$key'$checked_ajax>$value";
				?>
			<?php endforeach; ?>
        </p>

        <input class="widefat" id="<?php echo $this->get_field_id( 'widget_id' ); ?>" name="<?php echo $this->get_field_name( 'widget_id' ); ?>" type="hidden" value="<?php echo esc_attr( $widget_id ); ?>"/>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance                       = array();
		$instance['title']              = ( ! empty( $new_instance['title'] ) ) ? esc_html( $new_instance['title'] ) : '';
		$instance['wpss_post_type']     = ( ! empty( $new_instance['wpss_post_type'] ) ) ? esc_html( $new_instance['wpss_post_type'] ) : '';
		$instance['wpss_post_limit']    = ( ! empty( $new_instance['wpss_post_limit'] ) ) ? esc_html( $new_instance['wpss_post_limit'] ) : '';
		$instance['wpss_excerpt_limit'] = ( ! empty( $new_instance['wpss_excerpt_limit'] ) ) ? esc_html( $new_instance['wpss_excerpt_limit'] ) : 0;
		$instance['wpss_post_order_by'] = ( ! empty( $new_instance['wpss_post_order_by'] ) ) ? esc_html( $new_instance['wpss_post_order_by'] ) : '';
		$instance['wpss_post_order']    = ( ! empty( $new_instance['wpss_post_order'] ) ) ? esc_html( $new_instance['wpss_post_order'] ) : '';
		$instance['wpss_block_type']    = ( ! empty( $new_instance['wpss_block_type'] ) ) ? esc_html( $new_instance['wpss_block_type'] ) : '';
		$instance['widget_id']          = ( ! empty( $new_instance['widget_id'] ) ) ? esc_html( $new_instance['widget_id'] ) : '';
		$instance['wpss_widget_ajax']   = ( ! empty( $new_instance['wpss_widget_ajax'] ) ) ? esc_html( $new_instance['wpss_widget_ajax'] ) : '';

		return $instance;
	}

	public function wpss_widget_frontend( $post_type, $limit, $excerpt, $order_by, $order, $block_type, $widget_id, $ajax_load = 'no' ) {
		$cpt      = explode( ',', $post_type );
		$wpss_cpt = ( is_array( $cpt ) ? $cpt : array( $post_type ) );
		$args     = array(
			'post_type'      => $wpss_cpt,
			'posts_per_page' => $limit,
			'orderby'        => $order_by,
			'order'          => $order,
			'post_status'    => 'publish'
		);
		$widget   = new WP_Query( $args );

		if ( $widget->have_posts() ):
			echo "<div class='wpss-widget-blocks'>";
			echo( $block_type === 'block_7' ? "<ul class='wpss-widget-block-7 row p-0 m-0'>" : "<div class='row'>" );
			while ( $widget->have_posts() ): $widget->the_post();

				if ( $block_type === 'block_1' ):
					wpss_widget_block_1();
				endif;

				if ( $block_type === 'block_2' ):
					wpss_widget_block_2( $excerpt );
				endif;

				if ( $block_type === 'block_3' ):
					wpss_widget_block_3();
				endif;

				if ( $block_type === 'block_4' ):
					wpss_widget_block_4();
				endif;

				if ( $block_type === 'block_5' ):
					wpss_widget_block_5();
				endif;

				if ( $block_type === 'block_6' ):
					wpss_widget_block_6();
				endif;

				if ( $block_type === 'block_7' ):
					wpss_widget_block_7();
				endif;

			endwhile;

			echo( $block_type === 'block_7' ? "</ul>" : "</div>" );
			echo "</div>";

			wp_reset_postdata();

			if ( $ajax_load === 'yes' ):
				echo self::wpss_widget_ajax_btn( $widget_id );
			endif;

		else:
			_e( 'No content found', 'wpss' );
		endif;
	}

	public function wpss_widget_ajax_btn( $btn_id ) {
		$btn_label = __( 'Load More', 'wpss' );

		return "<div class='text-center'><a href='javascript:' title='$btn_label' id='$btn_id" . "-ajax-btn' class='btn btn-outline-dark'><i></i>$btn_label</a></div>";
	}

	public function wpss_widget_ajax_script( $post_type, $btn_id, $ppp, $excerpt, $order_by, $order, $block_type ) {
		?>
        <script>
            jQuery(function ($) {
                let ajaxUrl = "<?php echo admin_url( 'admin-ajax.php' )?>";
                let page = 1;
                let ppp = <?=$ppp?>;
                let post_type = '<?=$post_type?>';
                let excerpt = '<?=$excerpt?>';
                let order_by = '<?=$order_by?>';
                let order = '<?=$order?>';
                let block_type = '<?=$block_type?>';

                $("#<?=$btn_id?>-ajax-btn").on("click", function () {
                    $("#<?=$btn_id?>-ajax-btn").attr("disabled", true);
                    $.post(ajaxUrl, {
                        action: "wpss_widget_ajax_action",
                        security: '<?= wp_create_nonce( "wpss_widget_load_posts" ); ?>',
                        offset: (page * ppp),
                        post_type,
                        excerpt,
                        order_by,
                        order,
                        block_type,
                        beforeSend: function (xhr) {
                            $('#<?=$btn_id?>-ajax-btn i').addClass('fas fa-spinner fa-spin');
                            $('#<?=$btn_id?>-ajax-btn').addClass('disabled');
                        },
                        ppp: ppp
                    }).success(function (post_type) {
                        page++;
                        $("#<?=$btn_id?> .wpss-widget-blocks .row").append(post_type);
                        $("#<?=$btn_id?>-ajax-btn i").removeClass("fas fa-spinner fa-spin", false);
                        $('#<?=$btn_id?>-ajax-btn').removeClass('disabled');
                    });

                });
            })
        </script>
		<?php
	}

	public function wpss_widget_content_cb() {
		check_ajax_referer( 'wpss_widget_load_posts', 'security' );
		$offset     = $_POST["offset"];
		$ppp        = $_POST["ppp"];
		$post_type  = $_POST['post_type'];
		$excerpt    = $_POST['excerpt'];
		$order_by   = $_POST['order_by'];
		$order      = $_POST['order'];
		$block_type = $_POST['block_type'];

		$args   = array(
			'post_type'      => $post_type,
			'posts_per_page' => $ppp,
			'offset'         => $offset,
			'orderby'        => $order_by,
			'order'          => $order,
			'post_status'    => 'publish'
		);
		$widget = new WP_Query( $args );

		while ( $widget->have_posts() ): $widget->the_post();

			if ( $block_type === 'block_1' ):
				wpss_widget_block_1();
			endif;

			if ( $block_type === 'block_2' ):
				wpss_widget_block_2( $excerpt );
			endif;

			if ( $block_type === 'block_3' ):
				wpss_widget_block_3();
			endif;

			if ( $block_type === 'block_4' ):
				wpss_widget_block_4();
			endif;

			if ( $block_type === 'block_5' ):
				wpss_widget_block_5();
			endif;

			if ( $block_type === 'block_6' ):
				wpss_widget_block_6();
			endif;

			if ( $block_type === 'block_7' ):
				wpss_widget_block_7();
			endif;

		endwhile;

		wp_reset_postdata();

		exit;
	}
}


add_action( 'wp_ajax_wpss_widget_ajax_action', array( 'WPSSWidgetBlocks', 'wpss_widget_content_cb' ) );
add_action( 'wp_ajax_nopriv_wpss_widget_ajax_action', array( 'WPSSWidgetBlocks', 'wpss_widget_content_cb' ) );