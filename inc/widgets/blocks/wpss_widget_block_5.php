<?php
/**
 * @param string $cols
 *
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 * @author              Angelo Rocha
 */

function wpss_widget_block_5( $cols = "col-md-4" ) {
	?>
    <div class="<?= $cols ?>">
        <div class="wpss-widget-block-5">
            <a href="<?php the_permalink(); ?>" title="<?= __( 'Read More', 'wpss' ); ?>"><?= __( 'Read More', 'wpss' ); ?></a>
            <div class="wpss-block-image">
                <img src="<?= wpss_image_src( get_the_ID(), 'wpss_thumbnail' ); ?>" alt="<?= get_the_title() ?>">
            </div><!-- .wpss-related-image -->
            <div class="wpss-block-info">
                <h4>
                    <strong><?php the_title(); ?></strong>
                    <small>
                        <span><i class="far fa-calendar-alt"></i> <?= get_the_date( 'd-m-Y' ); ?></span>
                        <span><i class="far fa-eye"></i> <?= wpss_get_post_views( get_the_ID() ); ?></span>
                    </small>
                </h4>
            </div><!-- .wpss-related-info -->
        </div> <!-- .wpss-related-box -->
    </div> <!-- .col-md-4 -->
	<?php
}