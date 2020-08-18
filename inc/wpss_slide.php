<?php
/**
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 */

/*** Post Slide Metabox */
$slide_meta                = new WPSSMetaBox();
$slide_meta->metabox_id    = 'wpss_slide_metabox';
$slide_meta->metabox_title = __( 'Slide', 'wpss' );
$slide_meta->post_type     = array( 'post' );
$slide_meta->fields        = array(
	array(
		'name'    => __('Show in slide?','wpss'),
		'id'      => 'wpss_slide_show',
		'type'    => 'radio_inline',
		'options' => array(
            'yes' => __('Yes', 'wpss'),
			'no'  => __('No', 'wpss'),
		),
		'default' => 'no'
	),
);
/*** Add support to expire native function */
wpss_expire_metabox(
        'wpss_slide_expire',
        'post',
        __('Expire', 'wpss'),
        __('Expire Slide?','wpss')
);

/**
 * @param $post_type
 * @param $slide_limit
 * @param string $slide_size
 * @param int $caption_limit
 */
function wpss_slide( $post_type, $slide_limit, $slide_size = 'post_slide', $caption_limit = 20 ) {

	/*** Slide Query */
	$slide = new WP_Query(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => $slide_limit,
			'meta_key'       => 'wpss_slide_show',
			'meta_value'     => 'yes',
		)
	);
	if ( $slide->have_posts() ):
		?>
        <div id="wpss-slide" class="carousel slide carousel-fade wpss-slide" data-ride="carousel">
            <ol class="carousel-indicators">
				<?php
				for ( $count = 0; $count < $slide->post_count; $count ++ ):
					echo "<li data-target='#wpss-slide' data-slide-to='" . $count . "'" . ( $count === 0 ? " class='active'" : "" ) . "></li>";
				endfor;
				?>
            </ol>
            <div class="carousel-inner">
				<?php
				$count = 0;
				while ( $slide->have_posts() ): $slide->the_post(); ?>
                    <div class="carousel-item<?php echo( $count === 0 ? ' active' : '' ); ?>">

                        <a href="<?= get_permalink(); ?>" title="<?= get_the_title(); ?>">
                            <img src="<?= wpss_image_src( get_the_ID(), $slide_size ); ?>" class="d-block w-100" alt="<?= get_the_title(); ?>">
                        </a>

                        <div class="wpss-slide-caption">
                            <div class="row">
                                <div class="wpss-slide-control col-md-1">
                                    <a class="wpss-control-prev" href="#wpss-slide" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only"><?php _e('Previous','wpss'); ?></span>
                                    </a>
                                </div><!-- .wpss-slide-prev -->

                                <div class="wpss-slide-caption-text col-md-10">
                                    <h5 class="text-center"><?= get_the_title(); ?></h5>
                                    <p class="text-justify"><?= wpss_excerpt( $caption_limit ); ?></p>
                                </div>

                                <div class="wpss-slide-control col-md-1">
                                    <a class="wpss-control-next" href="#wpss-slide" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only"><?php _e('Next','wpss'); ?></span>
                                    </a>
                                </div><!-- .wpss-slide-next -->
                            </div><!-- .row -->
                        </div><!-- .wpss-slide-caption -->

                    </div>
					<?php $count ++; endwhile; ?>
				<?php wp_reset_postdata(); ?>
            </div>

        </div>
	<?php
	else:
		wpss_slide_cb( $post_type, $slide_size, $caption_limit );
	endif;
}

/*** Slide callback
 *
 * @param $post_type
 * @param $slide_size
 * @param $caption_limit
 */
function wpss_slide_cb( $post_type, $slide_size, $caption_limit ) {
	$slide = new WP_Query(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => 5
		)
	);
	?>
    <div id="wpss-slide" class="carousel slide carousel-fade wpss-slide" data-ride="carousel">
        <ol class="carousel-indicators">
			<?php
			for ( $count = 0; $count < $slide->post_count; $count ++ ):
				echo "<li data-target='#wpss-slide' data-slide-to='" . $count . "'" . ( $count === 0 ? " class='active'" : "" ) . "></li>";
			endfor;
			?>
        </ol>
        <div class="carousel-inner">
			<?php
			$count = 0;
			while ( $slide->have_posts() ): $slide->the_post(); ?>
                <div class="carousel-item<?php echo( $count === 0 ? ' active' : '' ); ?>">

                    <a href="<?= get_permalink(); ?>" title="<?= get_the_title(); ?>">
                        <img src="<?= wpss_image_src( get_the_ID(), $slide_size ); ?>" class="d-block w-100" alt="<?= get_the_title(); ?>">
                    </a>

                    <div class="wpss-slide-caption">
                        <div class="row">
                            <div class="wpss-slide-control col-md-1">
                                <a class="wpss-control-prev" href="#wpss-slide" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only"><?php _e('Previous','wpss'); ?></span>
                                </a>
                            </div><!-- .wpss-slide-prev -->

                            <div class="wpss-slide-caption-text col-md-10">
                                <h5 class="text-center"><?= get_the_title(); ?></h5>
                                <p class="text-justify"><?= wpss_excerpt( $caption_limit ); ?></p>
                            </div>

                            <div class="wpss-slide-control col-md-1">
                                <a class="wpss-control-next" href="#wpss-slide" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only"><?php _e('Next','wpss'); ?></span>
                                </a>
                            </div><!-- .wpss-slide-next -->
                        </div><!-- .row -->
                    </div><!-- .wpss-slide-caption -->

                </div>
				<?php $count ++; endwhile; ?>
			<?php wp_reset_postdata(); ?>
        </div>

    </div>
	<?php
}