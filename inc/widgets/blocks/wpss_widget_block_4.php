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

function wpss_widget_block_4( $cols = "col-md-3" ) {
	?>
    <div class="<?= $cols ?>">
        <div class="wpss-widget-block-4">
            <div class="wpss-featured-image">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">Continue Lendo</a>
                <img src="<?= wpss_image_src( get_the_ID(), 'wpss_thumbnail' ); ?>" alt="<?php the_title(); ?>">
            </div>
            <h4><?php the_title(); ?></h4>
            <span><i class="far fa-calendar-alt"></i> <?= get_the_date( 'd-m-Y' ); ?></span>
        </div>
    </div>
	<?php
}