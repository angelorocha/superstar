<?php
/**
 *
 * @param string $post_type
 * @param int $limit
 * @param string $title
 *
 * @param bool $date_limit
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

function wpss_related_content( $post_type = '', $limit = 3, $title = '', $date_limit = true ) {

	$post_type = ( ! empty( $post_type ) ? $post_type : get_post( get_the_ID() )->post_type );

	?>
    <div class="wpss-related-content">
		<?php if ( $title ): ?>
            <h3><?= $title; ?></h3>
		<?php endif; ?>
        <div class='row'>
			<?php
			$params = array(
				'orderby' => 'rand',
				'order'   => 'desc',
			);

			if ( $date_limit ):
				$params['date_query'] = array(
					array(
						'after'     => date( 'Y-m-d', strtotime( '-10 days', strtotime( get_post( get_queried_object_id() )->post_date ) ) ),
						'before'    => date( 'Y-m-d', strtotime( get_post( get_queried_object_id() )->post_date ) ),
						'inclusive' => true,
					),
				);
			endif;

			WPSSquery::wpss_make_query(
				$post_type, // Post type
				$limit, // Limit
				'wpss_widget_block_5', // Callback Function
				false,
				$params

			);
			?>
        </div>
    </div>
	<?php
}